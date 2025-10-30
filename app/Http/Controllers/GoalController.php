<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class GoalController extends Controller
{
    /**
     * Show combined goal, company, and document settings.
     */
    public function companySettingsView()
    {
        $user = auth()->user();
        $company = $user->company;

        if (!$company) {
            return redirect()->route('company.required')->with('error', 'Please create your company first.');
        }

        return view('admin.company_settings', compact('company'));
    }

    /**
     * Save monthly goal.
     */
    public function setGoal(Request $request)
    {
        $validated = $request->validate([
            'monthly_goal' => 'required|numeric|min:0',
        ]);

        $company = auth()->user()->company;
        $company->update(['monthly_goal' => $validated['monthly_goal']]);

        return back()->with('success', 'Monthly goal updated successfully.');
    }

    /**
     * Update company details & logo.
     */
    public function updateCompanyDetails(Request $request)
    {
        $company = auth()->user()->company;

        $validated = $request->validate([
            'company_name' => 'required|string|max:255',
            'reg_number' => 'nullable|string|max:50',
            'address' => 'nullable|string|max:255',
            'city' => 'nullable|string|max:100',
            'postal_code' => 'nullable|string|max:20',
            'bank_name' => 'nullable|string|max:100',
            'account_number' => 'nullable|string|max:50',
            'vat_number' => 'nullable|string|max:50',
            'footer_contacts' => 'nullable|string|max:500',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        $updateData = [
            'name' => $validated['company_name'],
            'registration_number' => $validated['reg_number'] ?? null,
            'address' => $validated['address'] ?? null,
            'city' => $validated['city'] ?? null,
            'postal_code' => $validated['postal_code'] ?? null,
            'bank_name' => $validated['bank_name'] ?? null,
            'account_number' => $validated['account_number'] ?? null,
            'vat_number' => $validated['vat_number'] ?? null,
            'footer_contacts' => $validated['footer_contacts'] ?? null,
        ];

        if ($request->hasFile('logo')) {
            if ($company->logo_path) {
                Storage::disk('public')->delete($company->logo_path);
            }
            $updateData['logo_path'] = $request->file('logo')->store('logos', 'public');
        }

        $company->update($updateData);

        return back()->with('success', 'Company details updated successfully.');
    }

    /**
     * Update document settings.
     */
    public function updateDocumentSettings(Request $request)
    {
        $company = auth()->user()->company;

        $validated = $request->validate([
            'invoice_prefix' => 'nullable|string|max:10',
            'estimate_prefix' => 'nullable|string|max:10',
        ]);

        $company->update([
            'invoice_prefix' => $validated['invoice_prefix'] ?? 'INV-',
            'estimate_prefix' => $validated['estimate_prefix'] ?? 'EST-',
        ]);

        return back()->with('success', 'Document settings updated successfully.');
    }
}
