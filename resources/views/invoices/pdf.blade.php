<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Invoice #{{ $invoice->invoice_number }}</title>

    <style>
        /* =========================
           FONT
        ========================== */

        /*
        Browser Preview:
        Uncomment below if needed.

        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap');
        */

        @page {
            margin: 0;
        }

        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            /* Added a 140px bottom padding to act as a safety buffer so content never overlaps the footer */
            padding: 42px 42px 140px 42px;
            background: #f3f4f6;
            color: #111827;
            font-family: "Poppins", "Helvetica Neue", Arial, sans-serif;
            font-size: 13px;
            line-height: 1.6;
            min-height: 100%;
        }

        .invoice-wrapper {
            max-width: 100%;
            margin: 0 auto;
        }

        .page-container {
            position: relative;
        }

        .invoice-content {
            padding-bottom: 20px;
        }

        /* =========================
           MAIN CARD
        ========================== */

        .invoice-card {
            background: #ffffff;
            border: 1px solid #eef2f7;
            border-radius: 28px;
            overflow: hidden;
            box-shadow:
                0 12px 35px rgba(15, 23, 42, 0.03),
                0 2px 10px rgba(15, 23, 42, 0.02);
        }

        /* =========================
           HEADER
        ========================== */

        .header {
            padding: 44px 50px 5px;
            background:
                radial-gradient(circle at top left,
                    rgba(99, 102, 241, 0.06),
                    transparent 35%),
                #ffffff;

            border-bottom: 1px solid #f5f7fa;
        }

        .header-table {
            width: 100%;
            border-collapse: collapse;
        }

        .header-table td {
            vertical-align: top;
        }

        /* LEFT SIDE */

        .brand-column {
            width: 50%;
        }

        .logo {
            max-width: 260px;
            max-height: 80px;
            object-fit: contain;
        }

        .company-name {
            margin-top: 16px;
            font-size: 16px;
            font-weight: 600;
            color: #111827;
            letter-spacing: -0.01em;
        }

        .company-meta {
            margin-top: 2px;
            color: #111827;
            font-size: 10px;
            line-height: 1.8;
        }

        /* RIGHT SIDE */

        .invoice-column {
            text-align: right;
        }

        .invoice-label {
            font-size: 64px;
            font-weight: 200;
            letter-spacing: 10px;
            margin: 0;
            color: #111827;
            line-height: 0.95;
        }

        .invoice-number {
            margin-top: 16px;
            font-size: 15px;
            letter-spacing: 2px;
            color: #6b7280;
            font-weight: 500;
        }

        .revision-badge {
            display: inline-block;
            margin-top: 16px;
            padding: 6px 14px;
            border-radius: 999px;
            background: #fef3c7;
            color: #92400e;
            font-size: 11px;
            font-weight: 600;
            letter-spacing: 0.04em;
        }

        /* =========================
           BODY
        ========================== */

        .body {
            padding: 10px 50px 52px;
        }

        /* =========================
           INFO GRID
        ========================== */

        .info-grid {
            width: 100%;
            margin-bottom: 10px;
            overflow: hidden;
        }

        .info-card {
            border: 1px solid #f1f5f9;
            background: #fcfcfd;
            border-radius: 22px;
            padding: 24px 28px;
        }

        /* LEFT CARD */

        .info-card-left {
            width: 58%;
            float: left;
            margin-right: 2%;
        }

        /* RIGHT CARD */

        .info-card-right {
            width: 40%;
            float: right;
        }

        /* Typography */

        .info-label {
            font-size: 11px;
            text-transform: uppercase;
            letter-spacing: 0.14em;
            color: #9ca3af;
            margin-bottom: 12px;
            font-weight: 600;
        }

        .info-title {
            font-size: 12px;
            font-weight: 600;
            color: #111827;
            margin-bottom: 5px;
            letter-spacing: -0.02em;
        }

        .info-content {
            color: #6b7280;
            line-height: 1.8;
            font-size: 12px;
        }

        /* Meta rows */

        .meta-row {
            margin-bottom: 1px;
        }

        .meta-row:last-child {
            margin-bottom: 0;
        }

        .meta-key {
            width: 95px;
            display: inline-block;
            color: #9ca3af;
            font-weight: 400;
            font-size: 12px;
        }

        .meta-value {
            color: #111827;
            font-weight: 500;
            font-size: 12px;
        }

        /* =========================
           ITEMS TABLE
        ========================== */

        .items-wrapper {
            border: 1px solid #f1f5f9;
            border-radius: 24px;
            overflow: hidden;
            background: #ffffff;
        }

        .items-table {
            width: 100%;
            border-collapse: collapse;
        }

        .items-table thead {
            background: #fafbfc;
        }

        .items-table thead th {
            text-align: left;
            padding: 18px 24px;
            font-size: 12px;
            text-transform: uppercase;
            letter-spacing: 0.14em;
            color: #6b7280;
            font-weight: 600;
            border-bottom: 1px solid #f1f5f9;
        }

        .items-table tbody td {
            padding: 22px 24px;
            border-bottom: 1px solid #f8fafc;
            vertical-align: top;
            font-size: 12px;
            color: #4b5563;
        }

        .items-table tbody tr:last-child td {
            border-bottom: none;
        }

        .item-title {
            color: #111827;
            font-weight: 500;
            margin-bottom: 6px;
            font-size: 12px;
        }

        .scope-list {
            margin: 8px 0 0 18px;
            padding: 0;
            color: #6b7280;
            font-size: 12px;
        }

        .scope-list li {
            margin-bottom: 4px;
        }

        .qty {
            text-align: center;
            color: #6b7280;
            font-weight: 500;
        }

        .amount {
            text-align: right;
            color: #111827;
            font-weight: 600;
        }

        .negative {
            color: #059669;
        }

        /* =========================
           TOTALS
        ========================== */

        .totals-section {
            margin-top: 10px;
        }

        .totals-card {
            width: 360px;
            margin-left: auto;
            border: 1px solid #f1f5f9;
            border-radius: 24px;
            overflow: hidden;
            background: #ffffff;
        }

        .totals-table {
            width: 100%;
            border-collapse: collapse;
        }

        .totals-table td {
            padding: 15px 24px;
            font-size: 14px;
        }

        .totals-label {
            color: #6b7280;
            font-weight: 500;
        }

        .totals-value {
            text-align: right;
            color: #111827;
            font-weight: 600;
        }

        .grand-total td {
            color: #6b7280;
            /* Adjusted color to make white text on a purple gradient background visible */
        }

        .grand-total-label {
            font-size: 15px;
            font-weight: 600;
            letter-spacing: 0.03em;
        }

        .grand-total-value {
            text-align: right;
            font-size: 24px;
            font-weight: 700;
        }

        /* =========================
           NOTES CARD
        ========================== */

        .notes-card {
            width: 200px;
            float: left;
            padding: 24px 28px;
        }

        .notes-label {
            font-size: 11px;
            text-transform: uppercase;
            letter-spacing: 0.14em;
            color: #9ca3af;
            margin-bottom: 14px;
            font-weight: 600;
        }

        .notes-content {
            color: #6b7280;
            font-size: 10px;
            line-height: 1.9;
        }

        /* =========================
            FOOTER
        ========================== */

        .footer {
            position: fixed;
            left: 42px;
            /* Coordinates align safely with body paddings */
            right: 42px;
            bottom: 42px;
            text-align: center;
            color: #9ca3af;
            font-size: 12px;
            line-height: 1.8;
            border-top: 1px solid #f1f5f9;
            padding-top: 18px;
        }

        .footer-brand {
            font-weight: 600;
            color: #6b7280;
            margin-bottom: 6px;
            letter-spacing: -0.01em;
        }

        /* =========================
           CLEARFIX
        ========================== */

        .clearfix::after {
            content: "";
            display: block;
            clear: both;
        }
    </style>
