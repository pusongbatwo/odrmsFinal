<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Document Tracking System')</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <style>
        :root {
            --dark-red: #8B0000;
            --gold: #D4AF37;
            --white: #FFFFFF;
            --light-gray: #F5F5F5;
            --dark-gray: #333333;
        }
        
        .bg-dark-red {
            background-color: var(--dark-red);
        }
        
        .text-gold {
            color: var(--gold);
        }
        
        .border-gold {
            border-color: var(--gold);
        }
        
        .bg-gold {
            background-color: var(--gold);
        }
        
        .text-dark-red {
            color: var(--dark-red);
        }
        
        .dashboard-container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
        }
        
        .dashboard-header {
            background-color: var(--dark-red);
            color: white;
            padding: 1.5rem;
            border-radius: 8px;
            margin-bottom: 1.5rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        
        .card {
            background-color: white;
            border-radius: 8px;
            padding: 1.5rem;
            margin-bottom: 1.5rem;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        
        .card h3 {
            color: var(--dark-red);
            margin-bottom: 1rem;
            font-size: 1.25rem;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }
        
        .reference-badge {
            background-color: var(--gold);
            color: var(--dark-red);
            padding: 0.5rem 1rem;
            border-radius: 20px;
            font-weight: bold;
            font-size: 0.9rem;
        }
        
        .status-badge {
            padding: 0.5rem 1rem;
            border-radius: 20px;
            font-weight: bold;
            font-size: 0.9rem;
            text-transform: capitalize;
        }
        
        .status-pending {
            background-color: #f59e0b;
            color: white;
        }
        
        .status-processing {
            background-color: #3b82f6;
            color: white;
        }
        
        .status-ready-for-payment {
            background-color: #10b981;
            color: white;
        }
        
        .status-ready-for-pickup {
            background-color: #6366f1;
            color: white;
        }
        
        .status-completed {
            background-color: var(--dark-red);
            color: white;
        }
        
        .progress-steps {
            display: flex;
            justify-content: space-between;
            position: relative;
            margin-top: 2rem;
            padding: 0 2rem;
        }
        
        .step {
            display: flex;
            flex-direction: column;
            align-items: center;
            position: relative;
            z-index: 1;
        }
        
        .step-icon {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 0.5rem;
            font-weight: bold;
            border: 3px solid var(--white);
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }
        
        .step-icon.completed {
            background-color: var(--dark-red);
            color: var(--white);
        }
        
        .step-icon.current {
            background-color: var(--gold);
            color: var(--dark-red);
        }
        
        .step-icon.pending {
            background-color: #e5e7eb;
            color: #6b7280;
        }
        
        .step-label {
            font-size: 0.875rem;
            font-weight: 500;
            color: var(--dark-gray);
            text-align: center;
        }
        
        .status-line {
            position: absolute;
            top: 25px;
            left: 0;
            right: 0;
            height: 3px;
            background-color: #e5e7eb;
            z-index: 0;
        }
        
        .status-line-progress {
            height: 100%;
            background-color: var(--gold);
            width: var(--progress-width, 0%);
        }
        
        .details-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 1rem;
        }
        
        .detail-item {
            margin-bottom: 0.75rem;
        }
        
        .detail-label {
            font-weight: 600;
            color: var(--dark-gray);
            display: block;
            margin-bottom: 0.25rem;
        }
        
        .detail-value {
            color: #4b5563;
        }
        
        .payment-actions {
            display: flex;
            gap: 1rem;
            margin-top: 1.5rem;
        }
        
        .pay-button {
            padding: 0.75rem 1.5rem;
            border-radius: 6px;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            cursor: pointer;
            transition: all 0.2s;
        }
        
        .online-pay {
            background-color: #3b82f6;
            color: white;
            border: none;
        }
        
        .online-pay:hover {
            background-color: #2563eb;
        }
        
        .walkin-pay {
            background-color: var(--gold);
            color: var(--dark-red);
            border: none;
        }
        
        .walkin-pay:hover {
            background-color: #c9a227;
        }
        
        .payment-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 0.75rem;
            padding-bottom: 0.75rem;
            border-bottom: 1px solid #e5e7eb;
        }
        
        .payment-label {
            font-weight: 600;
            color: var(--dark-gray);
        }
        
        .payment-value {
            color: #4b5563;
        }
        
        .payment-pending {
            color: #f59e0b;
            font-weight: 600;
        }
        
        .payment-paid {
            color: #10b981;
            font-weight: 600;
        }
    </style>
</head>
<body class="bg-gray-100">
    <header class="bg-dark-red text-white py-6 shadow-md">
        <div class="container mx-auto px-4 flex justify-between items-center">
            <div class="flex items-center">
                <h1 class="text-3xl font-bold">Doc<span class="text-gold">Track</span></h1>
               
            </div>
            <div class="flex items-center space-x-4">
                <span class="text-white">Welcome, User</span>
                <div class="w-10 h-10 rounded-full bg-gold flex items-center justify-center text-dark-red font-bold">
                    <i class="fas fa-user"></i>
                </div>
            </div>
        </div>
    </header>

    <main class="container mx-auto px-4 py-8">
        @yield('content')
    </main>

    <footer class="bg-dark-red text-white py-4 mt-8">
        <div class="container mx-auto px-4 text-center">
            <p>&copy; {{ date('Y') }} Document Tracking System</p>
        </div>
    </footer>

    @stack('scripts')
</body>
</html>