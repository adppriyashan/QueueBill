# QueueBill

> **"Your Recurring Revenue, Perfectly Aligned."**

QueueBill is a B2B subscription scheduling and automated billing engine built on Laravel. It empowers enterprise software systems, cloud providers, and SaaS lines of business to configure customer directories, construct branded dynamic invoice templates, run hands-free periodic cycle rollups, and manage ad-hoc adjustments cleanly.

---

## ✨ Features & Design Highlights

*   **🛡️ Administrative Authentication**: Custom center-screen glassmorphic login and registration panels.
*   **📂 Company Profile Directory (CRUD)**: Manage buyer credentials with strict international phone formatting checks (`+1 (555) 000-0000`) and a comprehensive **Historical Billing Profile** showing cumulative invoiced revenue, active billing schedules, and ledger histories.
*   **🎨 Dynamic Invoice Structure Templates**: Configure distinct layout brand identities (SaaS hosting templates, consultancy invoices, marketing templates) with fallback sender emails and real-time JavaScript alphanumeric URL slug auto-generation served dynamically at `/invoices/templates/{slug}`.
*   **📅 Recurring Service Scheduling (CRUD)**: Set calendar timeline boundaries, custom scope text lists (which dynamically parse line-by-line into independent $0.00 items on the statement breakdown), base pricing intervals, and target **Google Drive Upload Folders** registered on creation.
*   **⚡ Scenario A: Pre-emptive Manual Injections**: Inject manual credit or fee lines prior to cycle executions. These are stored in a queue and automatically integrated into the statement breakdown when the automated cycle scheduler runs.
*   **🔄 Scenario B: Retroactive Adjustments & Version Control**: Perform post-billing statement revisions. Inject retroactive charges on an issued invoice to automatically increment document versions (`v1 ➔ v2 ➔ v3`), recalculate subtotals, append a "REVISED STATEMENT" badge, log simulated dispatch emails, and upload simulated revision PDFs to the registered Google Drive path.
*   **⚙️ Midnight Cron Billing Runner**: Robust console Artisan task processing active schedules, advancing calendars, rolls up subtotals, logs simulated dispatches in `email_logs`, and logs simulated Google Drive file uploads in `google_drive_logs`.
*   **📊 Console Cockpit Dashboard**: View high-level metrics, active schedules, generated ledger statements, and run manual sandbox tick billing runs from a single unified workspace.

---

## 🛠️ Technical Specifications

Detailed design architecture and requirements are fully specified in the `docs/` folder:
*   [docs/requirements.md](docs/requirements.md) — Detailed feature bounds and scenarios.
*   [docs/structure.md](docs/structure.md) — Model schemas, traits, relationships, and directory tree maps.

---

## 🚀 Quick Start Guide

Verify or boot the platform environment on your system using these steps:

### 1. Requirements & Prerequisites
*   PHP 8.2+
*   Composer
*   SQLite3

### 2. Configure Local Database
Clone the repository, copy environment parameters, and run pristine database migrations and seeders:

```bash
# Install package dependencies
composer install

# Copy configuration
cp .env.example .env

# Create empty SQLite database file
touch database/database.sqlite

# Run database migrations and seed default administrative/testing profiles
php artisan migrate:fresh --seed
```

### 3. Launch Development Server
```bash
# Start local PHP dev server
php artisan serve --port=8000
```
Open [http://127.0.0.1:8000](http://127.0.0.1:8000) in your web browser.

### 4. Admin Sign-In Credentials
*   **Email**: `admin@queuebill.com`
*   **Password**: `password`

---

## 🕹️ Interactive Simulation Sandbox

To perform cycle billing simulation runs:
1.  Sign in to the administrative portal.
2.  Navigate to the **Dashboard** ("Administrative Console").
3.  Fill in the **Simulation Sandbox** form on the top right:
    *   Set simulation date to `2026-05-18` (which is already populated).
    *   Click **Tick Midnight Cron Run**.
4.  Observe the processed outputs and generated statements directly in the logs and invoice lists!
