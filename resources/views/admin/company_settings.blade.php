@extends('layouts.app')

@section('title', 'Company Settings - ' . config('app.name', 'Inventory Management'))

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

        {{-- Success Message --}}
        @if (session('success'))
            <div class="bg-amber-100 border border-amber-400 text-amber-700 px-4 py-3 rounded-xl relative" role="alert">
                <strong class="font-bold">Success!</strong>
                <span class="block sm:inline">{{ session('success') }}</span>
            </div>
        @endif

        {{-- Monthly Goal Setting --}}
        <div class="p-6 sm:p-8 bg-white dark:bg-gray-800 shadow-xl sm:rounded-xl border border-gray-100 dark:border-gray-700 transition-colors">
            <div class="max-w-xl">
                <h3 class="text-2xl font-bold text-amber-600 dark:text-amber-400 mb-4">Set Monthly Revenue Goal</h3>
                <p class="text-gray-600 dark:text-gray-300 mb-6">Enter the amount you're aiming for this month to help track your progress.</p>

                <form method="POST" action="{{ route('admin.setGoal') }}" class="mt-6 space-y-6">
                    @csrf

                    <div>
                        <label for="monthly_goal" class="block font-medium text-sm text-gray-700 dark:text-gray-300">Monthly Revenue Goal (€)</label>
                        <input id="monthly_goal" name="monthly_goal" type="number" step="0.01" min="0"
                               class="border-gray-300 dark:border-gray-600 focus:border-amber-500 focus:ring-amber-500 rounded-xl shadow-sm mt-1 block w-full transition duration-150 dark:bg-gray-700 dark:text-gray-100"
                               value="{{ old('monthly_goal', $company->monthly_goal ?? '') }}" required autofocus />
                        @error('monthly_goal')<p class="text-sm text-red-500 mt-2">{{ $message }}</p>@enderror
                    </div>

                    <div class="flex items-center gap-4">
                        <button type="submit"
                                class="inline-flex items-center px-6 py-3 bg-amber-600 border border-transparent rounded-xl font-semibold text-sm text-white hover:bg-amber-700 active:bg-amber-800 focus:outline-none focus:ring-2 focus:ring-amber-500 focus:ring-offset-2 transition ease-in-out duration-150 shadow-md">
                            Save Goal
                        </button>
                    </div>
                </form>
            </div>
        </div>

        {{-- Company Invoice Details --}}
        <div class="p-6 sm:p-8 bg-white dark:bg-gray-800 shadow-xl sm:rounded-xl border border-gray-100 dark:border-gray-700 transition-colors">
            <div class="max-w-3xl">
                <h3 class="text-2xl font-bold text-amber-600 dark:text-amber-400 mb-4">Invoice Company Details (Sender Information)</h3>
                <p class="text-gray-600 dark:text-gray-300 mb-6">These details will appear on all your generated invoices and estimates.</p>

                <form method="POST" action="{{ route('admin.updateCompanyDetails') }}" class="mt-6 space-y-6" enctype="multipart/form-data">
                    @csrf

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        {{-- Company Name --}}
                        <div>
                            <label for="company_name" class="block font-medium text-sm text-gray-700 dark:text-gray-300">Company Name</label>
                            <input id="company_name" name="company_name" type="text"
                                   class="border-gray-300 dark:border-gray-600 focus:border-amber-500 focus:ring-amber-500 rounded-xl shadow-sm mt-1 block w-full dark:bg-gray-700 dark:text-gray-100"
                                   value="{{ old('company_name', $company->name ?? '') }}" required />
                            @error('company_name')<p class="text-sm text-red-500 mt-2">{{ $message }}</p>@enderror
                        </div>

                        {{-- Registration Number --}}
                        <div>
                            <label for="reg_number" class="block font-medium text-sm text-gray-700 dark:text-gray-300">Registration Number</label>
                            <input id="reg_number" name="reg_number" type="text"
                                   class="border-gray-300 dark:border-gray-600 focus:border-amber-500 focus:ring-amber-500 rounded-xl shadow-sm mt-1 block w-full dark:bg-gray-700 dark:text-gray-100"
                                   value="{{ old('reg_number', $company->registration_number ?? '') }}" />
                            @error('reg_number')<p class="text-sm text-red-500 mt-2">{{ $message }}</p>@enderror
                        </div>

                        {{-- VAT Number --}}
                        <div>
                            <label for="vat_number" class="block font-medium text-sm text-gray-700 dark:text-gray-300">VAT Number</label>
                            <input id="vat_number" name="vat_number" type="text"
                                   class="border-gray-300 dark:border-gray-600 focus:border-amber-500 focus:ring-amber-500 rounded-xl shadow-sm mt-1 block w-full dark:bg-gray-700 dark:text-gray-100"
                                   value="{{ old('vat_number', $company->vat_number ?? '') }}" />
                            @error('vat_number')<p class="text-sm text-red-500 mt-2">{{ $message }}</p>@enderror
                        </div>

                        {{-- Bank Name --}}
                        <div>
                            <label for="bank_name" class="block font-medium text-sm text-gray-700 dark:text-gray-300">Bank Name</label>
                            <input id="bank_name" name="bank_name" type="text"
                                   class="border-gray-300 dark:border-gray-600 focus:border-amber-500 focus:ring-amber-500 rounded-xl shadow-sm mt-1 block w-full dark:bg-gray-700 dark:text-gray-100"
                                   value="{{ old('bank_name', $company->bank_name ?? '') }}" />
                            @error('bank_name')<p class="text-sm text-red-500 mt-2">{{ $message }}</p>@enderror
                        </div>

                        {{-- Account Number --}}
                        <div>
                            <label for="account_number" class="block font-medium text-sm text-gray-700 dark:text-gray-300">Bank Account Number (IBAN)</label>
                            <input id="account_number" name="account_number" type="text"
                                   class="border-gray-300 dark:border-gray-600 focus:border-amber-500 focus:ring-amber-500 rounded-xl shadow-sm mt-1 block w-full dark:bg-gray-700 dark:text-gray-100"
                                   value="{{ old('account_number', $company->account_number ?? '') }}" />
                            @error('account_number')<p class="text-sm text-red-500 mt-2">{{ $message }}</p>@enderror
                        </div>

                        {{-- Address --}}
                        <div>
                            <label for="address" class="block font-medium text-sm text-gray-700 dark:text-gray-300">Address (Street, House No.)</label>
                            <input id="address" name="address" type="text"
                                   class="border-gray-300 dark:border-gray-600 focus:border-amber-500 focus:ring-amber-500 rounded-xl shadow-sm mt-1 block w-full dark:bg-gray-700 dark:text-gray-100"
                                   value="{{ old('address', $company->address ?? '') }}" />
                            @error('address')<p class="text-sm text-red-500 mt-2">{{ $message }}</p>@enderror
                        </div>

                        {{-- City --}}
                        <div>
                            <label for="city" class="block font-medium text-sm text-gray-700 dark:text-gray-300">City</label>
                            <input id="city" name="city" type="text"
                                   class="border-gray-300 dark:border-gray-600 focus:border-amber-500 focus:ring-amber-500 rounded-xl shadow-sm mt-1 block w-full dark:bg-gray-700 dark:text-gray-100"
                                   value="{{ old('city', $company->city ?? '') }}" />
                            @error('city')<p class="text-sm text-red-500 mt-2">{{ $message }}</p>@enderror
                        </div>

                        {{-- Postal Code --}}
                        <div>
                            <label for="postal_code" class="block font-medium text-sm text-gray-700 dark:text-gray-300">Postal Code</label>
                            <input id="postal_code" name="postal_code" type="text"
                                   class="border-gray-300 dark:border-gray-600 focus:border-amber-500 focus:ring-amber-500 rounded-xl shadow-sm mt-1 block w-full dark:bg-gray-700 dark:text-gray-100"
                                   value="{{ old('postal_code', $company->postal_code ?? '') }}" />
                            @error('postal_code')<p class="text-sm text-red-500 mt-2">{{ $message }}</p>@enderror
                        </div>
                    </div>

                    {{-- Footer Contacts --}}
                    <div>
                        <label for="footer_contacts" class="block font-medium text-sm text-gray-700 dark:text-gray-300">Footer Contacts (Tel, Email, Web)</label>
                        <textarea id="footer_contacts" name="footer_contacts" rows="2"
                                  class="border-gray-300 dark:border-gray-600 focus:border-amber-500 focus:ring-amber-500 rounded-xl shadow-sm mt-1 block w-full dark:bg-gray-700 dark:text-gray-100">{{ old('footer_contacts', $company->footer_contacts ?? '') }}</textarea>
                        @error('footer_contacts')<p class="text-sm text-red-500 mt-2">{{ $message }}</p>@enderror
                    </div>

                    {{-- Company Logo --}}
                    <div>
                        <label class="block font-medium text-sm text-gray-700 dark:text-gray-300 mb-2">Company Logo</label>

                        <div class="relative w-full">
                            <input id="logo" name="logo" type="file" 
                                   class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10" 
                                   onchange="document.getElementById('file-name').textContent = this.files[0]?.name || 'Choose a file';" />
                            
                            <button type="button" 
                                    class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl bg-white dark:bg-gray-700 text-gray-700 dark:text-gray-100 text-sm font-medium shadow-sm hover:bg-gray-50 dark:hover:bg-gray-600 flex items-center justify-between">
                                <span id="file-name">Choose a file</span>
                                <svg class="w-5 h-5 ml-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v16h16V4H4zm16 0l-8 8-8-8" />
                                </svg>
                            </button>
                        </div>

                        @if ($company->logo_path)
                            <p class="mt-2 text-sm text-gray-600 dark:text-gray-300">Current Logo:</p>
                            <img src="{{ asset('storage/' . $company->logo_path) }}" alt="Current Logo" class="mt-2 h-16 rounded-lg border p-1" />
                        @endif

                        @error('logo')<p class="text-sm text-red-500 mt-2">{{ $message }}</p>@enderror
                    </div>

                    <div class="flex items-center gap-4 mt-4">
                        <button type="submit"
                                class="inline-flex items-center px-6 py-3 bg-amber-600 border border-transparent rounded-xl font-semibold text-sm text-white hover:bg-amber-700 active:bg-amber-800 focus:outline-none focus:ring-2 focus:ring-amber-500 focus:ring-offset-2 transition ease-in-out duration-150 shadow-md">
                            Save Details
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
                </form>
            </div>
        </div>

    </div>
</div>
@endsection
