<!DOCTYPE html>
<html lang="en" class="h-100">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'QueueBill - Perfect Recurring Revenue Alignment')</title>
    
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/all.min.css" rel="stylesheet" integrity="sha384-9ndCyUaIbzAi2FUVXJi0CjmCapSmO7SnpJef0486qhLnuZ2cdeRhO02iuK6FUUVM" crossorigin="anonymous">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-9ndCyUaIbzAi2FUVXJi0CjmCapSmO7SnpJef0486qhLnuZ2cdeRhO02iuK6FUUVM" crossorigin="anonymous">
    
    <!-- Premium Custom Styles -->
    <style>
        :root {
            --primary-gradient: linear-gradient(135deg, #4f46e5 0%, #7c3aed 100%);
            --secondary-gradient: linear-gradient(135deg, #06b6d4 0%, #3b82f6 100%);
            --accent-gradient: linear-gradient(135deg, #f43f5e 0%, #ec4899 100%);
            --glass-bg: rgba(255, 255, 255, 0.8);
            --glass-border: rgba(255, 255, 255, 0.2);
            --primary-soft: rgba(79, 70, 229, 0.08);
            --success-soft: rgba(16, 185, 129, 0.08);
            --warning-soft: rgba(245, 158, 11, 0.08);
            --danger-soft: rgba(239, 68, 68, 0.08);
            --info-soft: rgba(6, 180, 212, 0.08);
        }
        
        body {
            font-family: 'Plus Jakarta Sans', 'Outfit', sans-serif;
            background-color: #f8fafc;
            color: #0f172a;
        }

        /* Sidebar Styling */
        @media (min-width: 991.98px) {
            body {
                padding-left: 260px;
            }
            .sidebar {
                width: 260px;
                height: 100vh;
                position: fixed;
                top: 0;
                left: 0;
                z-index: 1030;
                padding-top: 58px;
            }
        }
        
        .sidebar {
            transition: all 0.3s ease;
        }
        
        .sidebar .list-group-item {
            border-radius: 8px;
            margin-bottom: 4px;
            padding: 10px 16px;
            font-weight: 500;
            color: #475569;
            transition: all 0.2s ease;
        }
        
        .sidebar .list-group-item:hover {
            background-color: #f1f5f9;
            color: #1e293b;
        }

        /* Top Navbar */
        #main-navbar {
            height: 62px;
            z-index: 1020;
        }
        
        @media (min-width: 991.98px) {
            #main-navbar {
                padding-left: 280px;
            }
        }

        /* Main Container */
        main {
            padding-top: 86px;
            min-height: calc(100vh - 62px);
        }

        /* Card and Glassmorphism */
        .card {
            border: 1px solid rgba(226, 232, 240, 0.8);
            border-radius: 16px;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05), 0 2px 4px -2px rgba(0, 0, 0, 0.05);
            background: #ffffff;
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }
        
        .card-hover:hover {
            transform: translateY(-4px);
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.05), 0 4px 6px -4px rgba(0, 0, 0, 0.05);
        }
        
        .gradient-card-primary {
            background: var(--primary-gradient);
            color: #ffffff;
            border: none;
        }

        .gradient-card-secondary {
            background: var(--secondary-gradient);
            color: #ffffff;
            border: none;
        }

        /* Alertsofts */
        .bg-primary-soft { background-color: var(--primary-soft); }
        .bg-success-soft { background-color: var(--success-soft); }
        .bg-warning-soft { background-color: var(--warning-soft); }
        .bg-danger-soft { background-color: var(--danger-soft); }
        .bg-info-soft { background-color: var(--info-soft); }

        .text-primary-soft { color: #4f46e5; }
        .text-success-soft { color: #10b981; }
        .text-warning-soft { color: #f59e0b; }
        .text-danger-soft { color: #ef4848; }

        /* Tables */
        .table-premium {
            vertical-align: middle;
        }
        .table-premium th {
            font-weight: 600;
            color: #475569;
            background-color: #f8fafc;
            border-bottom: 2px solid #e2e8f0;
            text-transform: uppercase;
            font-size: 0.75rem;
            letter-spacing: 0.05em;
            padding: 14px 16px;
        }
        .table-premium td {
            padding: 14px 16px;
            color: #334155;
            border-bottom: 1px solid #f1f5f9;
        }
        
        /* Form Controls */
        .form-control, .form-select {
            border: 1px solid #cbd5e1;
            border-radius: 10px;
            padding: 10px 14px;
            font-size: 0.95rem;
            transition: all 0.2s ease;
        }
        
        .form-control:focus, .form-select:focus {
            border-color: #6366f1;
            box-shadow: 0 0 0 4px rgba(99, 102, 241, 0.15);
        }

        .input-group-text {
            border: 1px solid #cbd5e1;
            border-radius: 10px;
            background-color: #f8fafc;
        }

        /* Buttons */
        .btn {
            padding: 9px 18px;
            font-weight: 500;
            border-radius: 10px;
            transition: all 0.2s ease;
        }
        
        .btn-primary {
            background: var(--primary-gradient);
            border: none;
            box-shadow: 0 4px 6px -1px rgba(99, 102, 241, 0.3);
        }
        .btn-primary:hover, .btn-primary:focus {
            background: linear-gradient(135deg, #4338ca 0%, #6d28d9 100%);
            transform: translateY(-1px);
            box-shadow: 0 10px 15px -3px rgba(99, 102, 241, 0.4);
        }
        
        .btn-secondary {
            background-color: #64748b;
            border: none;
        }
        .btn-secondary:hover {
            background-color: #475569;
        }

        .badge-premium {
            padding: 6px 12px;
            border-radius: 50rem;
            font-weight: 600;
            font-size: 0.75rem;
        }
        
        .fs-7 { font-size: 0.8rem; }
        .fs-8 { font-size: 0.7rem; }
    </style>
    @yield('styles')
</head>
<body class="d-flex flex-column h-100">

    <!-- Header Navbar Partition -->
    @include('layouts.partials.navbar')

    <!-- Left Sidebar Partition -->
    @include('layouts.partials.sidebar')

    <!-- Main Content Yield -->
    <main class="flex-shrink-0">
        <div class="container-fluid px-4 pb-4">
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show border-0 rounded-4 shadow-sm mb-4" role="alert">
                    <div class="d-flex align-items-center">
                        <i class="fas fa-check-circle me-3 fs-4 text-success"></i>
                        <div>
                            <strong>Success!</strong> {{ session('success') }}
                        </div>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show border-0 rounded-4 shadow-sm mb-4" role="alert">
                    <div class="d-flex align-items-center">
                        <i class="fas fa-exclamation-circle me-3 fs-4 text-danger"></i>
                        <div>
                            <strong>Error!</strong> {{ session('error') }}
                        </div>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @yield('content')
        </div>
    </main>

    <!-- Footer Partition -->
    @include('layouts.partials.footer')

    <!-- Bootstrap 5 JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-geWF76RCwLtnZ8qwWowPQNguL3RmwHVBC9FhGdlKrxdiJJigb/j/68SIy3Te4Bkz" crossorigin="anonymous"></script>
    @yield('scripts')
</body>
</html>
