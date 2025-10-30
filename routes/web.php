<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\GoalController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\InventoryController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DocumentController;
use App\Http\Controllers\WorkerController;
use App\Http\Controllers\LiveSearchController;

Route::get('/', function () {
    if (auth()->check()) {
        return redirect()->route('dashboard');
    }
    return view('welcome');
})->name('home');

// Registration
Route::get('/register', [RegisteredUserController::class, 'create'])->name('register');
Route::post('/register', [RegisteredUserController::class, 'store'])->name('register.store');

// Error page
Route::get('/company-required', function () {
    return view('errors.company_required');
})->name('company.required');

// Protected routes
Route::middleware('auth')->group(function () {

    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // ADMIN SETTINGS (company, goal, documents)
    Route::get('/admin/company-settings', [GoalController::class, 'companySettingsView'])
        ->name('admin.companySettings');

    Route::post('/admin/set-goal', [GoalController::class, 'setGoal'])
        ->name('admin.setGoal');

    Route::post('/admin/update-company-details', [GoalController::class, 'updateCompanyDetails'])
        ->name('admin.updateCompanyDetails');

    Route::post('/admin/update-document-settings', [GoalController::class, 'updateDocumentSettings'])
        ->name('admin.updateDocumentSettings');

    // Profile
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Workers
    Route::resource('workers', WorkerController::class)->except(['show']);

    // Products
    Route::resource('products', ProductController::class)->except(['show']);

    // Inventory / Storage Management
    Route::get('/storage', [InventoryController::class, 'index'])->name('inventory.index');
    Route::put('/storage/{product}', [InventoryController::class, 'updateQuantity'])->name('inventory.updateQuantity');

    // Clients
    Route::resource('clients', ClientController::class);

    // Documents
    Route::resource('documents', DocumentController::class)->except(['show']);
    Route::get('/documents/{document}', [DocumentController::class, 'show'])->name('documents.show');
    Route::get('/documents/{document}/pdf', [DocumentController::class, 'generatePdf'])->name('documents.pdf');

    // Invoices
    Route::get('/invoices', [InvoiceController::class, 'index'])->name('invoices.index');
    Route::get('/invoices/{id}', [InvoiceController::class, 'show'])->name('invoices.show');
    
    Route::get('/live-search', [LiveSearchController::class, 'search'])->name('live-search')->middleware('auth');
// Invoices - CREATE
Route::get('/invoices/create', [InvoiceController::class, 'create'])->name('invoices.create');
Route::post('/invoices', [InvoiceController::class, 'store'])->name('invoices.store');

// Reports Export (PDF)
Route::get('/reports/export', [DocumentController::class, 'exportReport'])
    ->name('reports.export');

});

require __DIR__ . '/auth.php';
