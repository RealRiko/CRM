<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\Company;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ClientController extends Controller
{
    /**
     * Require authentication for all actions.
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display all clients for the authenticated user's company.
     */
    public function index()
    {
        Log::info('ClientController@index accessed by user ID: ' . auth()->id());

        $company = auth()->user()->company;

        if (!$company) {
            Log::warning('User ID ' . auth()->id() . ' attempted to access clients without an assigned company.');
            return redirect()->route('dashboard')->with('error', 'No company assigned.');
        }

        $clients = Client::query()
            ->where('company_id', $company->id)
            ->when(request('search'), function ($query, $search) {
                $query->where('name', 'like', "%{$search}%")
                      ->orWhere('email', 'like', "%{$search}%");
            })
            ->latest()
            ->get();

        return view('clients.index', compact('clients'));
    }

    /**
     * Show the form to create a new client.
     */
    public function create()
    {
        $company = auth()->user()->company;

        if (!$company || !auth()->user()->isAdmin()) {
            Log::warning('Unauthorized access to client creation by user ID ' . auth()->id());
            return redirect()->route('dashboard')->with('error', 'Permission denied.');
        }

        return view('clients.create', compact('company'));
    }

    /**
     * Store a newly created client in the database.
     */
    public function store(Request $request)
    {
        Log::info('ClientController@store called by user ID: ' . auth()->id(), $request->all());

        $company = auth()->user()->company;

        if (!$company || !auth()->user()->isAdmin()) {
            Log::warning('Unauthorized attempt to store a client by user ID ' . auth()->id());
            return redirect()->route('dashboard')->with('error', 'Permission denied.');
        }

        $validated = $request->validate([
            'name'  => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'unique:clients,email'],
            'phone' => ['nullable', 'string', 'max:20'],
        ]);

        $validated['company_id'] = $company->id;

        $client = Client::create($validated);

        Log::info('Client created successfully', [
            'client_id' => $client->id,
            'company_id' => $company->id,
        ]);

        return redirect()->route('clients.index')->with('success', 'Client created successfully.');
    }
}
