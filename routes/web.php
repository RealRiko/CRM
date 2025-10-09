<?php

use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DocumentController;
use App\Http\Controllers\WorkerController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\RegisteredUserController; 
// Removed 'use App\Http\Controllers\Controller;' as it's usually not needed here

// ----------------------------------------------------------------------
// PUBLIC ROUTES
// ----------------------------------------------------------------------

// Root route: Redirects authenticated users to dashboard, shows 'welcome' view to guests.
Route::get('/', function () {
    if (auth()->check()) {
        return redirect()->route('dashboard'); 
    }
    return view('welcome'); // Show a default welcome page
})->name('home');

// Registration Routes
Route::get('/register', [RegisteredUserController::class, 'create'])->name('register');
Route::post('/register', [RegisteredUserController::class, 'store'])->name('register');

// Company Required Safe Route
// This route breaks the infinite redirect loop by providing a static page 
// for users who are logged in but lack critical company data.
Route::get('/company-required', function () {
    // You MUST create this view file: resources/views/errors/company_required.blade.php
    return view('errors.company_required');
})->name('company.required');

Route::middleware('auth')->group(function () {
    
    // Dashboard
    Route::get('/dashboard', [DocumentController::class, 'dashboard'])->name('dashboard');
    
    // Profile
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');     
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Workers
    Route::get('/workers', [WorkerController::class, 'index'])->name('workers.index');
    Route::get('/workers/create', [WorkerController::class, 'create'])->name('workers.create');
    Route::get('/workers/edit', [WorkerController::class, 'edit'])->name('workers.edit');
    Route::post('/workers', [WorkerController::class, 'store'])->name('workers.store');

    // Products
    Route::get('/products', [ProductController::class, 'index'])->name('products.index');
    Route::get('/products/create', [ProductController::class, 'create'])->name('products.create');
    Route::post('/products', [ProductController::class, 'store'])->name('products.store');
    Route::get('/products/{product}/edit', [ProductController::class, 'edit'])->name('products.edit');
    Route::patch('/products/{product}', [ProductController::class, 'update'])->name('products.update');

    // Clients
    Route::get('/clients', [ClientController::class, 'index'])->name('clients.index');
    Route::get('/clients/create', [ClientController::class, 'create'])->name('clients.create');
    Route::get('/products/edit', [ProductController::class, 'edit'])->name('clients.edit');
    Route::post('/clients', [ClientController::class, 'store'])->name('clients.store');
    Route::get('/clients/show', [ClientController::class, 'show'])->name('clients.show');

    // Documents
    Route::resource('documents', DocumentController::class)->except(['show']);
    Route::get('/documents/{document}', [DocumentController::class, 'show'])->name('documents.show');
    Route::get('/documents/{document}/pdf', [DocumentController::class, 'generatePdf'])->name('documents.pdf');

    // Invoices
    Route::get('/invoices', [InvoiceController::class, 'index'])->name('invoices.index');
    Route::get('/invoices/{id}', [InvoiceController::class, 'show'])->name('invoices.show');
});

require __DIR__ . '/auth.php';