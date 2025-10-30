<?php

namespace App\Http\Controllers;

use App\Models\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;
use App\Traits\LiveSearchTrait;

class ClientController extends Controller
{
    use LiveSearchTrait;

    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display a list of clients for the authenticated user's company.
     */
    public function index()
    {
        $user = auth()->user();
        $company = $user->company;

        if (!$company) {
            Log::warning("User ID {$user->id} attempted to access clients without a company.");
            return redirect()->route('company.required')->with('error', 'You must belong to a company.');
        }

        $clients = Client::where('company_id', $company->id)
            ->when(request('search'), function ($query, $search) {
                $query->where(function ($subQuery) use ($search) {
                    $subQuery->where('name', 'like', "%{$search}%")
                             ->orWhere('email', 'like', "%{$search}%")
                             ->orWhere('phone', 'like', "%{$search}%");
                });
            })
            ->latest()
            ->get();

        return view('clients.index', compact('clients'));
    }

    /**
     * Handle live search (AJAX endpoint)
     */
    public function liveSearch(Request $request)
    {
        return $this->performLiveSearch($request, Client::class);
    }

    /**
     * Show create form.
     */
    public function create()
    {
        $company = auth()->user()->company;

        if (!$company) {
            Log::warning("User ID " . auth()->id() . " tried to access clients.create without company.");
            return redirect()->route('company.required')->with('error', 'Please create or join a company first.');
        }

        return view('clients.create');
    }

    /**
     * Store a new client.
     */
    public function store(Request $request)
    {
        $company = auth()->user()->company;

        if (!$company) {
            Log::warning("User ID " . auth()->id() . " tried to store a client without company.");
            return redirect()->route('company.required')->with('error', 'Please join or create a company first.');
        }

        $validated = $request->validate([
            'name'                => ['required', 'string', 'max:255'],
            'email'               => ['required', 'email', 'unique:clients,email'],
            'phone'               => ['nullable', 'string', 'max:20'],
            'address'             => ['nullable', 'string', 'max:255'],
            'city'                => ['nullable', 'string', 'max:100'],
            'postal_code'         => ['nullable', 'string', 'max:20'],
            'registration_number' => ['nullable', 'string', 'max:100'],
            'vat_number'          => ['nullable', 'string', 'max:100'],
            'bank'                => ['nullable', 'string', 'max:255'],
            'bank_account'        => ['nullable', 'string', 'max:255'],
        ]);

        $validated['company_id'] = $company->id;

        $client = Client::create($validated);

        Log::info('Client created successfully', [
            'client_id'  => $client->id,
            'company_id' => $company->id,
            'user_id'    => auth()->id(),
        ]);

        return redirect()->route('clients.index')->with('success', 'Client created successfully.');
    }

    /**
     * Show the edit form.
     */
    public function edit(Client $client)
    {
        $this->authorizeClientAccess($client);
        return view('clients.edit', compact('client'));
    }

    /**
     * Update an existing client.
     */
    public function update(Request $request, Client $client)
    {
        $this->authorizeClientAccess($client);

        $validated = $request->validate([
            'name'                => ['required', 'string', 'max:255'],
            'email'               => ['required', 'email', Rule::unique('clients', 'email')->ignore($client->id)],
            'phone'               => ['nullable', 'string', 'max:20'],
            'address'             => ['nullable', 'string', 'max:255'],
            'city'                => ['nullable', 'string', 'max:100'],
            'postal_code'         => ['nullable', 'string', 'max:20'],
            'registration_number' => ['nullable', 'string', 'max:100'],
            'vat_number'          => ['nullable', 'string', 'max:100'],
            'bank'                => ['nullable', 'string', 'max:255'],
            'bank_account'        => ['nullable', 'string', 'max:255'],
        ]);

        $client->update($validated);

        Log::info('Client updated successfully', [
            'client_id' => $client->id,
            'user_id'   => auth()->id(),
        ]);

        return redirect()->route('clients.index')->with('success', 'Client updated successfully.');
    }
    // Add this destroy method to your ClientController

public function destroy(Client $client)
{
    $user = auth()->user();
    $company = $user->company;

    // Check if user has a company
    if (!$company) {
        return redirect()->route('clients.index')->with('error', 'You are not assigned to a company.');
    }

    // Check if client belongs to the same company
    if ($client->company_id !== $company->id) {
        return redirect()->route('clients.index')->with('error', 'You do not have permission to delete this client.');
    }

    // Optional: Check if client has related documents/invoices
    // Uncomment if you want to prevent deletion of clients with existing documents
    /*
    if ($client->documents()->count() > 0) {
        return redirect()->route('clients.index')->with('error', 'Cannot delete client with existing documents.');
    }
    */

    Log::info("Client ID {$client->id} deleted by user ID {$user->id}");

    $client->delete();

    return redirect()
        ->route('clients.index')
        ->with('success', 'Client deleted successfully.');
}

    /**
     * Ensure the user can access the client.
     */
    protected function authorizeClientAccess(Client $client)
    {
        $user = auth()->user();
        if (!$user->company || $client->company_id !== $user->company->id) {
            Log::error("User {$user->id} attempted unauthorized access to client {$client->id}");
            abort(403, 'Unauthorized action.');
        }
    }
}
