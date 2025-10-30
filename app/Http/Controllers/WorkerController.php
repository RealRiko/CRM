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
    public function __construct()
    {
        $this->middleware('auth');
    }

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

    public function create()
    {
        $user = auth()->user();

        if (!$user->isAdmin()) {
            return redirect()->route('dashboard')->with('error', 'You do not have permission to create workers.');
        }

        $company = $user->company;

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

        if (!$company) {
            $company = Company::create([
                'name' => $user->name . "'s Company",
                'country' => 'Latvia',
            ]);
            $user->update(['company_id' => $company->id]);
        }

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

    // ----------------- EDIT/UPDATE -----------------
    public function edit(User $worker)
    {
        $user = auth()->user();

        if (!$user->isAdmin() || $worker->company_id !== $user->company_id) {
            return redirect()->route('workers.index')->with('error', 'You do not have permission to edit this worker.');
        }

        return view('workers.edit-worker', compact('worker'));
    }

    public function update(Request $request, User $worker)
    {
        $user = auth()->user();

        if (!$user->isAdmin() || $worker->company_id !== $user->company_id) {
            return redirect()->route('workers.index')->with('error', 'You do not have permission to update this worker.');
        }

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'surname' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', "unique:users,email,{$worker->id}"],
            'password' => ['nullable', 'confirmed', Rules\Password::defaults()],
        ]);

        $worker->name = $validated['name'];
        $worker->surname = $validated['surname'];
        $worker->email = $validated['email'];

        if (!empty($validated['password'])) {
            $worker->password = Hash::make($validated['password']);
        }

        $worker->save();

        Log::info("Worker ID {$worker->id} updated by admin ID {$user->id}");

        return redirect()
            ->route('workers.index')
            ->with('success', 'Worker updated successfully.');
    }

    // ----------------- DESTROY -----------------
    public function destroy(User $worker)
    {
        $user = auth()->user();

        // Check if user is admin
        if (!$user->isAdmin()) {
            return redirect()->route('workers.index')->with('error', 'You do not have permission to delete workers.');
        }

        // Check if worker belongs to the same company
        if ($worker->company_id !== $user->company_id) {
            return redirect()->route('workers.index')->with('error', 'You do not have permission to delete this worker.');
        }

        // Prevent deleting yourself
        if ($user->id === $worker->id) {
            return redirect()->route('workers.index')->with('error', 'You cannot delete your own account.');
        }

        Log::info("Worker ID {$worker->id} deleted by admin ID {$user->id}");

        $worker->delete();

        return redirect()
            ->route('workers.index')
            ->with('success', 'Worker deleted successfully.');
    }
}