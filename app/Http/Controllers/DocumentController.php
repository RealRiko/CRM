<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\Document;
use App\Models\Product;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Support\Facades\File;

class DocumentController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $company = auth()->user()->company;
        if (!$company) {
            return redirect()->route('company.required')->with('error', 'No company assigned.');
        }

        $documents = Document::where('company_id', $company->id)
            ->with(['client', 'lineItems.product'])
            ->latest()
            // LIMITATION: Use ->paginate() instead of ->get() if you have thousands of documents!
            ->get();

        return view('documents.index', compact('documents'));
    }

    public function create()
    {
        $user = auth()->user();
        $company = $user->company;

        if (!$company) {
            return redirect()->route('company.required')->with('error', 'Please join or create a company first.');
        }

        // Good use of eager loading for selection views is to only select needed columns
        $clients = Client::where('company_id', $company->id)->select('id', 'name')->get();
        $products = Product::where('company_id', $company->id)->select('id', 'name')->get();

        return view('documents.create', compact('clients', 'products', 'company'));
    }

    public function store(Request $request)
    {
        // *** REFACTOR SUGGESTION: Move validation to a FormRequest class ***
        $user = auth()->user();
        $company = $user->company;

        if (!$company) {
            return redirect()->route('dashboard')->with('error', 'Please join or create a company first.');
        }

        $validated = $request->validate([
            'type' => 'required|in:estimate,sales_order,sales_invoice',
            'client_id' => 'required|exists:clients,id',
            'invoice_date' => 'required|date_format:Y-m-d',
            'delivery_days' => 'required|integer|min:1',
            'status' => 'required|in:draft,sent,paid,cancelled',
            'line_items' => 'required|array|min:1',
            'line_items.*.product_id' => 'required|exists:products,id',
            'line_items.*.quantity' => 'required|integer|min:1',
            'line_items.*.price' => 'required|numeric|min:0',
        ]);

        // *** REFACTOR SUGGESTION: Move line item processing to a Service or Model method ***
        $total = 0;
        $lineItemsData = [];
        foreach ($validated['line_items'] as $item) {
            $subtotal = $item['quantity'] * $item['price'];
            $total += $subtotal;
            $lineItemsData[] = [
                'product_id' => $item['product_id'],
                'quantity' => $item['quantity'],
                'price' => $item['price'],
                'subtotal' => $subtotal,
            ];
        }

        $invoiceDate = Carbon::createFromFormat('Y-m-d', $validated['invoice_date']);
        $dueDate = $invoiceDate->copy()->addDays((int)$validated['delivery_days']);

        $document = Document::create(array_merge($validated, [
            'due_date' => $dueDate,
            'total' => $total,
            'company_id' => $company->id,
        ]));

        $document->lineItems()->createMany($lineItemsData);

        return redirect()->route('documents.index')->with('success', 'Document created successfully.');
    }

    public function edit(Document $document)
    {
        // Authorization check moved to a Policy/Gate ideally, but functional here.
        if ($document->company_id !== auth()->user()->company->id) {
            return redirect()->route('dashboard')->with('error', 'Permission denied.');
        }

        $company = auth()->user()->company;
        $clients = Client::where('company_id', $company->id)->select('id', 'name')->get();
        $products = Product::where('company_id', $company->id)->select('id', 'name')->get();

        return view('documents.edit', compact('document', 'clients', 'products'));
    }

    public function update(Request $request, Document $document)
    {
        // Authorization check
        if ($document->company_id !== auth()->user()->company->id) {
            return redirect()->route('dashboard')->with('error', 'Permission denied.');
        }

        // ... (Validation and line item processing logic removed for brevity, as it's the same as store) ...

        // Placeholder for validation and calculation logic
        $validated = $request->validate([
            // ... all your validation rules
            'line_items' => 'required|array|min:1',
        ]);
        
        $total = 0; // Recalculate total here
        $lineItemsData = []; // Recalculate line items data here

        $invoiceDate = Carbon::createFromFormat('Y-m-d', $validated['invoice_date']);
        $dueDate = $invoiceDate->copy()->addDays((int)$validated['delivery_days']);

        $document->update(array_merge($validated, [
            'due_date' => $dueDate,
            'total' => $total,
        ]));

        $document->lineItems()->delete();
        $document->lineItems()->createMany($lineItemsData);

        return redirect()->route('documents.index')->with('success', 'Document updated successfully.');
    }

    public function generatePdf(Document $document)
    {
        // Authorization check
        if ($document->company_id !== auth()->user()->company->id) {
             return redirect()->route('dashboard')->with('error', 'Permission denied.');
        }

        // *** CRITICAL PERFORMANCE FIX: Load logo outside this function or use Queues ***
        $document->load(['client', 'lineItems.product']);

        $logoBase64 = null;
        $logoPath = public_path('images/company_logo.png');

        if (File::exists($logoPath)) {
             // Ideally, read the logo and base64 encode it once and cache it globally!
            $logoType = pathinfo($logoPath, PATHINFO_EXTENSION);
            $logoData = base64_encode(File::get($logoPath));
            $logoBase64 = "data:image/{$logoType};base64,{$logoData}";
        }

        // *** MAJOR PERFORMANCE WARNING: This line is CPU intensive and should be queued if possible ***
        $pdf = Pdf::loadView('documents.pdf', compact('document', 'logoBase64'));
        return $pdf->download("{$document->type}-{$document->id}.pdf");
    }

    public function show($id)
    {
        $company = auth()->user()->company;

        $document = Document::where('company_id', $company->id)
            ->with(['client', 'lineItems.product'])
            ->findOrFail($id);

        return view('documents.show', compact('document'));
    }
}