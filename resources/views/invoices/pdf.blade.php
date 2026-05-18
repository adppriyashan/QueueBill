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
            background-color: #ffffff;
        }
        .invoice-container {
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
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

        .header-bar {
            background-color: {{ $primaryColor }};
            color: #ffffff;
            padding: 30px;
            border-radius: 12px;
            margin-bottom: 30px;
        }
        .header-table {
            width: 100%;
            border-collapse: collapse;
        }
        .header-table td {
            vertical-align: middle;
            border: none;
            padding: 0;
        }
        .brand-name {
            font-size: 22px;
            font-weight: bold;
            margin: 0;
            letter-spacing: -0.5px;
        }
        .simulated-badge {
            display: inline-block;
            background-color: rgba(255, 255, 255, 0.2);
            color: #ffffff;
            padding: 3px 8px;
            border-radius: 4px;
            font-size: 10px;
            font-weight: bold;
            text-transform: uppercase;
            margin-top: 5px;
        }
        .invoice-title-col {
            text-align: right;
        }
        .invoice-title {
            font-size: 22px;
            font-weight: bold;
            margin: 0;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        .invoice-subtitle {
            font-size: 13px;
            opacity: 0.9;
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
            font-weight: bold;
            text-transform: uppercase;
            font-size: 10px;
            letter-spacing: 0.5px;
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
        .items-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 30px;
        }
        .items-table th {
            background-color: #f8fafc;
            color: #475569;
            font-weight: bold;
            text-transform: uppercase;
            font-size: 10px;
            letter-spacing: 0.5px;
            padding: 10px;
            text-align: left;
            border-bottom: 2px solid #e2e8f0;
        }
        .items-table td {
            padding: 12px 10px;
            border-bottom: 1px solid #f1f5f9;
            vertical-align: top;
        }
        .item-desc-title {
            font-weight: bold;
            color: #0f172a;
            font-size: 13px;
        }
        .item-desc-sub {
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
        .adhoc-badge {
            display: inline-block;
            background-color: #fef3c7;
            color: #d97706;
            font-size: 9px;
            font-weight: bold;
            padding: 1px 6px;
            border-radius: 4px;
            margin-top: 4px;
        }
        .contract-badge {
            display: inline-block;
            background-color: #f1f5f9;
            color: #475569;
            font-size: 9px;
            font-weight: bold;
            padding: 1px 6px;
            border-radius: 4px;
            margin-top: 4px;
        }

        /* Totals Block Layout */
        .totals-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        .totals-table td {
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
            text-align: left;
        }
        .total-value {
            font-weight: bold;
            color: #0f172a;
            text-align: right;
        }
        .grand-total-row td {
            border-top: 2px solid #e2e8f0;
            padding-top: 12px;
        }
        .grand-total-label {
            font-size: 14px;
            font-weight: bold;
            color: #0f172a;
        }
        .grand-total-value {
            font-size: 18px;
            font-weight: bold;
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
    </style>
</head>
<body>

<div class="invoice-container">
    <!-- 1. Header Branded Banner -->
    <div class="header-bar">
        <table class="header-table">
            <tr>
                <td>
                    <h1 class="brand-name">{{ $invoice->creator?->company_name ?? 'QueueBill Automation System' }}</h1>
                    <span class="simulated-badge">Verified Statement</span>
                </td>
                <td class="invoice-title-col">
                    <h2 class="invoice-title">Invoice @if($invoice->version > 1) (Revised) @endif</h2>
                    <span class="invoice-subtitle">#{{ $invoice->invoice_number }}</span>
                </td>
            </tr>
        </table>
    </div>

    <!-- 2. Metadata Columns -->
    <table class="metadata-table">
        <tr>
            <!-- Billed From -->
            <td>
                <div class="meta-heading">Billed From</div>
                <div class="meta-content">
                    <strong>{{ $invoice->creator?->company_name ?? 'QueueBill Automation System' }}</strong>
                    {!! nl2br(e($invoice->creator?->company_address ?? "100 Revenue Way, Suite A\nAustin, TX 78701")) !!}
                    <div style="margin-top: 6px; color: {{ $primaryColor }}; font-weight: 500;">
                        @php
                            $senderEmail = $invoice->recurringService?->invoiceStructureTemplate?->sender_email;
                        @endphp
                        {{ $senderEmail ?? 'hello@queuebill.com' }}
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
                <div class="meta-content" style="line-height: 1.6;">
                    <div style="margin-bottom: 2px;"><strong>Issue Date:</strong> {{ $invoice->issue_date->format('M d, Y') }}</div>
                    <div style="margin-bottom: 2px;"><strong>Due Date:</strong> {{ $invoice->due_date->format('M d, Y') }}</div>
                </div>
            </td>
        </tr>
    </table>

    <!-- 3. Line Items Table -->
    <table class="items-table">
        <thead>
            <tr>
                <th style="width: 60%;">Item Description</th>
                <th style="width: 15%; text-align: center;">Qty</th>
                <th style="width: 25%; text-align: right; padding-right: 15px;">Amount</th>
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
                        <div class="item-desc-title">{{ $baseItem->description }}</div>
                        @if($scopeItems->isNotEmpty())
                            <ul class="scope-list">
                                @foreach($scopeItems as $scope)
                                    <li>{{ $scope->description }}</li>
                                @endforeach
                            </ul>
                        @endif
                    </td>
                    <td style="text-align: center; color: #64748b;">1</td>
                    <td style="text-align: right; font-weight: bold; padding-right: 15px; color: #0f172a;">
                        {{ format_currency($baseItem->amount, $invoice->created_by) }}
                    </td>
                </tr>
            @endif

            @foreach($otherItems as $item)
                <tr>
                    <td>
                        <div class="item-desc-title">{{ $item->description }}</div>
                        @if($item->is_adhoc)
                            <span class="adhoc-badge">Ad-Hoc Line Injection</span>
                        @else
                            <span class="contract-badge">Additional Contract Item</span>
                        @endif
                    </td>
                    <td style="text-align: center; color: #64748b;">1</td>
                    <td style="text-align: right; font-weight: bold; padding-right: 15px; color: #0f172a;">
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

    <!-- 4. Summary and Totals -->
    <div class="clearfix">
        <div class="totals-block">
            <table class="total-row-table">
                <tr>
                    <td class="total-label">Subtotal:</td>
                    <td class="total-value">{{ format_currency($invoice->subtotal, $invoice->created_by) }}</td>
                </tr>
                <tr class="grand-total-row">
                    <td class="grand-total-label">Total Amount Due:</td>
                    <td class="grand-total-value">{{ format_currency($invoice->total, $invoice->created_by) }}</td>
                </tr>
            </table>
        </div>
    </div>

    <!-- 5. Elegant Branded Footer -->
    <div class="invoice-footer">
        <div style="font-weight: bold; color: #64748b; margin-bottom: 4px;">QueueBill Automated Billing Service</div>
        <div>"Your Recurring Revenue, Perfectly Aligned."</div>
    </div>
</div>

</body>
</html>
