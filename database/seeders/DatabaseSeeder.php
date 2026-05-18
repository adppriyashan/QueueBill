<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Company;
use App\Models\InvoiceStructureTemplate;
use App\Models\RecurringService;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Create Administrative Console User
        $admin = User::create([
            'name' => 'Administrator',
            'email' => 'admin@queuebill.com',
            'password' => Hash::make('password'),
        ]);

        // Login the user in console command contexts so HasUserstamps has user context
        auth()->login($admin);

        // 2. Create Sample Buyer Companies
        $acme = Company::create([
            'name' => 'Acme Corporation',
            'email' => 'billing@acme.com',
            'phone' => '+1 (555) 019-2834',
            'address' => "123 Industrial Way, Suite 400\nAustin, TX 78701",
            'status' => 'active'
        ]);

        $cyberdyne = Company::create([
            'name' => 'CyberDyne Systems',
            'email' => 'finance@cyberdyne.co',
            'phone' => '+1 (555) 998-1002',
            'address' => "456 Mainframe Boulevard\nSilicon Valley, CA 94025",
            'status' => 'active'
        ]);

        // 3. Create Branded Layout Structure Templates
        $hostingTemplate = InvoiceStructureTemplate::create([
            'title' => 'Cloud Hosting Enterprise Template',
            'slug' => 'cloud-hosting-enterprise-template',
            'sender_email' => 'cloud-billing@cyberdyne.co',
            'status' => 'active'
        ]);

        $consultingTemplate = InvoiceStructureTemplate::create([
            'title' => 'Corporate Consulting Layout Theme',
            'slug' => 'corporate-consulting-layout-theme',
            'sender_email' => 'partners@acme.com',
            'status' => 'active'
        ]);

        // 4. Create Active Billing Schedules
        RecurringService::create([
            'company_id' => $acme->id,
            'invoice_structure_template_id' => $hostingTemplate->id,
            'name' => 'SaaS Cloud Hosting Enterprise Suite',
            'from_date' => '2026-05-18', // Today
            'to_date' => '2027-05-17', // One Year Contract
            'recurring_cadence' => 'month',
            'base_cost' => 299.00,
            'invoice_includes' => "Unlimited High-Availability SSD Storage\n10 Dedicated Load Balancing Nodes\n24/7 Premium Technical SLA Support",
            'google_drive_path' => '/QueueBill/Drive/AcmeCorp',
            'next_billing_date' => '2026-05-18', // Ready to process today!
            'status' => 'active'
        ]);

        RecurringService::create([
            'company_id' => $cyberdyne->id,
            'invoice_structure_template_id' => $consultingTemplate->id,
            'name' => 'Strategic AI Advisory Consulting retainer',
            'from_date' => '2026-05-18', // Today
            'to_date' => '2026-11-17', // Six Months Contract
            'recurring_cadence' => '6_months',
            'base_cost' => 4500.00,
            'invoice_includes' => "Strategic Neural Net Architectural Advisory\nT-800 CPU Logic Calibration Protocol\nQuantum Coherence Threat Analysis Workshops",
            'google_drive_path' => '/QueueBill/Drive/CyberDyne',
            'next_billing_date' => '2026-05-18', // Ready to process today!
            'status' => 'active'
        ]);
    }
}
