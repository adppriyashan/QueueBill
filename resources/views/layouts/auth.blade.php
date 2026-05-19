<!DOCTYPE html>
<html lang="en" class="h-100">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'QueueBill Authenticate')</title>
    
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-9ndCyUaIbzAi2FUVXJi0CjmCapSmO7SnpJef0486qhLnuZ2cdeRhO02iuK6FUUVM" crossorigin="anonymous">
    
    <style>
        :root {
            --primary-gradient: linear-gradient(135deg, #4f46e5 0%, #7c3aed 100%);
            --secondary-gradient: linear-gradient(135deg, #06b6d4 0%, #3b82f6 100%);
            --glass-bg: rgba(255, 255, 255, 0.7);
            --glass-border: rgba(255, 255, 255, 0.5);
            --glass-shadow: 0 8px 32px 0 rgba(31, 38, 135, 0.1);
        }
        
        body {
            font-family: 'Plus Jakarta Sans', 'Outfit', sans-serif;
            background: #f1f5f9;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
            overflow: hidden;
        }

        /* Animated Background Elements */
        .bg-shape {
            position: absolute;
            border-radius: 50%;
            filter: blur(80px);
            opacity: 0.6;
            z-index: -1;
            animation: pulse 10s infinite alternate cubic-bezier(0.4, 0, 0.2, 1);
        }

        .bg-shape-1 {
            background: var(--primary-gradient);
            width: 500px;
            height: 500px;
            top: -150px;
            left: -150px;
            animation-delay: 0s;
        }

        .bg-shape-2 {
            background: var(--secondary-gradient);
            width: 600px;
            height: 600px;
            bottom: -200px;
            right: -100px;
            animation-delay: -5s;
        }

        @keyframes pulse {
            0% { transform: scale(1) translate(0, 0); opacity: 0.5; }
            50% { transform: scale(1.1) translate(50px, -50px); opacity: 0.8; }
            100% { transform: scale(0.9) translate(-50px, 50px); opacity: 0.5; }
        }

        @keyframes fadeInUp {
            0% { opacity: 0; transform: translateY(30px) scale(0.95); }
            100% { opacity: 1; transform: translateY(0) scale(1); }
        }

        .auth-container {
            width: 100%;
            max-width: 450px;
            padding: 20px;
            z-index: 10;
        }

        .card {
            border: 1px solid var(--glass-border);
            border-radius: 28px;
            background: var(--glass-bg);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            box-shadow: var(--glass-shadow);
            animation: fadeInUp 0.8s cubic-bezier(0.16, 1, 0.3, 1) forwards;
            overflow: hidden;
            position: relative;
        }

        /* Subtle inner highlight for glassmorphism */
        .card::before {
            content: '';
            position: absolute;
            top: 0; left: 0; right: 0; height: 1px;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.8), transparent);
            z-index: 1;
        }

        .form-control, .input-group-text {
            border: 1px solid rgba(203, 213, 225, 0.6);
            background: rgba(255, 255, 255, 0.6);
            backdrop-filter: blur(10px);
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            color: #1e293b;
        }

        .input-group-text {
            border-right: none;
            color: #64748b;
        }

        .form-control {
            border-left: none;
            padding: 14px 16px;
            font-size: 0.95rem;
            font-weight: 500;
        }
        
        .form-control:focus {
            background: rgba(255, 255, 255, 0.9);
            box-shadow: none;
        }

        /* Group focus within */
        .input-group:focus-within .input-group-text,
        .input-group:focus-within .form-control {
            border-color: #6366f1;
            background: #ffffff;
        }
        
        .input-group:focus-within {
            box-shadow: 0 0 0 4px rgba(99, 102, 241, 0.15);
            border-radius: 12px;
        }

        .input-group-text, .form-control {
            border-radius: 12px;
        }
        
        .input-group .input-group-text {
            border-top-right-radius: 0;
            border-bottom-right-radius: 0;
        }

        .input-group .form-control {
            border-top-left-radius: 0;
            border-bottom-left-radius: 0;
        }

        .btn-sm {
            padding: 8px 16px !important;
            font-size: 0.875rem !important;
            border-radius: 8px !important;
        }

        .btn-primary {
            background: var(--primary-gradient);
            border: none;
            padding: 14px 28px;
            font-weight: 700;
            font-size: 1rem;
            letter-spacing: 0.02em;
            border-radius: 14px;
            box-shadow: 0 10px 20px -5px rgba(79, 70, 229, 0.4);
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            position: relative;
            overflow: hidden;
            z-index: 1;
        }

        .btn-primary::before {
            content: '';
            position: absolute;
            top: 0; left: 0; right: 0; bottom: 0;
            background: linear-gradient(135deg, #6366f1 0%, #8b5cf6 100%);
            z-index: -1;
            transition: opacity 0.3s ease;
            opacity: 0;
        }
        
        .btn-primary:hover, .btn-primary:focus {
            transform: translateY(-2px);
            box-shadow: 0 15px 25px -5px rgba(79, 70, 229, 0.5);
            color: #ffffff;
        }

        .btn-primary:hover::before {
            opacity: 1;
        }
        
        .brand-logo {
            font-size: 2.5rem;
            font-weight: 800;
            background: var(--primary-gradient);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            letter-spacing: -0.04em;
            margin-bottom: 0.25rem;
        }

        .text-muted {
            color: #64748b !important;
        }

        .form-check-input:checked {
            background-color: #6366f1;
            border-color: #6366f1;
        }

        .animate-fade-in {
            animation: fadeInUp 0.5s cubic-bezier(0.16, 1, 0.3, 1) backwards;
        }
        
        /* Staggered animations for form items */
        .stagger-1 { animation-delay: 0.1s; }
        .stagger-2 { animation-delay: 0.2s; }
        .stagger-3 { animation-delay: 0.3s; }
        .stagger-4 { animation-delay: 0.4s; }
        .stagger-5 { animation-delay: 0.5s; }

    </style>
    @yield('styles')
</head>
<body>

    <!-- Animated Background Shapes -->
    <div class="bg-shape bg-shape-1"></div>
    <div class="bg-shape bg-shape-2"></div>

    <div class="auth-container">
        @yield('content')
    </div>

    <!-- Bootstrap 5 JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-geWF76RCwLtnZ8qwWowPQNguL3RmwHVBC9FhGdlKrxdiJJigb/j/68SIy3Te4Bkz" crossorigin="anonymous"></script>
    @yield('scripts')
</body>
</html>
