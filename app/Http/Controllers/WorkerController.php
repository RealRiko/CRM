<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Company;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\Support\Facades\Log;

class WorkerController extends Controller
{
    /**
     * Require authentication.
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display a listing of all workers for the admin's company.
     */
    public function index()
    {
        $user = auth()->user();
        $company = $user->company;

        if (!$company) {
            return redirect()->route('company.required')->with('error', 'You are not assigned to a company.');
        }

        $workers = User::where('company_id', $company->id)->get();

        return view('workers.index', compact('workers', 'company'));
    }

    /**
     * Show the form for creating a new worker.
     */
    public function create()
    {
        $user = auth()->user();

        if (!$user->isAdmin()) {
            return redirect()->route('dashboard')->with('error', 'You do not have permission to create workers.');
        }

        $company = $user->company;

        // Automatically create a company if admin has none
        if (!$company) {
            $company = Company::create([
                'name' => $user->name . "'s Company",
                'country' => 'Latvia',
            ]);

            $user->update([
                'company_id' => $company->id,
                'role' => 'admin',
            ]);

            Log::info("Created company ID {$company->id} for admin user ID {$user->id}");
        }

        return view('workers.create-worker', compact('company'));
    }

    /**
     * Store a newly created worker in storage.
     */
    public function store(Request $request)
    {
        $user = auth()->user();

        if (!$user->isAdmin()) {
            return redirect()->route('dashboard')->with('error', 'You do not have permission to create workers.');
        }

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'surname' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $company = $user->company;

        // If the admin somehow has no company, create one automatically
        if (!$company) {
            $company = Company::create([
                'name' => $user->name . "'s Company",
                'country' => 'Latvia',
            ]);
            $user->update(['company_id' => $company->id]);
        }

        // Create the worker and assign them to the same company
        $worker = User::create([
            'name' => $validated['name'],
            'surname' => $validated['surname'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'company_id' => $company->id,
            'role' => 'user',
        ]);

        Log::info("Worker ID {$worker->id} created for company ID {$company->id} by admin ID {$user->id}");

        return redirect()
            ->route('workers.index')
            ->with('success', 'Worker created successfully and linked to your company.');
    }
}
