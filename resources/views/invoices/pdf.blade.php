<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Invoice Statement #{{ $invoice->invoice_number }}</title>
    <style>
        body {
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            color: #1e293b;
            margin: 0;
            padding: 0;
            font-size: 13px;
            line-height: 1.5;
            background-color: #f8fafc;
        }
        .invoice-container {
            max-width: 850px;
            margin: 0 auto;
            padding: 20px;
        }

        /* 1. Premium Invoice Sheet Card styling */
        .invoice-sheet {
            background: #ffffff;
            border-radius: 20px;
            overflow: hidden;
            border: 1px solid rgba(0, 0, 0, 0.05);
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.04);
        }

        /* Dynamic Header Styling based on Template Title */
        @php
            $templateTitle = $invoice->recurringService?->invoiceStructureTemplate?->title ?? '';
            $isHosting = str_contains(strtolower($templateTitle), 'hosting') || str_contains(strtolower($templateTitle), 'cloud');
            $isCorp = str_contains(strtolower($templateTitle), 'corporate') || str_contains(strtolower($templateTitle), 'enterprise');
            
            if ($isHosting) {
                $primaryColor = '#06b6d4';
                $gradientStart = '#06b6d4';
                $gradientEnd = '#3b82f6';
            } elseif ($isCorp) {
                $primaryColor = '#1e293b';
                $gradientStart = '#1e293b';
                $gradientEnd = '#475569';
            } else {
                $primaryColor = '#4f46e5';
                $gradientStart = '#4f46e5';
                $gradientEnd = '#7c3aed';
            }
        @endphp

        /* Branded Header bar customized dynamically by Template rules */
        .sheet-header-bar {
            background: {{ $primaryColor }};
            color: #ffffff;
            padding: 35px;
        }
        .sheet-header-table {
            width: 100%;
            border-collapse: collapse;
        }
        .sheet-header-table td {
            vertical-align: middle;
            border: none;
            padding: 0;
        }
        .sheet-header-bar h2 {
            font-size: 24px;
            font-weight: 700;
            margin: 0 0 5px 0;
            letter-spacing: -0.5px;
            color: #ffffff;
        }
        .sheet-header-bar h3 {
            font-size: 22px;
            font-weight: 700;
            margin: 0;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            color: #ffffff;
        }
        .sheet-badge {
            display: inline-block;
            background: rgba(255, 255, 255, 0.2);
            color: #ffffff;
            padding: 4px 12px;
            border-radius: 50rem;
            font-weight: 600;
            font-size: 11px;
            margin-bottom: 8px;
            text-transform: uppercase;
        }
        .sheet-invoice-number {
            font-size: 13px;
            opacity: 0.85;
            color: #ffffff;
        }

        /* Sheet Body */
        .sheet-body {
            padding: 35px;
        }

        /* Metadata Details Layout */
        .metadata-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 40px;
        }
        .metadata-table td {
            width: 33.33%;
            vertical-align: top;
            padding: 0 10px;
            border: none;
        }
        .metadata-table td:first-child {
            padding-left: 0;
        }
        .metadata-table td:last-child {
            padding-right: 0;
        }
        .meta-heading {
            color: #64748b;
            font-weight: 700;
            text-transform: uppercase;
            font-size: 10px;
            letter-spacing: 0.05em;
            margin-bottom: 8px;
            border-bottom: 1px solid #e2e8f0;
            padding-bottom: 4px;
        }
        .meta-content {
            color: #334155;
            font-size: 12px;
        }
        .meta-content strong {
            color: #0f172a;
            display: block;
            margin-bottom: 2px;
        }

        /* Items Table Styling */
        .table-sheet-items {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 30px;
        }
        .table-sheet-items th {
            text-transform: uppercase;
            font-size: 10px;
            letter-spacing: 0.05em;
            font-weight: 700;
            color: #64748b;
            border-bottom: 2px solid #e2e8f0;
            padding: 10px;
            text-align: left;
            background-color: #f8fafc;
        }
        .table-sheet-items td {
            padding: 14px 10px;
            border-bottom: 1px solid #f1f5f9;
            vertical-align: top;
        }
        .fw-semibold {
            font-weight: 600;
            color: #0f172a;
            font-size: 13px;
        }
        .text-secondary {
            color: #64748b;
            font-size: 11px;
            margin-top: 3px;
        }
        .scope-list {
            margin: 4px 0 0 0;
            padding-left: 15px;
            color: #64748b;
            font-size: 11px;
        }
        .badge {
            display: inline-block;
            font-size: 9px;
            font-weight: 600;
            padding: 2px 6px;
            border-radius: 4px;
            margin-top: 4px;
        }
        .bg-warning-soft {
            background-color: #fef3c7;
            color: #d97706;
        }
        .bg-light {
            background-color: #f1f5f9;
            color: #475569;
        }

        /* Totals Block Layout */
        .totals-container {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        .totals-container td {
            border: none;
            padding: 0;
        }
        .totals-block {
            width: 280px;
            float: right;
        }
        .total-row-table {
            width: 100%;
            border-collapse: collapse;
        }
        .total-row-table td {
            padding: 8px 0;
            font-size: 13px;
        }
        .total-label {
            color: #64748b;
            font-weight: 600;
            text-align: left;
        }
        .total-value {
            font-weight: 700;
            color: #0f172a;
            text-align: right;
        }
        .grand-total-row td {
            border-top: 2px solid #e2e8f0;
            padding-top: 12px;
        }
        .grand-total-label {
            font-size: 14px;
            font-weight: 700;
            color: #0f172a;
        }
        .grand-total-value {
            font-size: 18px;
            font-weight: 700;
            color: {{ $primaryColor }};
        }

        /* Clearfix for float layout */
        .clearfix::after {
            content: "";
            clear: both;
            display: table;
        }

        /* Footer styling */
        .invoice-footer {
            margin-top: 80px;
            border-top: 1px solid #e2e8f0;
            padding-top: 15px;
            text-align: center;
            color: #94a3b8;
            font-size: 11px;
        }
        .text-right {
            text-align: right !important;
        }
    </style>
</head>
<body>

<div class="invoice-container">
    <div class="invoice-sheet">
        <!-- Sheet Header -->
        <div class="sheet-header-bar">
            <table class="sheet-header-table">
                <tr>
                    <td style="width: 25%;">
                        <img src="https://queuebill.com/assets/brand/icon-800.png" alt="Logo" style="width: 150px;">
                    </td>
                    <td>
                        <h2>{{ $invoice->creator?->company_name ?? env('APP_NAME', 'QueueBill') }}</h2>
                        <span class="fs-8">{{ $invoice->creator?->company_address ?? '' }}</span>
                        <span class="fs-8">{{ $invoice->creator?->company_email ?? '' }}</span>
                        <span class="fs-8">{{ $invoice->creator?->company_phone ?? '' }}</span>
                    </td>
                    <td style="text-align: right;">
                        <h3>INVOICE @if($invoice->version > 1) (Revised) @endif</h3>
                        <span class="sheet-invoice-number">#{{ $invoice->invoice_number }}</span>
                    </td>
                </tr>
            </table>
        </div>

        <!-- Sheet Body -->
        <div class="sheet-body">
            <!-- Meta Rows -->
            <table class="metadata-table">
                <tr>
                    <!-- Billed From -->
                    <td>
                        <div class="meta-heading">Billed From</div>
                        <div class="meta-content">
                            <strong>{{ $invoice->creator?->company_name ?? env('APP_NAME', 'QueueBill') }}</strong>
                            {!! nl2br(e($invoice->creator?->company_address ?? "100 Revenue Way, Suite A\nAustin, TX 78701")) !!}
                            
                            <!-- Custom Fallback Email overridden via template properties -->
                            <div style="margin-top: 6px; color: {{ $primaryColor }}; font-weight: 500;">
                                @php
                                    $senderEmail = $invoice->recurringService?->invoiceStructureTemplate?->sender_email;
                                @endphp
                                @if($senderEmail)
                                    {{ $senderEmail }}
                                @else
                                    hello@queuebill.com
                                @endif
                            </div>
                        </div>
                    </td>
                    
                    <!-- Billed To -->
                    <td>
                        <div class="meta-heading">Billed To</div>
                        <div class="meta-content">
                            <strong>{{ $invoice->company->name }}</strong>
                            {{ $invoice->company->address ?? '—' }}
                            <div style="margin-top: 6px; color: {{ $primaryColor }}; font-weight: 500;">
                                {{ $invoice->company->email }}
                            </div>
                        </div>
                    </td>
                    
                    <!-- Cycle Timeline -->
                    <td style="text-align: right;">
                        <div class="meta-heading" style="text-align: right;">Cycle Timeline</div>
                        <div class="meta-content">
                            <div style="margin-bottom: 2px;"><strong>Issue Date:</strong> {{ $invoice->issue_date->format('M d, Y') }}</div>
                            <div style="margin-bottom: 2px;"><strong>Due Date:</strong> {{ $invoice->due_date->format('M d, Y') }}</div>
                        </div>
                    </td>
                </tr>
            </table>

            <!-- Line Items Table -->
            <table class="table-sheet-items">
                <thead>
                    <tr>
                        <th style="width: 60%">Item Description</th>
                        <th style="width: 15%; text-align: center;">Qty</th>
                        <th style="width: 25%;" class="text-right">Amount</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $baseItem = $invoice->invoiceItems->first(function($item) {
                            return !$item->is_adhoc && str_contains(strtolower($item->description), 'base subscription');
                        });
                        if (!$baseItem) {
                            $baseItem = $invoice->invoiceItems->first(function($item) {
                                return !$item->is_adhoc && $item->amount > 0;
                            });
                        }
                        
                        $scopeItems = $invoice->invoiceItems->filter(function($item) use ($baseItem) {
                            return !$item->is_adhoc && $item->amount == 0 && ($baseItem ? $item->id !== $baseItem->id : true);
                        });
                        
                        $otherItems = $invoice->invoiceItems->filter(function($item) use ($baseItem, $scopeItems) {
                            $excludeIds = [];
                            if ($baseItem) $excludeIds[] = $baseItem->id;
                            foreach ($scopeItems as $si) $excludeIds[] = $si->id;
                            return !in_array($item->id, $excludeIds);
                        });
                    @endphp

                    @if($baseItem)
                        <tr>
                            <td>
                                <div class="fw-semibold">{{ $baseItem->description }}</div>
                                @if($scopeItems->isNotEmpty())
                                    <ul class="scope-list">
                                        @foreach($scopeItems as $scope)
                                            <li>{{ $scope->description }}</li>
                                        @endforeach
                                    </ul>
                                @endif
                            </td>
                            <td style="text-align: center; color: #64748b;">1</td>
                            <td class="text-right" style="font-weight: 700;">
                                {{ format_currency($baseItem->amount, $invoice->created_by) }}
                            </td>
                        </tr>
                    @endif

                    @foreach($otherItems as $item)
                        <tr>
                            <td>
                                <div class="fw-semibold">{{ $item->description }}</div>
                                @if($item->is_adhoc)
                                    <span class="badge bg-warning-soft">Ad-Hoc Line Injection</span>
                                @else
                                    <span class="badge bg-light">Additional Contract Item</span>
                                @endif
                            </td>
                            <td style="text-align: center; color: #64748b;">1</td>
                            <td class="text-right" style="font-weight: 700;">
                                @if($item->amount < 0)
                                    <span style="color: #10b981;">-{{ format_currency(abs($item->amount), $invoice->created_by) }}</span>
                                @else
                                    {{ format_currency($item->amount, $invoice->created_by) }}
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            <!-- Totals row -->
            <table style="width: 100%; border-collapse: collapse; margin-top: 20px;">
                <tr>
                    <td style="width: 60%; border: none;"></td>
                    <td style="width: 40%; border: none; vertical-align: top;">
                        <table class="total-row-table">
                            <tr>
                                <td class="total-label">Subtotal:</td>
                                <td class="total-value text-right" style="padding-right: 10px;">{{ format_currency($invoice->subtotal, $invoice->created_by) }}</td>
                            </tr>
                            <tr class="grand-total-row">
                                <td class="grand-total-label">Total Amount Due:</td>
                                <td class="grand-total-value text-right" style="padding-right: 10px;">{{ format_currency($invoice->total, $invoice->created_by) }}</td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </table>
        </div>
    </div>

    <!-- Elegant Branded Footer -->
    <div class="invoice-footer">
        <div style="font-weight: bold; color: #64748b; margin-bottom: 4px;">QueueBill Automated Billing Service</div>
        <div>"Your Recurring Revenue, Perfectly Aligned."</div>
    </div>
</div>

</body>
</html>
