<?php

namespace App\Http\Controllers;

use App\Models\Company;
use App\Models\Invoice;
use App\Models\RecurringService;
use App\Models\EmailLog;
use App\Models\GoogleDriveLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;

class HomeController extends Controller
{
    public function index()
    {
        $companiesCount = Company::count();
        $activeSchedulesCount = RecurringService::where('status', 'active')->count();
        $totalInvoiced = Invoice::sum('total');
        
        $recentInvoices = Invoice::with(['company', 'recurringService'])
            ->latest()
            ->take(5)
            ->get();

        $activeServices = RecurringService::with(['company'])
            ->where('status', 'active')
            ->orderBy('next_billing_date')
            ->take(5)
            ->get();

        return view('dashboard', compact(
            'companiesCount',
            'activeSchedulesCount',
            'totalInvoiced',
            'recentInvoices',
            'activeServices'
        ));
    }

    public function logs()
    {
        $emailLogs = EmailLog::whereHas('invoice')
            ->with('invoice.company')
            ->latest()
            ->paginate(10, ['*'], 'emails');
            
        $driveLogs = GoogleDriveLog::whereHas('invoice')
            ->with('invoice.company')
            ->latest()
            ->paginate(10, ['*'], 'drive');

        return view('logs.index', compact('emailLogs', 'driveLogs'));
    }

    // Sandbox Simulation Trigger Action for Midnight Cron Execution
    public function triggerCron(Request $request)
    {
        $request->validate([
            'simulation_date' => ['required', 'date'],
        ]);

        $date = $request->input('simulation_date');

        // Execute programmatic artisan call
        $exitCode = Artisan::call('queuebill:process-invoices', [
            '--date' => $date
        ]);

        $output = Artisan::output();

        if ($exitCode === 0) {
            return redirect()->route('home')
                ->with('success', 'Sandbox Simulation executed successfully!')
                ->with('cron_output', $output);
        }

        return redirect()->route('home')
            ->with('error', 'Simulation failed with exit code: ' . $exitCode)
            ->with('cron_output', $output);
    }

    public function settings()
    {
        $user = auth()->user();
        return view('settings', compact('user'));
    }

    public function updateSettings(Request $request)
    {
        $validated = $request->validate([
            'currency' => ['required', 'string', 'max:10'],
        ]);

        $user = auth()->user();
        $user->update([
            'currency' => $validated['currency']
        ]);

        return redirect()->route('settings')->with('success', 'System settings updated successfully!');
    }

    public function updateProfile(Request $request)
    {
        $user = auth()->user();

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'password' => ['nullable', 'string', 'min:8', 'confirmed'],
        ]);

        $updateData = [
            'name' => $validated['name'],
        ];

        if (!empty($validated['password'])) {
            $updateData['password'] = \Illuminate\Support\Facades\Hash::make($validated['password']);
        }

        $user->update($updateData);

        return redirect()->route('settings')->with('success', 'Profile and password updated successfully!');
    }
}