</head>

<body>

    <div class="page-container">

        <div class="invoice-wrapper">

            <div class="invoice-content">

                <div class="invoice-card">

                    <!-- =========================
                         HEADER
                    ========================== -->

                    <div class="header">

                        <table class="header-table">

                            <tr>

                                <!-- LEFT -->
                                <td class="brand-column">

                                    @if(
                                            $invoice->creator?->company_logo &&
                                            file_exists(public_path($invoice->creator?->company_logo))
                                        )

                                        <img src="{{ public_path($invoice->creator?->company_logo) }}" class="logo"
                                            alt="Logo">

                                    @endif

                                    <div class="company-name">
                                        {{ $invoice->creator?->name ?? env('APP_NAME', 'QueueBill') }}
                                    </div>

                                    <div class="company-meta">

                                        @if ($invoice->creator?->company_address)
                                            <div>
                                                {{ $invoice->creator?->company_address }}
                                            </div>
                                        @endif

                                        @php
                                            $senderEmail =
                                                $invoice->recurringService?->invoiceStructureTemplate?->sender_email;
                                        @endphp

                                        <div>
                                            {{ $senderEmail ?? $invoice->creator?->email ?? 'No Email Provided' }}
                                        </div>

                                    </div>

                                </td>

                                <!-- RIGHT -->
                                <td class="invoice-column">

                                    <h1 class="invoice-label">
                                        {{ $invoice->version > 1 ? 'REVISED' : 'INVOICE' }}
                                    </h1>

                                    <div class="invoice-number">
                                        #{{ $invoice->invoice_number }}
                                    </div>
                                </td>

                            </tr>

                        </table>

                    </div>

                    <!-- =========================
                         BODY
                    ========================== -->

                    <div class="body">

                        <!-- INFO GRID -->

                        <div class="info-grid clearfix">

                            <!-- BILL TO -->

                            <div class="info-card info-card-left">

                                <div class="info-label">
                                    Billed To
                                </div>

                                <div class="info-title">
                                    {{ $invoice->company->name }}
                                </div>

                                <div class="info-content">

                                    <div>
                                        {{ $invoice->company->address ?? '—' }}
                                    </div>

                                    <div>
                                        {{ $invoice->company->email }}
                                    </div>

                                </div>

                            </div>

                            <!-- INVOICE DETAILS -->

                            <div class="info-card info-card-right">

                                <div class="info-label">
                                    Invoice Details
                                </div>

                                <div class="meta-row">

                                    <span class="meta-key">
                                        Issue Date
                                    </span>

                                    <span class="meta-value">
                                        {{ $invoice->issue_date->format('M d, Y') }}
                                    </span>

                                </div>

                                <div class="meta-row">

                                    <span class="meta-key">
                                        Due Date
                                    </span>

                                    <span class="meta-value">
                                        {{ $invoice->due_date->format('M d, Y') }}
                                    </span>

                                </div>

                                <div class="meta-row">

                                    <span class="meta-key">
                                        Status
                                    </span>

                                    <span class="meta-value">
                                        Pending Payment
                                    </span>

                                </div>

                            </div>

                        </div>

                        <!-- =========================
                             ITEMS TABLE
                        ========================== -->

                        <div class="items-wrapper">

                            <table class="items-table">

                                <thead>

                                    <tr>

                                        <th style="width:60%">
                                            Description
                                        </th>

                                        <th style="width:10%; text-align:center;">
                                            Qty
                                        </th>

                                        <th style="width:30%; text-align:right;">
                                            Amount
                                        </th>

                                    </tr>

                                </thead>

                                <tbody>

                                    @php
                                        $baseItem = $invoice->invoiceItems->first(function ($item) {
                                            return !$item->is_adhoc &&
                                                str_contains(strtolower($item->description), 'base subscription');
                                        });

                                        if (!$baseItem) {
                                            $baseItem = $invoice->invoiceItems->first(function ($item) {
                                                return !$item->is_adhoc && $item->amount > 0;
                                            });
                                        }

                                        $scopeItems = $invoice->invoiceItems->filter(function ($item) use ($baseItem) {
                                            return !$item->is_adhoc &&
                                                $item->amount == 0 &&
                                                ($baseItem ? $item->id !== $baseItem->id : true);
                                        });

                                        $otherItems = $invoice->invoiceItems->filter(function ($item) use ($baseItem, $scopeItems) {

                                            $excludeIds = [];

                                            if ($baseItem)
                                                $excludeIds[] = $baseItem->id;

                                            foreach ($scopeItems as $si)
                                                $excludeIds[] = $si->id;

                                            return !in_array($item->id, $excludeIds);
                                        });
                                    @endphp

                                    <!-- BASE ITEM -->

                                    @if($baseItem)

                                        <tr>

                                            <td>

                                                <div class="item-title">
                                                    {{ $baseItem->description }}
                                                </div>

                                                @if($scopeItems->isNotEmpty())

                                                    <ul class="scope-list">

                                                        @foreach($scopeItems as $scope)
                                                            <li>{{ $scope->description }}</li>
                                                        @endforeach

                                                    </ul>

                                                @endif

                                            </td>

                                            <td class="qty">
                                                1
                                            </td>

                                            <td class="amount">
                                                {{ format_currency($baseItem->amount, $invoice->created_by) }}
                                            </td>

                                        </tr>

                                    @endif

                                    <!-- OTHER ITEMS -->

                                    @foreach($otherItems as $item)

                                        <tr>

                                            <td>

                                                <div class="item-title">
                                                    {{ $item->description }}
                                                </div>

                                            </td>

                                            <td class="qty">
                                                1
                                            </td>

                                            <td class="amount">

                                                @if($item->amount < 0)

                                                    <span class="negative">
                                                        -{{ format_currency(abs($item->amount), $invoice->created_by) }}
                                                    </span>

                                                @else

                                                    {{ format_currency($item->amount, $invoice->created_by) }}

                                                @endif

                                            </td>

                                        </tr>

                                    @endforeach

                                </tbody>

                            </table>

                        </div>

                        <!-- =========================
                             TOTALS
                        ========================== -->

                        <div class="totals-section clearfix">

                            @if($invoice->recurringService && $invoice->recurringService->note)
                                <div class="notes-card">

                                    <div class="notes-label">
                                        Notes
                                    </div>

                                    <div class="notes-content">
                                        {!! nl2br(e($invoice->recurringService->note)) !!}
                                    </div>

                                </div>
                            @endif

                            <div class="totals-card">

                                <table class="totals-table">

                                    <tr>

                                        <td class="totals-label">
                                            Sub Total
                                        </td>

                                        <td class="totals-value">
                                            {{ format_currency($invoice->subtotal, $invoice->created_by) }}
                                        </td>

                                    </tr>

                                    <tr>

                                        <td class="totals-label">
                                            Discount
                                        </td>

                                        <td class="totals-value">
                                            {{ format_currency(0, $invoice->created_by) }}
                                        </td>

                                    </tr>

                                    <tr class="grand-total">

                                        <td class="grand-total-label">
                                            Total
                                        </td>

                                        <td class="grand-total-value">
                                            {{ format_currency($invoice->total, $invoice->created_by) }}
                                        </td>

                                    </tr>

                                </table>

                            </div>

                        </div>

                    </div> <!-- Close body -->

                </div> <!-- Close invoice-card -->

            </div> <!-- Close invoice-content -->

        </div> <!-- Close invoice-wrapper -->

    </div> <!-- Close page-container -->

    <!-- =========================
         FOOTER (Using Fixed Placement for DOMPDF Context)
    ========================== -->
    <div class="footer">

        <div class="footer-brand">
            QueueBill Automated Billing Service
        </div>

        <div>
            Thank you for your business.
        </div>

    </div>

</body>

</html>