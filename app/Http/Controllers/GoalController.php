<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Company; // Import the Company model

class GoalController extends Controller
{
    /**
     * GoalController constructor.
     * The middleware is now typically handled in the routes file.
     */
    public function __construct()
    {
        // Removed explicit middleware setup as per previous context
    }

    /**
     * Displays the goal setting view.
     */
    public function goalView()
    {
        $user = auth()->user();

        // Assuming user must have a company relationship
        $company = $user->company;

        if (!$company) {
            // Redirect to dashboard if company relationship is missing
            return redirect()->route('dashboard')->with('error', 'No company assigned to your user account.');
        }

        // The goal setting form view
        return view('dashboard.goal', compact('company'));
    }

    /**
     * Handles the submission to set or update the monthly revenue goal.
     */
    public function setGoal(Request $request)
    {
        $user = auth()->user();
        
        // **FIX 1: Get the Company model directly by ID**
        // We bypass the potentially cached user relationship ($user->company) 
        // and fetch the Company object directly from the database using its ID.
        $company = Company::find($user->company_id);

        // Ensure company exists before proceeding
        if (!$company) {
             return redirect()->route('dashboard')->with('error', 'Cannot update goal: No company assigned.');
        }

        // Validate the incoming request data
        $validated = $request->validate([
            'monthly_goal' => 'required|numeric|min:0|max:999999999.99', // Added a reasonable max value
        ]);

        // Update the goal in the database
        $company->update(['monthly_goal' => $validated['monthly_goal']]);

        // **FIX 2: Force complete user refresh in the session**
        // This is the most robust way to ensure the authenticated session 
        // object is up-to-date for the redirect request.
        auth()->setUser($user->fresh());

        // FIX: Redirects back to the main 'dashboard' route to display the updated goal diagram.
        return redirect()->route('dashboard')->with('success', 'Monthly revenue goal updated successfully.');
    }
}