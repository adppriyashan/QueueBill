<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\CompanyController;
use App\Http\Controllers\InvoiceStructureTemplateController;
use App\Http\Controllers\RecurringServiceController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\GoogleDriveController;

// Public / Guest Auth Routes
Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login']);
    Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
    Route::post('/register', [RegisterController::class, 'register']);
});

// Authenticated Routes
Route::middleware('auth')->group(function () {
    // Logout Action
    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

    // Dashboard Home
    Route::get('/', [HomeController::class, 'index'])->name('home');
    Route::get('/dashboard', [HomeController::class, 'index']);

    // Module 1: Company CRUD
    Route::resource('companies', CompanyController::class);

    // Module 2: Dynamic Invoice Structure Templates
    Route::resource('templates', InvoiceStructureTemplateController::class);
    Route::get('/invoices/templates/{slug}', [InvoiceStructureTemplateController::class, 'renderTemplate'])->name('templates.preview');

    // Module 3: Recurring Service Scheduling Configurations (CRUD)
    Route::resource('services', RecurringServiceController::class);
    Route::get('/services/{service}/preview-next-invoice', [RecurringServiceController::class, 'previewNextInvoice'])->name('services.preview-next-invoice');

    // Module 4: Pre-emptive Manual Item Adjustments
    Route::post('/services/{service}/adjustments', [InvoiceController::class, 'storeAdjustment'])->name('services.adjustments.store');
    Route::delete('/adjustments/{line}', [InvoiceController::class, 'destroyAdjustment'])->name('services.adjustments.destroy');

    // Module 4: Invoices list, show & post-billing retroactive revision
    Route::get('/invoices', [InvoiceController::class, 'index'])->name('invoices.index');
    Route::get('/invoices/{invoice}', [InvoiceController::class, 'show'])->name('invoices.show');
    Route::post('/invoices/{invoice}/revise', [InvoiceController::class, 'revise'])->name('invoices.revise');

    // Activity Logs Viewer (Email Logs & Google Drive Uploads)
    Route::get('/logs', [HomeController::class, 'logs'])->name('logs.index');

    // Sandbox Trigger for Cron
    Route::post('/sandbox/process-cron', [HomeController::class, 'triggerCron'])->name('sandbox.cron');

    // System Settings: Currency Configurations
    Route::get('/settings', [HomeController::class, 'settings'])->name('settings');
    Route::post('/settings', [HomeController::class, 'updateSettings'])->name('settings.update');
    Route::post('/settings/profile', [HomeController::class, 'updateProfile'])->name('settings.profile.update');

    // Google Drive OAuth integration
    Route::get('/auth/google', [GoogleDriveController::class, 'redirect'])->name('auth.google');
    Route::get('/auth/google/callback', [GoogleDriveController::class, 'callback'])->name('auth.google.callback');
    Route::post('/auth/google/disconnect', [GoogleDriveController::class, 'disconnect'])->name('auth.google.disconnect');
});
