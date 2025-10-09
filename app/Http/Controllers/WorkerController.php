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
     * Create a new controller instance.
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display a listing of the workers.
     */
    public function index()
    {
        Log::info('Accessing workers index for user ID: ' . (auth()->check() ? auth()->id() : 'not authenticated'));

        $company = auth()->user()->company ?? null;
        $workers = $company ? User::where('company_id', $company->id)->get() : collect();

        if ($workers->isEmpty() && $company) {
            return view('workers.index', compact('workers'))
                ->with('message', 'No workers found for your company.');
        }

        return view('workers.index', compact('workers'));
    }

    /**
     * Show the form for creating a new worker.
     */
    public function create()
    {
        $user = auth()->user();

        Log::info('User ID ' . $user->id . ' with role ' . ($user->role ?? 'null') . ' attempting to access workers/create');

        $company = $user->company;

        if (!$company) {
            $company = Company::firstOrCreate(
                ['name' => 'Default Company'],
                ['country' => 'Latvia']
            );

            $user->company_id = $company->id;
            $user->role = 'admin';
            $user->save();

            Log::info('Created new company ID ' . $company->id . ' and set user ID ' . $user->id . ' as admin');
        }

        if (method_exists($user, 'isAdmin') && !$user->isAdmin()) {
            Log::warning('Non-admin user ID ' . $user->id . ' attempted to access workers/create');
            return redirect()->route('dashboard')->with('error', 'You do not have permission to create workers.');
        }

        return view('workers.create-worker', compact('company'));
    }

    /**
     * Store a newly created worker in storage.
     */
    public function store(Request $request)
    {
        $user = auth()->user();

        Log::info('User ID ' . $user->id . ' with role ' . ($user->role ?? 'null') . ' attempting to create worker');

        if (method_exists($user, 'isAdmin') && !$user->isAdmin()) {
            Log::warning('Non-admin user ID ' . $user->id . ' attempted to create a worker');
            return redirect()->route('dashboard')->with('error', 'You do not have permission to create workers.');
        }

        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'surname' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $company = $user->company;
        if (!$company) {
            Log::warning('No company found for user ID ' . $user->id . ' during worker creation.');
            return redirect()->back()->with('error', 'Unable to create worker due to missing company.');
        }

        $worker = User::create([
            'name' => $request->name . ' ' . $request->surname,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'company_id' => $company->id,
            'role' => 'user',
        ]);

        Log::info('Created worker ID ' . $worker->id . ' for company ID ' . $company->id);

        return redirect()->route('workers.create')->with('success', 'Worker created successfully.');
    }
}
