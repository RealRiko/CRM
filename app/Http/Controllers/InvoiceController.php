<?php

namespace App\Http\Controllers;

use App\Models\Company;
use App\Models\Document;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class InvoiceController extends Controller
{
    /**
     * Require authentication for all actions.
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display a listing of all sales invoices for the authenticated user's company.
     */
    public function index()
    {
        Log::info('InvoiceController@index accessed by user ID: ' . auth()->id());

        $company = auth()->user()->company;
        if (!$company) {
            Log::warning('User ID ' . auth()->id() . ' attempted to access invoices with no assigned company.');
            return redirect()->route('dashboard')->with('error', 'No company assigned.');
        }

        $invoices = Document::where('type', 'sales_invoice')
            ->where('company_id', $company->id)
            ->with(['client', 'lineItems.product'])
            ->latest()
            ->get();

        return view('documents.invoices', compact('invoices'));
    }

    /**
     * Display the specified sales invoice details.
     *
     * @param  int  $id
     */
    public function show($id)
    {
        Log::info("InvoiceController@show accessed for ID: {$id} by user ID: " . auth()->id());

        $company = auth()->user()->company;
        if (!$company) {
            Log::warning('User ID ' . auth()->id() . ' attempted to view invoice with no assigned company.');
            return redirect()->route('dashboard')->with('error', 'No company assigned.');
        }

        $invoice = Document::where('type', 'sales_invoice')
            ->where('company_id', $company->id)
            ->with(['client', 'lineItems.product'])
            ->findOrFail($id);

        return view('documents.show', compact('invoice'));
    }
}
