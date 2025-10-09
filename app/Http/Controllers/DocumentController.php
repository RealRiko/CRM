<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\Document;
use App\Models\Company;
use App\Models\Product;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\File;

class DocumentController extends Controller
{
    /**
     * Require authentication.
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display a listing of company documents.
     */
    public function index()
    {
        Log::info('DocumentController@index accessed');

        $company = auth()->user()->company;

        if (!$company) {
            return redirect()->route('dashboard')->with('error', 'No company assigned.');
        }

        $documents = Document::where('company_id', $company->id)
            ->with(['client', 'lineItems.product'])
            ->latest()
            ->get();

        return view('documents.index', compact('documents'));
    }

    /**
     * Show form for creating a new document.
     */
    public function create()
    {
        $user = auth()->user();
        $company = $user->company;

        if (!$company || (method_exists($user, 'isAdmin') && !$user->isAdmin())) {
            Log::warning('Unauthorized access to create document by user ID ' . $user->id);
            return redirect()->route('dashboard')->with('error', 'Permission denied.');
        }

        $clients = Client::where('company_id', $company->id)->get();
        $products = Product::where('company_id', $company->id)->get();

        return view('documents.create', compact('clients', 'products', 'company'));
    }

    /**
     * Store a newly created document.
     */
    public function store(Request $request)
    {
        Log::info('DocumentController@store called', ['data' => $request->all()]);

        $user = auth()->user();
        $company = $user->company;

        if (!$company || (method_exists($user, 'isAdmin') && !$user->isAdmin())) {
            Log::warning('Unauthorized store attempt by user ID ' . $user->id);
            return redirect()->route('dashboard')->with('error', 'Permission denied.');
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

    /**
     * Show form to edit an existing document.
     */
    public function edit(Document $document)
    {
        $user = auth()->user();
        $company = $user->company;

        if (
            !$company ||
            $document->company_id !== $company->id ||
            (method_exists($user, 'isAdmin') && !$user->isAdmin())
        ) {
            Log::warning("Unauthorized edit access to document ID {$document->id} by user ID {$user->id}");
            return redirect()->route('dashboard')->with('error', 'Permission denied.');
        }

        $clients = Client::where('company_id', $company->id)->get();
        $products = Product::where('company_id', $company->id)->get();

        return view('documents.edit', compact('document', 'clients', 'products'));
    }

    /**
     * Update an existing document.
     */
    public function update(Request $request, Document $document)
    {
        Log::info('DocumentController@update called', [
            'document_id' => $document->id,
            'data' => $request->all(),
        ]);

        $user = auth()->user();
        $company = $user->company;

        if (
            !$company ||
            $document->company_id !== $company->id ||
            (method_exists($user, 'isAdmin') && !$user->isAdmin())
        ) {
            Log::warning("Unauthorized update attempt on document ID {$document->id} by user ID {$user->id}");
            return redirect()->route('dashboard')->with('error', 'Permission denied.');
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

        $document->update(array_merge($validated, [
            'due_date' => $dueDate,
            'total' => $total,
        ]));

        $document->lineItems()->delete();
        $document->lineItems()->createMany($lineItemsData);

        return redirect()->route('documents.index')->with('success', 'Document updated successfully.');
    }

    /**
     * Generate a PDF version of a document.
     */
    public function generatePdf(Document $document)
    {
        $company = auth()->user()->company;

        if (!$company || $document->company_id !== $company->id) {
            Log::warning("Unauthorized PDF generation for document ID {$document->id}");
            return redirect()->route('dashboard')->with('error', 'Permission denied.');
        }

        $document->load(['client', 'lineItems.product']);

        $logoBase64 = null;
        $logoPath = public_path('images/company_logo.png');

        if (File::exists($logoPath)) {
            $logoType = pathinfo($logoPath, PATHINFO_EXTENSION);
            $logoData = base64_encode(File::get($logoPath));
            $logoBase64 = "data:image/{$logoType};base64,{$logoData}";
        }

        $pdf = Pdf::loadView('documents.pdf', compact('document', 'logoBase64'));
        return $pdf->download("{$document->type}-{$document->id}.pdf");
    }

    /**
     * Dashboard summary for company.
     */
    public function dashboard()
    {
        $company = auth()->user()->company;

        if (!$company) {
            return redirect()->route('company.required')->with('error', 'No company found.');
        }

        $productCount = Product::where('company_id', $company->id)->count();
        $clientCount = Client::where('company_id', $company->id)->count();
        $documentCount = Document::where('company_id', $company->id)->count();

        return view('dashboard', compact('productCount', 'clientCount', 'documentCount'));
    }

    /**
     * Display a specific document.
     */
    public function show($id)
    {
        $company = auth()->user()->company;

        $document = Document::where('company_id', $company->id)
            ->with(['client', 'lineItems.product'])
            ->findOrFail($id);

        return view('documents.show', compact('document'));
    }
}
