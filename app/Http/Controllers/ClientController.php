<?php

namespace App\Http\Controllers;

use App\Models\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule; // Import Rule for unique validation

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
        $user = auth()->user();
        Log::info('ClientController@index accessed by user ID: ' . $user->id);

        $company = $user->company;

        if (!$company) {
            Log::warning('User ID ' . $user->id . ' attempted to access clients without a company.');
            return redirect()->route('company.required')->with('error', 'You must belong to a company.');
        }

        $clients = Client::query()
            ->where('company_id', $company->id)
            ->when(request('search'), function ($query, $search) {
                $query->where(function ($subQuery) use ($search) {
                    $subQuery->where('name', 'like', "%{$search}%")
                             ->orWhere('email', 'like', "%{$search}%");
                });
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
        $user = auth()->user();
        $company = $user->company;

        if (!$company) {
            Log::warning('User ID ' . $user->id . ' tried to access clients.create without company.');
            return redirect()->route('company.required')->with('error', 'Please create or join a company first.');
        }

        // ✅ Allow all users in the same company to create clients
        return view('clients.create', compact('company'));
    }

    /**
     * Store a newly created client in the database.
     */
    public function store(Request $request)
    {
        $user = auth()->user();
        $company = $user->company;

        Log::info('ClientController@store called by user ID: ' . $user->id);

        if (!$company) {
            Log::warning('User ID ' . $user->id . ' tried to store a client without company.');
            return redirect()->route('company.required')->with('error', 'Please join or create a company first.');
        }

        $validated = $request->validate([
            'name'  => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'unique:clients,email'],
            'phone' => ['nullable', 'string', 'max:20'],
            'address' => ['nullable', 'string', 'max:255'],
        ]);

        $validated['company_id'] = $company->id;

        $client = Client::create($validated);

        Log::info('Client created successfully', [
            'client_id' => $client->id,
            'company_id' => $company->id,
            'user_id' => $user->id,
        ]);

        return redirect()->route('clients.index')->with('success', 'Client created successfully.');
    }

    /**
     * Show the form for editing the specified client.
     */
    public function edit(Client $client)
    {
        $this->authorizeClientAccess($client);

        return view('clients.edit', compact('client'));
    }

    /**
     * Update the specified client in the database.
     */
    public function update(Request $request, Client $client)
    {
        $this->authorizeClientAccess($client);
        
        $validated = $request->validate([
            'name'  => ['required', 'string', 'max:255'],
            // Ensures the email is unique *except* for the current client's email
            'email' => ['required', 'email', Rule::unique('clients', 'email')->ignore($client->id)],
            'phone' => ['nullable', 'string', 'max:20'],
            'address' => ['nullable', 'string', 'max:255'],
        ]);
        
        $client->update($validated);

        return redirect()->route('clients.index')->with('success', 'Client updated successfully.');
    }

    /**
     * Helper function to ensure the user has access to the client.
     * Checks if the client belongs to the user's company.
     */
    protected function authorizeClientAccess(Client $client)
    {
        $user = auth()->user();
        
        if (!$user->company || $client->company_id !== $user->company->id) {
            Log::error("User {$user->id} attempted unauthorized access to client {$client->id}");
            // Use abort(403) or throw an AuthorizationException in a real app
            abort(403, 'Unauthorized action.');
        }
    }
}