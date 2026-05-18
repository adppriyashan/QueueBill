<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Structure Design Live Preview: {{ $template->title }}</title>
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-9ndCyUaIbzAi2FUVXJi0CjmCapSmO7SnpJef0486qhLnuZ2cdeRhO02iuK6FUUVM" crossorigin="anonymous">
    
    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background: linear-gradient(135deg, #f1f5f9 0%, #cbd5e1 100%);
            min-height: 100vh;
            padding: 40px 15px;
        }

        .invoice-preview-card {
            max-width: 850px;
            margin: 0 auto;
            background: #ffffff;
            border-radius: 24px;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            border: 1px solid rgba(255, 255, 255, 0.5);
        }

        /* Dynamic Layout Styling based on Template Properties */
        .template-header-bar {
            background: linear-gradient(135deg, #4f46e5 0%, #7c3aed 100%);
            color: #ffffff;
            padding: 40px;
        }

        /* If title contains "hosting" or "cloud" - use secondary cyan/blue theme */
        @if(str_contains(strtolower($template->title), 'hosting') || str_contains(strtolower($template->title), 'cloud'))
        .template-header-bar {
            background: linear-gradient(135deg, #06b6d4 0%, #3b82f6 100%);
        }
        .text-theme {
            color: #06b6d4 !important;
        }
        @elseif(str_contains(strtolower($template->title), 'corporate') || str_contains(strtolower($template->title), 'enterprise'))
        .template-header-bar {
            background: linear-gradient(135deg, #1e293b 0%, #475569 100%);
        }
        .text-theme {
            color: #1e293b !important;
        }
        @else
        .text-theme {
            color: #4f46e5 !important;
        }
        @endif

        .invoice-body {
            padding: 40px;
        }

        .table-invoice th {
            text-transform: uppercase;
            font-size: 0.75rem;
            letter-spacing: 0.05em;
            font-weight: 700;
            color: #64748b;
            border-bottom: 2px solid #e2e8f0;
            padding: 12px 10px;
        }

        .table-invoice td {
            padding: 16px 10px;
            border-bottom: 1px solid #f1f5f9;
            color: #334155;
            font-size: 0.95rem;
        }

        .invoice-badge {
            background: rgba(255, 255, 255, 0.2);
            color: #ffffff;
            padding: 6px 16px;
            border-radius: 50rem;
            font-weight: 600;
            font-size: 0.8rem;
            backdrop-filter: blur(8px);
        }

        .fs-7 { font-size: 0.85rem; }
        .fs-8 { font-size: 0.75rem; }
    </style>
</head>
<body>

    <div class="container">
        <!-- Live Customizer Info Bar -->
        <div class="alert alert-info border-0 rounded-4 shadow-sm max-width-850 mx-auto mb-4 p-3 d-flex align-items-center justify-content-between" style="max-width: 850px;">
            <div class="d-flex align-items-center">
                <i class="fas fa-eye text-info fa-lg me-3"></i>
                <div>
                    <h6 class="mb-0 fw-bold">Live Configuration Mode</h6>
                    <span class="fs-8">Template: <strong>{{ $template->title }}</strong> | Slug: <code>/invoices/templates/{{ $template->slug }}</code></span>
                </div>
            </div>
            <span class="badge bg-info text-white px-3 py-2 rounded-3 fs-8">Dynamic View</span>
        </div>

        <!-- Simulated Rendered Invoice Layout -->
        <div class="invoice-preview-card">
            <!-- Partition A: Dynamic Custom Branded Header -->
            <div class="template-header-bar d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center gap-3">
                <div>
                    <span class="invoice-badge mb-3 d-inline-block">Simulated Statement</span>
                    <h2 class="fw-bold tracking-tight mb-1">{{ auth()->user()->company_name ?? 'QueueBill Automation System' }}</h2>
                    <p class="fs-7 mb-0 opacity-75">Layout branded dynamically via template: <strong>{{ $template->title }}</strong></p>
                </div>
                <div class="text-md-end">
                    <h3 class="fw-bold mb-1">INVOICE</h3>
                    <span class="fs-7 opacity-75">#QB-2026-MOCK</span>
                </div>
            </div>

            <!-- Partition B: Invoice Metadata Details -->
            <div class="invoice-body">
                <div class="row g-4 mb-5 fs-7">
                    <div class="col-12 col-md-4">
                        <h6 class="text-secondary fw-bold text-uppercase fs-8 mb-2">Billed From</h6>
                        <strong class="text-dark d-block">{{ auth()->user()->company_name ?? 'QueueBill Automation System' }}</strong>
                        <span class="text-muted d-block">{!! nl2br(e(auth()->user()->company_address ?? "100 Revenue Way, Suite A\nAustin, TX 78701")) !!}</span>
                        <!-- Custom Fallback Email overridden via template properties -->
                        <span class="text-primary fw-medium d-block mt-2">
                            <i class="far fa-envelope me-1"></i>
                            @if($template->sender_email)
                                <strong>{{ $template->sender_email }}</strong> <small class="text-muted">(Override)</small>
                            @else
                                hello@queuebill.com <small class="text-muted">(System Default)</small>
                            @endif
                        </span>
                    </div>
                    <div class="col-12 col-md-4">
                        <h6 class="text-secondary fw-bold text-uppercase fs-8 mb-2">Billed To</h6>
                        <strong class="text-dark d-block">Simulated Client Corp</strong>
                        <span class="text-muted d-block">456 Enterprise Boulevard</span>
                        <span class="text-muted d-block">New York, NY 10001</span>
                        <span class="text-muted d-block">client@corporation.com</span>
                    </div>
                    <div class="col-12 col-md-4 text-md-end">
                        <h6 class="text-secondary fw-bold text-uppercase fs-8 mb-2">Cycle Timeline</h6>
                        <div class="mb-1"><strong>Issue Date:</strong> <span class="text-muted">May 18, 2026</span></div>
                        <div class="mb-1"><strong>Due Date:</strong> <span class="text-muted">Jun 01, 2026</span></div>
                        <div class="mb-1"><strong>Period From:</strong> <span class="text-muted">May 18, 2026</span></div>
                        <div class="mb-1"><strong>Period To:</strong> <span class="text-muted">Jun 17, 2026</span></div>
                    </div>
                </div>

                <!-- Partition C: Invoice Items Breakdown -->
                <div class="table-responsive mb-5">
                    <table class="table table-invoice mb-0">
                        <thead>
                            <tr>
                                <th style="width: 60%">Item Description</th>
                                <th style="width: 15%" class="text-center">Qty</th>
                                <th style="width: 25%" class="text-end">Amount</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>
                                    <strong class="text-dark d-block">Base subscription service cost</strong>
                                    <small class="text-muted">Calculated decimal pricing engine</small>
                                </td>
                                <td class="text-center text-secondary">1</td>
                                <td class="text-end fw-bold text-dark">@currencySymbol()199.00</td>
                            </tr>
                            <tr>
                                <td>
                                    <strong class="text-dark d-block">Simulated Scope Line element 1</strong>
                                    <small class="text-muted">Scope details list parsed from configuration</small>
                                </td>
                                <td class="text-center text-secondary">1</td>
                                <td class="text-end fw-bold text-dark">@currencySymbol()0.00</td>
                            </tr>
                            <tr>
                                <td>
                                    <strong class="text-dark d-block">Manual pre-emptive adjustment line</strong>
                                    <small class="text-muted">Injected prior-to-billing adhoc item</small>
                                </td>
                                <td class="text-center text-secondary">1</td>
                                <td class="text-end fw-bold text-dark">@currencySymbol()50.00</td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <!-- Partition D: Sum totals -->
                <div class="row justify-content-end text-md-end">
                    <div class="col-12 col-md-5">
                        <div class="d-flex justify-content-between py-2 border-bottom fs-7">
                            <span class="text-secondary fw-semibold">Subtotal:</span>
                            <span class="fw-bold text-dark">@currencySymbol()249.00</span>
                        </div>
                        <div class="d-flex justify-content-between py-3 border-bottom fs-6">
                            <span class="text-dark fw-bold">Total Amount Due:</span>
                            <span class="fw-extrabold text-theme fs-4 fw-bold">@currencySymbol()249.00</span>
                        </div>
                        <div class="mt-4">
                            <span class="text-muted fs-8 font-italic">"Your Recurring Revenue, Perfectly Aligned."</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap 5 JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
