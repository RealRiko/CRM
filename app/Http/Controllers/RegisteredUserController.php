<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Company;
use App\Providers\RouteServiceProvider;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Show the registration form.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     */
    public function store(Request $request): JsonResponse|RedirectResponse
    {
        // 1. Validation (Laravel automatically handles 422 JSON responses if expectsJson() is true)
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'surname' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'country' => ['required', 'string', 'max:100'],
            'company_name' => ['required', 'string', 'max:255'],
        ]);

        DB::beginTransaction();

        try {
            // Determine role: first user = admin, others = user
            $role = User::count() === 0 ? 'admin' : 'user';

            // 2. Create company
            $company = Company::create([
                'name' => $request->company_name,
                'country' => $request->country,
            ]);

            // 3. Create user
            $user = User::create([
                'name' => $request->name,
                'surname' => $request->surname,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'company_id' => $company->id,
                'role' => $role,
            ]);

            DB::commit();

            event(new Registered($user));
            Auth::login($user);

            // 4. JSON or redirect response
            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'Registration successful.',
                    'redirect' => RouteServiceProvider::HOME,
                ], 201);
            }

            return redirect(RouteServiceProvider::HOME);

        } catch (\Throwable $e) {
            DB::rollBack();

            Log::error('Registration failed.', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            // 5. JSON error response
            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'Registration failed due to a server error. Please contact support.',
                    'error_details' => config('app.debug') ? $e->getMessage() : null,
                ], 500);
            }

            // Fallback for form submission
            return back()
                ->withInput()
                ->withErrors(['email' => 'Registration failed due to a server error. Please contact support.']);
        }
    }
}
