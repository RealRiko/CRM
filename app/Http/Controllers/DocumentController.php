<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\Document;
use App\Models\Product;
use App\Models\Inventory;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class DocumentController extends Controller
{
    // Konstruktors — nodrošina, ka visām šīs kontroliera metodēm jābūt autentificētām
    public function __construct()
    {
        $this->middleware('auth');
    }

    // Rāda dokumentu sarakstu uzņēmumam, kuram pieder pieteikušais lietotājs
    public function index()
    {
        $company = auth()->user()->company;

        // Ja lietotājam nav pievienots uzņēmums — pāradresēt
        if (!$company) {
            return redirect()->route('company.required')->with('error', 'No company assigned.');
        }

        // Iegūst dokumentus ar klienta un rindu vienību datiem, sakārtotus pēc jaunākā
        $documents = Document::where('company_id', $company->id)
            ->with(['client', 'lineItems.product'])
            ->latest()
            ->get();

        return view('documents.index', compact('documents'));
    }

    // Parāda formu jaunam dokumentam
    public function create()
    {
        $user = auth()->user();
        $company = $user->company;

        // Ja nav uzņēmuma — prasīt pievienoties/pievienot
        if (!$company) {
            return redirect()->route('company.required')->with('error', 'Please join or create a company first.');
        }

        // Iegūst klientus attiecīgajam uzņēmumam
        $clients = Client::where('company_id', $company->id)->select('id', 'name')->get();

        // Iegūst produktus ar noliktavas informāciju un pievieno īpašumu stock
        $products = Product::where('company_id', $company->id)
            ->with('inventory')
            ->select('id', 'name', 'price')
            ->get()
            ->map(function ($product) {
                // Pievieno lauku stock, kurā ir pieejamais daudzums (vai 0, ja nav inventory)
                $product->stock = $product->inventory->quantity ?? 0;
                return $product;
            });

        return view('documents.create', compact('clients', 'products', 'company'));
    }

    // Saglabā jaunu dokumentu un attiecīgi koriģē noliktavas atlikumus, ja nepieciešams
    public function store(Request $request)
    {
        $user = auth()->user();
        $company = $user->company;

        // Ja nav uzņēmuma — atgriezt ar kļūdas paziņojumu
        if (!$company) {
            return redirect()->route('dashboard')->with('error', 'Please join or create a company first.');
        }

        // --- Noteikt pieļaujamās status vērtības atkarībā no tipa ---
        $type = $request->input('type');
        $allowedStatuses = [];
        if ($type === 'estimate') {
            $allowedStatuses = ['draft', 'sent'];
        } elseif ($type === 'sales_order') {
            $allowedStatuses = ['draft', 'confirmed', 'cancelled'];
        } elseif ($type === 'sales_invoice') {
            $allowedStatuses = ['waiting_payment', 'paid'];
        }

        // Validācija — pārbauda lauku formātu un pieejamību
        $validated = $request->validate([
            'type' => 'required|in:estimate,sales_order,sales_invoice',
            'client_id' => 'required|exists:clients,id',
            'invoice_date' => 'required|date_format:Y-m-d',
            'delivery_days' => 'required|integer|min:1',
            'status' => 'required|in:' . implode(',', $allowedStatuses),
            'line_items' => 'required|array|min:1',
            'line_items.*.product_id' => 'required|exists:products,id',
            'line_items.*.quantity' => 'required|integer|min:1',
            'line_items.*.price' => 'required|numeric|min:0',
        ]);

        // --- Aprēķina kopējo summu un sagatavo rindu vienību datus ---
        $total = 0;
        $lineItemsData = [];
        foreach ($validated['line_items'] as $item) {
            $subtotal = $item['quantity'] * $item['price'];
            $total += $subtotal;
            $lineItemsData[] = [
                'product_id' => $item['product_id'],
                'quantity'   => $item['quantity'],
                'price'      => $item['price'],
                'subtotal'   => $subtotal,
            ];
        }

        // Aprēķina termiņu (due_date) no invoice_date + delivery_days
        $invoiceDate = Carbon::createFromFormat('Y-m-d', $validated['invoice_date']);
        $dueDate = $invoiceDate->copy()->addDays((int)$validated['delivery_days']);

        // Izveido dokumentu datubāzē
        $document = Document::create(array_merge($validated, [
            'due_date' => $dueDate,
            'total' => $total,
            'company_id' => $company->id,
        ]));

        // Izveido rindu vienības (line items)
        $document->lineItems()->createMany($lineItemsData);

        // Ja dokuments ir pārdošanas pasūtījums vai rēķins — samazina noliktavas atlikumu
        if (in_array($validated['type'], ['sales_order', 'sales_invoice'])) {
            $this->handleInventoryAdjustment($lineItemsData, $company->id, 'decrement');
        }

        return redirect()->route('documents.index')->with('success', 'Document created successfully.');
    }

    // Atjaunina esošu dokumentu; atjauno noliktavu (atgriež veco daudzumu, tad pielieto jauno)
    public function update(Request $request, Document $document)
    {
        // Pārbauda, vai dokumentam pieder tas pats uzņēmums kā pieteikušajam lietotājam
        if ($document->company_id !== auth()->user()->company->id) {
            return redirect()->route('dashboard')->with('error', 'Permission denied.');
        }

        // --- Noteikt pieļaujamās status vērtības atkarībā no tipa ---
        $type = $request->input('type');
        $allowedStatuses = [];
        if ($type === 'estimate') {
            $allowedStatuses = ['draft', 'sent'];
        } elseif ($type === 'sales_order') {
            $allowedStatuses = ['draft', 'confirmed', 'cancelled'];
        } elseif ($type === 'sales_invoice') {
            $allowedStatuses = ['waiting_payment', 'paid'];
        }

        // Validācija
        $validated = $request->validate([
            'type' => 'required|in:estimate,sales_order,sales_invoice',
            'client_id' => 'required|exists:clients,id',
            'invoice_date' => 'required|date_format:Y-m-d',
            'delivery_days' => 'required|integer|min:1',
            'status' => 'required|in:' . implode(',', $allowedStatuses),
            'line_items' => 'required|array|min:1',
            'line_items.*.product_id' => 'required|exists:products,id',
            'line_items.*.quantity' => 'required|integer|min:1',
            'line_items.*.price' => 'required|numeric|min:0',
        ]);

        $companyId = $document->company_id;

        // --- Atgriež noliktavā vecos daudzumus, ja iepriekšējais dokuments bija sales_order vai sales_invoice ---
        if (in_array($document->type, ['sales_order', 'sales_invoice'])) {
            $oldLineItems = $document->lineItems()->select('product_id', 'quantity')->get()->toArray();
            $this->handleInventoryAdjustment($oldLineItems, $companyId, 'increment');
        }

        // --- Aprēķina jauno kopējo summu un sagatavo rindu vienību datus ---
        $total = 0;
        $lineItemsData = [];
        foreach ($validated['line_items'] as $item) {
            $subtotal = $item['quantity'] * $item['price'];
            $total += $subtotal;
            $lineItemsData[] = [
                'product_id' => $item['product_id'],
                'quantity'   => $item['quantity'],
                'price'      => $item['price'],
                'subtotal'   => $subtotal,
            ];
        }

        // Aprēķina jauno due_date
        $invoiceDate = Carbon::createFromFormat('Y-m-d', $validated['invoice_date']);
        $dueDate = $invoiceDate->copy()->addDays((int)$validated['delivery_days']);

        // Atjaunina dokumenta laukus
        $document->update(array_merge($validated, [
            'due_date' => $dueDate,
            'total' => $total,
        ]));

        // Noņem vecās rindu vienības un pievieno jaunas
        $document->lineItems()->delete();
        $document->lineItems()->createMany($lineItemsData);

        // Ja jaunais dokuments ir sales_order vai sales_invoice — samazina noliktavu pēc jaunajām rindām
        if (in_array($validated['type'], ['sales_order', 'sales_invoice'])) {
            $this->handleInventoryAdjustment($lineItemsData, $companyId, 'decrement');
        }

        return redirect()->route('documents.index')->with('success', 'Document updated successfully.');
    }

    // Parāda rediģēšanas lapu dokumentam (vienkārši atgriež view ar nepieciešamajiem datiem)
    public function edit(Document $document)
    {
        // Pārbauda piekļuvi
        if ($document->company_id !== auth()->user()->company->id) {
            return redirect()->route('dashboard')->with('error', 'Permission denied.');
        }

        $company = auth()->user()->company;

        // Iegūst klientus uzņēmumam
        $clients = Client::where('company_id', $company->id)->select('id', 'name')->get();

        // Iegūst produktus ar noliktavas datiem un pievieno stock īpašumu
        $products = Product::where('company_id', $company->id)
            ->with('inventory')
            ->select('id', 'name', 'price')
            ->get()
            ->map(function ($product) {
                $product->stock = $product->inventory->quantity ?? 0;
                return $product;
            });

        return view('documents.edit', compact('document', 'clients', 'products'));
    }

    // Dzēš dokumentu — atjauno noliktavas atlikumus, ja dokuments bija pārdošanas raksturs
    public function destroy(Document $document)
    {
        $user = auth()->user();
        $company = $user->company;

        // Pārbauda, vai lietotājs vispār ir pievienots uzņēmumam
        if (!$company) {
            return redirect()->route('documents.index')->with('error', 'You are not assigned to a company.');
        }

        // Pārbauda, vai dokuments pieder tam pašam uzņēmumam
        if ($document->company_id !== $company->id) {
            return redirect()->route('documents.index')->with('error', 'You do not have permission to delete this document.');
        }

        // Ja dokuments ir sales_order vai sales_invoice — atgriež katras rindas daudzumu noliktavā
        if (in_array($document->type, ['sales_order', 'sales_invoice'])) {
            foreach ($document->lineItems as $item) {
                $inventory = \App\Models\Inventory::firstOrCreate(
                    ['product_id' => $item->product_id, 'company_id' => $company->id],
                    ['quantity' => 0]
                );
                // Palielina inventory par dzēšamā dokumenta rindu daudzumu
                $inventory->increment('quantity', $item->quantity);
                \Log::info("Restored {$item->quantity} units to Product ID {$item->product_id} after deleting Document ID {$document->id}");
            }
        }

        // Ieraksta logā, kurš izdzēsa dokumentu
        Log::info("Document ID {$document->id} (Type: {$document->type}) deleted by user ID {$user->id}");

        // Dzēš dokumentu (ja migrācijās iestatīta cascade, arī lineItems tiks dzēstas)
        $document->delete();

        return redirect()
            ->route('documents.index')
            ->with('success', 'Document deleted successfully and stock restored.');
    }

    // ģenerē PDF no dokumenta datiem un uzņēmuma logotipa (ja ir)
    public function generatePdf(Document $document)
    {
        // Pārbauda piekļuvi - tikai uzņēmuma lietotāji var ģenerēt
        if ($document->company_id !== auth()->user()->company->id) {
            return redirect()->route('dashboard')->with('error', 'Permission denied.');
        }

        // Ielādē nepieciešamos attiecīgos relācijas datus
        $document->load(['client', 'lineItems.product']);
        $company = $document->company;

        // Sagatavo bāzes64 attēlu uzņēmuma logotipam, ja tas saglabāts
        $logoBase64 = null;

        if ($company && $company->logo_path && Storage::disk('public')->exists($company->logo_path)) {
            $logoData = Storage::disk('public')->get($company->logo_path);
            $logoType = pathinfo($company->logo_path, PATHINFO_EXTENSION);
            $logoBase64 = 'data:image/' . $logoType . ';base64,' . base64_encode($logoData);
        }

        // Izveido PDF no skata documents.pdf
        $pdf = Pdf::loadView('documents.pdf', compact('document', 'logoBase64', 'company'));
        return $pdf->download("{$document->type}-{$document->id}.pdf");
    }

    // Rāda konkrēta dokumenta detaļas
    public function show($id)
    {
        $company = auth()->user()->company;

        // Iegūst dokumentu pēc id tikai ja tas pieder lietotāja uzņēmumam
        $document = Document::where('company_id', $company->id)
            ->with(['client', 'lineItems.product'])
            ->findOrFail($id);

        return view('documents.show', compact('document'));
    }

    // Privāta funkcija noliktavas korekcijai — increment vai decrement pēc nepieciešamības
    private function handleInventoryAdjustment(array $lineItemsData, int $companyId, string $action): void
    {
        foreach ($lineItemsData as $item) {
            // Ja rindas datus nav korekti, izlaist
            if (!isset($item['product_id'], $item['quantity'])) {
                continue;
            }

            $productId = $item['product_id'];
            $quantity = (int)$item['quantity'];

            // Iegūst vai izveido inventory ierakstu konkrētajam produktam un uzņēmumam
            $inventory = Inventory::firstOrCreate(
                ['product_id' => $productId, 'company_id' => $companyId],
                ['quantity' => 0]
            );

            // Atbilstoši action pieliek vai atņem daudzumu
            if ($action === 'decrement') {
                $inventory->decrement('quantity', $quantity);
            } elseif ($action === 'increment') {
                $inventory->increment('quantity', $quantity);
            }

            // Ieraksta izmaiņas logā (debug/informācijas nolūkos)
            Log::info("Inventory {$action}: {$quantity} units for Product ID {$productId}. New qty: {$inventory->quantity}");
        }
    }
}
