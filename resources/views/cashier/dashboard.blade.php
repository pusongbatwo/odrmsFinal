@php
    $fees = config('services.document_fees');
    $maxCount = max(array_merge([1], array_values($document_type_counts)));
    $barColors = [
        'Official Transcript' => '#8B0000', // dark red
        'Diploma Copy' => '#D4AF37', // gold
        'Enrollment Verification' => '#4CAF50', // green
        'Course Completion Cert' => '#1E40AF', // blue
        'Certificate of Enrollment' => '#A52A2A', // brown
        'Certificate of Graduation' => '#F44336', // red
        'Other' => '#616161', // gray
    ];
@endphp
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document Request Cashier Dashboard</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <style>
        :root {
            --primary: #8B0000; /* College dark red */
            --primary-light: #A52A2A;
            --secondary: #D4AF37; /* Gold accent */
            --dark: #222;
            --light: #FFF;
            --success: #4CAF50;
            --warning: #FFC107;
            --danger: #F44336;
            --gray: #E0E0E0;
            --gray-dark: #616161;
            --bg-light: #FAFAFA;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        body {
            display: flex;
            min-height: 100vh;
            background-color: var(--bg-light);
            color: var(--dark);
        }

        /* Sidebar Styles */
        .sidebar {
            width: 260px;
            background: var(--primary);
            transition: all 0.3s ease;
            height: 100vh;
            position: sticky;
            top: 0;
            display: flex;
            flex-direction: column;
            z-index: 100;
            box-shadow: 2px 0 10px rgba(0, 0, 0, 0.1);
        }

        .sidebar-header {
            padding: 1.5rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }

        .logo {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .logo-icon {
            width: 32px;
            height: 32px;
            background: var(--secondary);
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--primary);
            font-size: 18px;
        }

        .logo-text {
            font-weight: 600;
            font-size: 18px;
            color: var(--light);
        }

        .toggle-btn {
            background: none;
            border: none;
            font-size: 18px;
            cursor: pointer;
            color: var(--light);
            opacity: 0.8;
        }

        .sidebar-menu {
            padding: 1.5rem 0;
            flex-grow: 1;
            overflow-y: auto;
        }

        .menu-title {
            padding: 0 1.5rem 0.5rem;
            font-size: 12px;
            font-weight: 600;
            color: rgba(255, 255, 255, 0.6);
            text-transform: uppercase;
        }

        .menu-items {
            list-style: none;
        }

        .menu-item {
            margin-bottom: 4px;
        }

        .menu-link {
            display: flex;
            align-items: center;
            padding: 12px 1.5rem;
            color: rgba(255, 255, 255, 0.8);
            text-decoration: none;
            transition: all 0.2s ease;
            position: relative;
        }

        .menu-link:hover, .menu-link.active {
            background: rgba(255, 255, 255, 0.1);
            color: var(--light);
        }

        .menu-link:hover::before, .menu-link.active::before {
            content: '';
            position: absolute;
            left: 0;
            top: 0;
            height: 100%;
            width: 4px;
            background: var(--secondary);
        }

        .menu-icon {
            font-size: 20px;
            margin-right: 12px;
            color: var(--secondary);
        }

        .menu-badge {
            margin-left: auto;
            background: var(--secondary);
            color: var(--primary);
            font-size: 10px;
            font-weight: 600;
            padding: 2px 6px;
            border-radius: 10px;
        }

        .sidebar-footer {
            padding: 1rem 1.5rem;
            border-top: 1px solid rgba(255, 255, 255, 0.1);
        }

        .user-profile {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .avatar {
            width: 36px;
            height: 36px;
            border-radius: 50%;
            object-fit: cover;
            border: 2px solid var(--secondary);
        }

        .user-name {
            font-weight: 600;
            font-size: 14px;
            color: var(--light);
        }

        .user-role {
            font-size: 12px;
            color: rgba(255, 255, 255, 0.6);
        }

        /* Main Content */
        .main-content {
            flex-grow: 1;
            padding: 1.5rem;
            overflow-y: auto;
        }

        .header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 1.5rem;
            background: var(--light);
            padding: 1rem 1.5rem;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
        }

        .page-title h1 {
            font-size: 24px;
            font-weight: 600;
            color: var(--primary);
        }

        .search-bar {
            display: flex;
            align-items: center;
            background: var(--light);
            border-radius: 8px;
            padding: 8px 12px;
            width: 300px;
            border: 1px solid var(--gray);
        }

        .search-bar input {
            border: none;
            outline: none;
            flex-grow: 1;
            padding: 4px 8px;
        }

        .search-icon {
            color: var(--gray-dark);
            margin-right: 8px;
        }

        .user-actions {
            display: flex;
            align-items: center;
            gap: 16px;
        }

        .notification-icon {
            font-size: 20px;
            color: var(--gray-dark);
            position: relative;
        }

        .notification-badge {
            position: absolute;
            top: -6px;
            right: -6px;
            background: var(--danger);
            color: white;
            font-size: 10px;
            width: 16px;
            height: 16px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        /* Dashboard Cards */
        .dashboard-content {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
            gap: 1.5rem;
            margin-bottom: 1.5rem;
        }

        .summary-card {
            background: var(--light);
            border-radius: 12px;
            padding: 1.5rem;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
            border-top: 3px solid var(--secondary);
        }

        .card-header {
            display: flex;
            align-items: center;
            margin-bottom: 1rem;
        }

        .card-icon {
            width: 48px;
            height: 48px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 20px;
            margin-right: 12px;
        }

        .card-icon.pending {
            background: rgba(255, 193, 7, 0.1);
            color: var(--warning);
        }

        .card-icon.paid {
            background: rgba(76, 175, 80, 0.1);
            color: var(--success);
        }

        .card-icon.amount {
            background: rgba(139, 0, 0, 0.1);
            color: var(--primary);
        }

        .card-icon.requests {
            background: rgba(67, 97, 238, 0.1);
            color: var(--primary-light);
        }

        .card-title {
            font-size: 14px;
            color: var(--gray-dark);
            font-weight: 500;
        }

        .card-value {
            font-size: 24px;
            font-weight: 600;
            margin: 8px 0 4px;
            color: var(--primary);
        }

        .card-change {
            font-size: 12px;
            display: flex;
            align-items: center;
        }

        .card-change.positive {
            color: var(--success);
        }

        /* Document Types Breakdown */
        .document-types {
            background: var(--light);
            border-radius: 12px;
            padding: 1.5rem;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
            margin-bottom: 1.5rem;
            border-top: 3px solid var(--secondary);
        }

        .progress-container {
            margin-top: 1rem;
        }

        .progress-item {
            display: flex;
            align-items: center;
            margin-bottom: 12px;
        }

        .progress-label {
            width: 150px;
            font-size: 14px;
        }

        .progress-bar {
            flex-grow: 1;
            height: 8px;
            background: var(--gray);
            border-radius: 4px;
            overflow: hidden;
        }

        .progress-fill {
            height: 100%;
            border-radius: 4px;
            background: var(--primary);
        }

        /* Recent Requests Table */
        .recent-requests {
            background: var(--light);
            border-radius: 12px;
            padding: 1.5rem;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
            border-top: 3px solid var(--secondary);
        }

        .section-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 1.5rem;
        }

        .section-title {
            font-size: 18px;
            font-weight: 600;
            color: var(--primary);
        }

        .view-all {
            color: var(--primary);
            font-size: 14px;
            font-weight: 500;
            text-decoration: none;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th {
            text-align: left;
            padding: 12px 16px;
            font-size: 12px;
            color: var(--gray-dark);
            font-weight: 500;
            text-transform: uppercase;
            border-bottom: 2px solid var(--gray);
        }

        td {
            padding: 16px;
            border-bottom: 1px solid var(--gray);
            font-size: 14px;
        }

        .request-id {
            font-weight: 600;
            color: var(--primary);
        }

        .status-badge {
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 500;
        }

        .status-pending {
            background: rgba(255, 193, 7, 0.1);
            color: var(--warning);
        }

        .status-paid {
            background: rgba(76, 175, 80, 0.1);
            color: var(--success);
        }

        .status-cancelled {
            background: rgba(244, 67, 54, 0.1);
            color: var(--danger);
        }

        .action-btn {
            background: none;
            border: none;
            color: var(--gray-dark);
            cursor: pointer;
            font-size: 16px;
            margin-left: 8px;
        }

        .action-btn:hover {
            color: var(--primary);
        }

        /* Payment Modal */
        .payment-modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            z-index: 1000;
            justify-content: center;
            align-items: center;
        }

        .modal-content {
            background: var(--light);
            border-radius: 12px;
            width: 500px;
            max-width: 90%;
            padding: 1.5rem;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.15);
        }

        .modal-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1.5rem;
            border-bottom: 1px solid var(--gray);
            padding-bottom: 1rem;
        }

        .modal-title {
            font-size: 20px;
            font-weight: 600;
            color: var(--primary);
        }

        .close-btn {
            background: none;
            border: none;
            font-size: 24px;
            cursor: pointer;
            color: var(--gray-dark);
        }

        .form-group {
            margin-bottom: 1rem;
        }

        .form-label {
            display: block;
            margin-bottom: 8px;
            font-size: 14px;
            font-weight: 500;
        }

        .form-input {
            width: 100%;
            padding: 10px 12px;
            border: 1px solid var(--gray);
            border-radius: 6px;
            font-size: 14px;
        }

        .payment-options {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 12px;
            margin: 1.5rem 0;
        }

        .payment-option {
            border: 1px solid var(--gray);
            border-radius: 6px;
            padding: 12px;
            text-align: center;
            cursor: pointer;
            transition: all 0.2s;
        }

        .payment-option:hover {
            border-color: var(--primary);
        }

        .payment-option.selected {
            border-color: var(--primary);
            background: rgba(139, 0, 0, 0.05);
        }

        .payment-icon {
            font-size: 24px;
            margin-bottom: 8px;
            color: var(--primary);
        }

        .modal-footer {
            display: flex;
            justify-content: flex-end;
            gap: 12px;
            margin-top: 1.5rem;
            border-top: 1px solid var(--gray);
            padding-top: 1.5rem;
        }

        .btn {
            padding: 10px 20px;
            border-radius: 6px;
            font-weight: 500;
            cursor: pointer;
            border: none;
        }

        .btn-secondary {
            background: var(--gray);
            color: var(--dark);
        }

        .btn-primary {
            background: var(--primary);
            color: var(--light);
        }

        /* Responsive */
        @media (max-width: 1200px) {
            .sidebar {
                width: 80px;
            }
            .logo-text, .menu-title, .menu-text, .user-details {
                display: none;
            }
            .menu-link {
                justify-content: center;
                padding: 12px 0;
            }
            .menu-icon {
                margin-right: 0;
                font-size: 22px;
            }
            .search-bar {
                width: 200px;
            }
        }

        @media (max-width: 768px) {
            .dashboard-content {
                grid-template-columns: 1fr;
            }
            .header {
                flex-direction: column;
                align-items: flex-start;
                gap: 1rem;
            }
            .search-bar {
                width: 100%;
            }
        }

        /* Feature UI Styles */
        .feature-ui {
            display: none;
            margin-top: 1.5rem;
        }
        
        .feature-ui.active {
            display: block;
        }
        
        .dashboard-sections {
            display: block;
        }
        
        .dashboard-sections.hidden {
            display: none;
        }
        
        /* Process Payment UI */
        .payment-processor {
            background: var(--light);
            border-radius: 12px;
            padding: 1.5rem;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
            border-top: 3px solid var(--secondary);
        }
        
        .payment-form {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 1.5rem;
        }
        
        .form-row {
            margin-bottom: 1rem;
        }
        
        .form-label {
            display: block;
            margin-bottom: 0.5rem;
            font-weight: 500;
            color: var(--gray-dark);
        }
        
        .form-control {
            width: 100%;
            padding: 0.75rem;
            border: 1px solid var(--gray);
            border-radius: 6px;
            font-size: 14px;
        }
        
       
       
        .submit-payment {
            background: var(--primary);
            color: white;
            border: none;
            padding: 12px 24px;
            border-radius: 6px;
            font-weight: 600;
            cursor: pointer;
            font-size: 16px;
        }
        
        /* Document Requests UI */
        .document-requests {
            background: var(--light);
            border-radius: 12px;
            padding: 1.5rem;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
            border-top: 3px solid var(--secondary);
        }
        
        .request-filters {
            display: flex;
            gap: 1rem;
            margin-bottom: 1.5rem;
        }
        
        .filter-select {
            padding: 0.75rem;
            border: 1px solid var(--gray);
            border-radius: 6px;
            min-width: 200px;
        }
        
        /* Transactions UI */
        .transactions-list {
            background: var(--light);
            border-radius: 12px;
            padding: 1.5rem;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
            border-top: 3px solid var(--secondary);
        }
        
        .transaction-item {
            display: flex;
            justify-content: space-between;
            padding: 1rem;
            border-bottom: 1px solid var(--gray);
        }
        
        .transaction-details {
            flex: 2;
        }
        
        .transaction-amount {
            flex: 1;
            text-align: right;
            font-weight: 600;
        }
        
        /* Reports UI */
        .reports-container {
            background: var(--light);
            border-radius: 12px;
            padding: 1.5rem;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
            border-top: 3px solid var(--secondary);
        }
        
        .report-tabs {
            display: flex;
            border-bottom: 1px solid var(--gray);
            margin-bottom: 1.5rem;
        }
        
        .report-tab {
            padding: 0.75rem 1.5rem;
            cursor: pointer;
            border-bottom: 3px solid transparent;
        }
        
        .report-tab.active {
            border-bottom-color: var(--primary);
            font-weight: 600;
        }
        
        .report-content {
            display: none;
        }
        
        .report-content.active {
            display: block;
        }
        
        .chart-placeholder {
            height: 300px;
            background: var(--bg-light);
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 8px;
            margin-bottom: 1.5rem;
        }
    </style>
</head>
<body>
    <!-- Sidebar -->
    <aside class="sidebar">
        <div class="sidebar-header">
            <div class="logo">
                <div class="logo-icon">
                    <i class="fas fa-file-invoice-dollar"></i>
                </div>
                <span class="logo-text">Cashier Panel</span>
            </div>
            <button class="toggle-btn">
                <i class="fas fa-chevron-left"></i>
            </button>
        </div>

        <div class="sidebar-menu">
            <h3 class="menu-title">Main</h3>
            <ul class="menu-items">
                <li class="menu-item">
                    <a href="#" class="menu-link active" data-section="dashboard">
                        <i class="fas fa-home menu-icon"></i>
                        <span class="menu-text">Dashboard</span>
                    </a>
                </li>
                <li class="menu-item">
                    <a href="#" class="menu-link" data-section="processPayment">
                        <i class="fas fa-cash-register menu-icon"></i>
                        <span class="menu-text">Process Payment</span>
                    </a>
                </li>
                <li class="menu-item">
                    <a href="#" class="menu-link" data-section="documentRequests">
                        <i class="fas fa-file-alt menu-icon"></i>
                        <span class="menu-text">Document Requests</span>
                        <span class="menu-badge">12</span>
                    </a>
                </li>
                <li class="menu-item">
                    <a href="#" class="menu-link" data-section="transactions">
                        <i class="fas fa-exchange-alt menu-icon"></i>
                        <span class="menu-text">Cashier Logs</span>
                    </a>
                </li>
            </ul>

            <h3 class="menu-title">Reports</h3>
            <ul class="menu-items">
                <li class="menu-item">
                    <a href="#" class="menu-link" data-section="dailySummary">
                        <i class="fas fa-chart-bar menu-icon"></i>
                        <span class="menu-text">Daily Summary</span>
                    </a>
                </li>
                <li class="menu-item">
                    <a href="#" class="menu-link" data-section="exportRecords">
                        <i class="fas fa-file-export menu-icon"></i>
                        <span class="menu-text">Export Records</span>
                    </a>
                </li>
            </ul>
        </div>

        <div class="sidebar-footer">
            <div class="user-profile">
                <img src="https://randomuser.me/api/portraits/women/45.jpg" alt="User" class="avatar">
                <div class="user-details">
                    <div class="user-name">Maria Garcia</div>
                    <div class="user-role">Document Cashier</div>
                </div>
            </div>
        </div>
    </aside>

    <!-- Main Content -->
    <main class="main-content">
        <div class="header">
            <div class="page-title">
                <h1 id="pageTitle">Document Request Payments</h1>
            </div>
            <div class="search-bar">
                <i class="fas fa-search search-icon"></i>
                <input type="text" placeholder="Search requests...">
            </div>
           
        </div>

        <!-- Dashboard Sections -->
        <div class="dashboard-sections" id="dashboardSections">
            <!-- Summary Cards -->
            <div class="dashboard-content">
                <div class="summary-card">
                    <div class="card-header">
                        <div class="card-icon pending">
                            <i class="fas fa-clock"></i>
                        </div>
                        <div>
                            <div class="card-title">Pending Payments</div>
                            <div class="card-value">{{ $pending_payments }}</div>
                        </div>
                    </div>
                </div>

                <div class="summary-card">
                    <div class="card-header">
                        <div class="card-icon paid">
                            <i class="fas fa-check-circle"></i>
                        </div>
                        <div>
                            <div class="card-title">Paid Today</div>
                            <div class="card-value">{{ $paid_today }}</div>
                        </div>
                    </div>
                </div>

                <div class="summary-card">
                    <div class="card-header">
                        <div class="card-icon amount">
                        <i class="fas fa-peso-sign"></i>
                        </div>
                        <div>
                            <div class="card-title">Total Collected (This Month)</div>
                            <div class="card-value">₱{{ number_format($total_collected_month, 2) }}</div>
                        </div>
                    </div>
                </div>

                <div class="summary-card">
                    <div class="card-header">
                        <div class="card-icon requests">
                            <i class="fas fa-file-alt"></i>
                        </div>
                        <div>
                            <div class="card-title">Total Approved Requests</div>
                            <div class="card-value">{{ $total_approved }}</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Document Types Breakdown -->
            <div class="document-types">
                <div class="section-header">
                    <h2 class="section-title">Document Type Breakdown</h2>
                </div>
                <div class="progress-container" style="display: flex; flex-direction: row; gap: 0.7rem; justify-content: flex-start; align-items: flex-end; height: 170px;">
                    @foreach($document_type_counts as $type => $count)
                    @php
                        $redShades = [
                            '#7f1d1d', // very dark red
                            '#b91c1c', // dark red
                            '#dc2626', // strong red
                            '#ef4444', // medium red
                            '#f87171', // light red
                            '#fca5a5', // very light red
                        ];
                        $barIndex = $loop->index % count($redShades);
                        $barColor = $redShades[$barIndex];
                        // Use solid color for each bar, no gradient
                    @endphp
                    <div class="progress-item" style="display: flex; flex-direction: column; align-items: center; min-width: 80px; justify-content: flex-end; height: 100%;">
                        <div class="progress-value" style="margin-bottom: 0.5rem; font-size: 1.1em; font-weight: 700; color: #c62828; letter-spacing: 0.5px;">{{ $count }}</div>
                        <div class="progress-bar" style="width: 40px; height: 90px; background: #ffebee; border-radius: 12px; display: flex; align-items: flex-end; box-sizing: border-box; box-shadow: 0 2px 8px rgba(183,28,28,0.08);">
                            <div class="progress-fill" style="width: 100%; border-radius: 10px; height: {{ $maxCount > 0 ? number_format(($count / $maxCount) * 100, 2, '.', '') : '0' }}%; background-color: {{ $barColor }}; box-shadow: 0 2px 8px {{ $barColor }}33;"></div>
                        </div>
                        <div class="progress-label" style="margin-top: 0.5rem; font-weight: bold; color: #b71c1c; letter-spacing: 0.5px; font-size: 13px; text-align: center; min-height: 32px; display: flex; align-items: flex-start; justify-content: center; width: 100%; white-space: pre-line;">
                            @php
                                $labelParts = explode(' ', $type, 2);
                            @endphp
                            <span style="width: 100%; text-align: center; align-self: center; display: flex; flex-direction: column;">
                                <span style="display: block;">{{ $labelParts[0] }}</span>
                                <span style="display: block;">{{ $labelParts[1] ?? '' }}</span>
                            </span>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>

            <!-- Recent Requests Table -->
            <div class="recent-requests">
                <div class="section-header">
                    <h2 class="section-title">Recent Document Requests</h2>
                    <a href="#" class="view-all">
                        View All
                        <i class="fas fa-chevron-right"></i>
                    </a>
                </div>

                <table>
                    <thead>
                        <tr>
                            <th>Reference #</th>
                            <th>Student</th>
                            <th>Document Types</th>
                            <th>Amount</th>
                            <th>Payment Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($document_requests->take(5) as $req)
                        <tr data-reference="{{ $req->reference_number }}">
                            <td class="request-id">{{ $req->reference_number }}</td>
                            <td>{{ $req->first_name }} {{ $req->last_name }}</td>
                            <td>
                                @foreach($req->requestedDocuments as $doc)
                                    {{ $doc->document_type }} ({{ $doc->quantity }})@if(!$loop->last), @endif
                                @endforeach
                            </td>
                            <td>
                                @php
                                    $amount = 0;
                                    foreach ($req->requestedDocuments as $doc) {
                                        $amount += ($fees[$doc->document_type] ?? 250) * $doc->quantity;
                                    }
                                @endphp
                                ₱{{ number_format($amount, 2) }}
                            </td>
                            <td>
                                @php
                                    $paymentStatus = $req->payment_status;
                                    $statusLabel = $paymentStatus === 'paid' ? 'Paid' : 'Unpaid';
                                    $statusClass = $paymentStatus === 'paid' ? 'status-paid' : 'status-unpaid';
                                @endphp
                                <span class="status-badge {{ $statusClass }}">{{ $statusLabel }}</span>
                            </td>
                            <td>
                                <button class="action-btn"><i class="fas fa-receipt"></i></button>
                                <button class="action-btn"><i class="fas fa-print"></i></button>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Process Payment UI -->
        <div id="processPaymentUI" class="feature-ui">
            <div class="payment-processor">
                <div class="section-header">
                    <h2 class="section-title">Process Document Payment</h2>
                </div>
                <!-- Search Bar for Filtering Requests -->
                <div class="search-bar" style="margin-bottom: 1rem; width: 100%;">
                    <i class="fas fa-search search-icon"></i>
                    <input type="text" id="paymentSearchInput" placeholder="Search by ID number, name, or reference number..." autocomplete="off">
                    <div id="paymentSearchResults" style="position: absolute; background: #fff; border: 1px solid #ccc; z-index: 10; width: 100%; display: none;"></div>
                </div>
                <div class="payment-form">
                    <div class="form-row">
                        <label class="form-label">Reference Number</label>
                        <input type="text" class="form-control" id="referenceNumberInput" placeholder="Enter reference number" autocomplete="off">
                    </div>
                    <div class="form-row">
                        <label class="form-label">Student Name</label>
                        <input type="text" class="form-control" id="studentNameInput" placeholder="Student name" readonly>
                    </div>
                    <div class="form-row">
                        <label class="form-label">Document Type(s)</label>
                        <input type="text" class="form-control" id="documentTypeInput" placeholder="Document type(s)" readonly>
                    </div>
                    <div class="form-row">
                        <label class="form-label">Amount Due</label>
                        <input type="text" class="form-control" id="amountDueInput" value="" readonly>
                    </div>
                    <div class="form-row">
                        <label class="form-label">Amount Received</label>
                        <input type="text" class="form-control" id="amountReceivedInput" placeholder="Enter amount">
                    </div>
                    <div class="form-row">
                        <label class="form-label">Change</label>
                        <input type="text" class="form-control" id="changeInput" value="₱0.00" readonly>
                    </div>
                </div>
                <button class="submit-payment" id="processPaymentBtn">
                    <i class="fas fa-check-circle"></i> Process Payment
                </button>
            </div>
        </div>

        <!-- Document Requests UI -->
        <div id="documentRequestsUI" class="feature-ui">
            <div class="document-requests" style="position: relative; display: flex; flex-direction: column; height: 480px; min-height: 320px;">
                <div class="section-header" style="position: sticky; top: 0; z-index: 2; background: var(--light); border-radius: 12px 12px 0 0;">
                    <h2 class="section-title">Document Requests</h2>
                </div>
                <div class="request-filters" style="position: sticky; top: 3.5rem; z-index: 2; background: var(--light); padding-bottom: 0.5rem; margin-bottom: 0;">
                    <select class="filter-select">
                        <option>All Statuses</option>
                        <option>Pending</option>
                        <option>Paid</option>
                        <option>Cancelled</option>
                    </select>
                    <select class="filter-select">
                        <option>All Document Types</option>
                        <option>Transcript</option>
                        <option>Diploma</option>
                        <option>Certificate</option>
                    </select>
                    <input type="date" class="filter-select" placeholder="Filter by date">
                </div>
                <div style="flex: 1 1 auto; overflow-y: auto; min-height: 0;">
                    <table style="width: 100%; border-collapse: collapse;">
                        <thead>
                            <tr style="position: sticky; top: 0; z-index: 3; background: var(--light);">
                                <th>Reference #</th>
                                <th>Student</th>
                                <th>Document Types</th>
                                <th>Amount</th>
                                <th>Payment Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($document_requests as $req)
                            <tr data-reference="{{ $req->reference_number }}">
                                <td class="request-id">{{ $req->reference_number }}</td>
                                <td>{{ $req->first_name }} {{ $req->last_name }}</td>
                                <td>
                                    @foreach($req->requestedDocuments as $doc)
                                        {{ $doc->document_type }} ({{ $doc->quantity }})@if(!$loop->last), @endif
                                    @endforeach
                                </td>
                                <td>
                                    @php
                                        $amount = 0;
                                        foreach ($req->requestedDocuments as $doc) {
                                            $amount += ($fees[$doc->document_type] ?? 250) * $doc->quantity;
                                        }
                                    @endphp
                                    ₱{{ number_format($amount, 2) }}
                                </td>
                                <td>
                                    @php
                                        $paymentStatus = $req->payment_status;
                                        $statusLabel = $paymentStatus === 'paid' ? 'Paid' : 'Unpaid';
                                        $statusClass = $paymentStatus === 'paid' ? 'status-paid' : 'status-unpaid';
                                    @endphp
                                    <span class="status-badge {{ $statusClass }}">{{ $statusLabel }}</span>
                                </td>
                                <td>
                                    <button class="action-btn"><i class="fas fa-receipt"></i></button>
                                    <button class="action-btn"><i class="fas fa-print"></i></button>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Cashier Logs UI -->
        <div id="transactionsUI" class="feature-ui">
            <div class="transactions-list">
                <div class="section-header">
                    <h2 class="section-title">Cashier Logs</h2>
                    <div class="search-bar" style="width: 300px;">
                        <i class="fas fa-search search-icon"></i>
                        <input type="text" placeholder="Search cashier logs..." id="cashierLogSearchInput">
                    </div>
                </div>
                @if($cashier_logs->isEmpty())
                    <div style="padding: 2rem; text-align: center; color: #888;">No logs found.</div>
                @else
                    @foreach($cashier_logs as $log)
                        <div class="transaction-item">
                            <div class="transaction-details">
                                <div><strong>{{ ucfirst(str_replace('_', ' ', $log->type)) }}</strong> - {{ $log->message }}</div>
                                <div style="font-size: 13px; color: #888;">{{ $log->created_at->format('M d, Y h:i A') }}</div>
                            </div>
                        </div>
                    @endforeach
                @endif
            </div>
        </div>

        <!-- Daily Summary UI -->
        <div id="dailySummaryUI" class="feature-ui">
            <div class="reports-container">
                <div class="section-header">
                    <h2 class="section-title">Daily Summary Report</h2>
                    <div class="date-range-picker">
                        <input type="date" class="filter-select">
                        <span>to</span>
                        <input type="date" class="filter-select">
                    </div>
                </div>
                
                <div class="report-tabs">
                    <div class="report-tab active">Summary</div>
                    <div class="report-tab">By Document Type</div>
                   
                </div>
                
                <div class="report-content active">
                    <div class="chart-placeholder">
                        [Daily Summary Chart]
                    </div>
                    
                    <div class="dashboard-content">
                        <div class="summary-card">
                            <div class="card-header">
                                <div class="card-icon amount">
                                    <i class="fas fa-peso-sign"></i>
                                </div>
                                <div>
                                    <div class="card-title">Total Collected</div>
                                    <div class="card-value">₱1,245</div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="summary-card">
                            <div class="card-header">
                                <div class="card-icon requests">
                                    <i class="fas fa-file-alt"></i>
                                </div>
                                <div>
                                    <div class="card-title">Total Requests</div>
                                    <div class="card-value">36</div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="summary-card">
                            <div class="card-header">
                                <div class="card-icon paid">
                                    <i class="fas fa-check-circle"></i>
                                </div>
                                <div>
                                    <div class="card-title">Completed</div>
                                    <div class="card-value">24</div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="summary-card">
                            <div class="card-header">
                                <div class="card-icon pending">
                                    <i class="fas fa-clock"></i>
                                </div>
                                <div>
                                    <div class="card-title">Pending</div>
                                    <div class="card-value">12</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Export Records UI -->
        <div id="exportRecordsUI" class="feature-ui">
            <div class="reports-container">
                <div class="section-header">
                    <h2 class="section-title">Export Records</h2>
                </div>
                
                <div class="form-row">
                    <label class="form-label">Date Range</label>
                    <div style="display: flex; gap: 1rem;">
                        <input type="date" class="form-control">
                        <span style="align-self: center;">to</span>
                        <input type="date" class="form-control">
                    </div>
                </div>
                
                <div class="form-row">
                    <label class="form-label">Report Type</label>
                    <select class="form-control">
                        <option>Transaction Records</option>
                        <option>Document Requests</option>
                        <option>Payment Summary</option>
                        <option>Daily Totals</option>
                    </select>
                </div>
                
                <div class="form-row">
                    <label class="form-label">File Format</label>
                    <div class="payment-methods">
                        <div class="method-card selected">
                            <div class="method-icon">
                                <i class="fas fa-file-excel"></i>
                            </div>
                            <div>Excel (.xlsx)</div>
                        </div>
                        <div class="method-card">
                            <div class="method-icon">
                                <i class="fas fa-file-csv"></i>
                            </div>
                            <div>CSV (.csv)</div>
                        </div>
                        <div class="method-card">
                            <div class="method-icon">
                                <i class="fas fa-file-pdf"></i>
                            </div>
                            <div>PDF (.pdf)</div>
                        </div>
                    </div>
                </div>
                
                <button class="submit-payment" style="margin-top: 1.5rem;">
                    <i class="fas fa-download"></i> Export Records
                </button>
            </div>
        </div>
    </main>

    <!-- Payment Modal -->
    <div class="payment-modal" id="paymentModal">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title">Process Document Payment</h3>
                <button class="close-btn" onclick="closePaymentModal()">&times;</button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label class="form-label">Request ID</label>
                    <input type="text" class="form-input" value="#DOC-2023-1024" readonly>
                </div>
                <div class="form-group">
                    <label class="form-label">Student Name</label>
                    <input type="text" class="form-input" value="Sarah Williams" readonly>
                </div>
                <div class="form-group">
                    <label class="form-label">Document Type</label>
                    <input type="text" class="form-input" value="Diploma Copy" readonly>
                </div>
                <div class="form-group">
                    <label class="form-label">Amount Due</label>
                    <input type="text" class="form-input" value="₱25.00" readonly>
                </div>
                
              
                <div class="form-group">
                    <label class="form-label">Amount Received</label>
                    <input type="text" class="form-input" placeholder="Enter amount received">
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-secondary" onclick="closePaymentModal()">Cancel</button>
                <button class="btn btn-primary">Process Payment</button>
            </div>
        </div>
    </div>

    <script>
        // Toggle sidebar
        document.querySelector('.toggle-btn').addEventListener('click', function() {
            document.querySelector('.sidebar').classList.toggle('collapsed');
        });

        // Payment modal functions
        function openPaymentModal() {
            document.getElementById('paymentModal').style.display = 'flex';
        }

        function closePaymentModal() {
            document.getElementById('paymentModal').style.display = 'none';
        }

        // Select payment option
       
        // Simulate loading animation
        document.addEventListener('DOMContentLoaded', function() {
            const cards = document.querySelectorAll('.summary-card');
            cards.forEach((card, index) => {
                setTimeout(() => {
                    card.style.opacity = '1';
                    card.style.transform = 'translateY(0)';
                }, index * 100);
            });
            
            // Set dashboard as default view
            showSection('dashboard');
        });

        // Navigation between sections
        function showSection(sectionId) {
            // Hide all feature UIs and show dashboard sections by default
            document.querySelectorAll('.feature-ui').forEach(ui => {
                ui.style.display = 'none';
            });
            
            // Update page title
            const pageTitle = document.getElementById('pageTitle');
            
            // Handle each section
            if (sectionId === 'dashboard') {
                // Show dashboard
                document.getElementById('dashboardSections').classList.remove('hidden');
                pageTitle.textContent = 'Document Request Payments';
            } else {
                // Hide dashboard and show the selected feature
                document.getElementById('dashboardSections').classList.add('hidden');
                
                if (sectionId === 'processPayment') {
                    document.getElementById('processPaymentUI').style.display = 'block';
                    pageTitle.textContent = 'Process Payment';
                } else if (sectionId === 'documentRequests') {
                    document.getElementById('documentRequestsUI').style.display = 'block';
                    pageTitle.textContent = 'Document Requests';
                } else if (sectionId === 'transactions') {
                    document.getElementById('transactionsUI').style.display = 'block';
                    pageTitle.textContent = 'System Logs';
                } else if (sectionId === 'dailySummary') {
                    document.getElementById('dailySummaryUI').style.display = 'block';
                    pageTitle.textContent = 'Daily Summary';
                } else if (sectionId === 'exportRecords') {
                    document.getElementById('exportRecordsUI').style.display = 'block';
                    pageTitle.textContent = 'Export Records';
                }
            }
            
            // Update active menu item
            document.querySelectorAll('.menu-link').forEach(link => {
                link.classList.remove('active');
                if (link.getAttribute('data-section') === sectionId) {
                    link.classList.add('active');
                }
            });
        }

        // Add click event listeners to menu items
        document.querySelectorAll('.menu-link').forEach(link => {
            link.addEventListener('click', function(e) {
                e.preventDefault();
                const section = this.getAttribute('data-section');
                showSection(section);
            });
        });
        
        // Report tab switching
        document.querySelectorAll('.report-tab').forEach(tab => {
            tab.addEventListener('click', function() {
                // Remove active class from all tabs
                document.querySelectorAll('.report-tab').forEach(t => {
                    t.classList.remove('active');
                });
                
                // Add active class to clicked tab
                this.classList.add('active');
                
                // You can add logic here to show different report content
                // based on which tab was clicked
            });
        });

        // Prepare approved requests data for JS
        const approvedRequests = JSON.parse('{!! json_encode($approved_requests) !!}');
        const fees = JSON.parse('{!! json_encode($fees) !!}');

        function formatAmount(request) {
            let amount = 0;
            if (request.requested_documents) {
                request.requested_documents.forEach(doc => {
                    amount += (fees[doc.document_type] ?? 250) * doc.quantity;
                });
            }
            return amount;
        }

        // Search/filter logic
        const searchInput = document.getElementById('paymentSearchInput');
        const searchResults = document.getElementById('paymentSearchResults');
        const referenceInput = document.getElementById('referenceNumberInput');
        const studentNameInput = document.getElementById('studentNameInput');
        const documentTypeInput = document.getElementById('documentTypeInput');
        const amountDueInput = document.getElementById('amountDueInput');
        const amountReceivedInput = document.getElementById('amountReceivedInput');
        const changeInput = document.getElementById('changeInput');
        let selectedRequest = null;

        searchInput.addEventListener('input', function() {
            const query = this.value.toLowerCase();
            if (!query) {
                searchResults.style.display = 'none';
                searchResults.innerHTML = '';
                return;
            }
            // Exclude requests with payment_status 'paid'
            const matches = approvedRequests.filter(req =>
                req.payment_status !== 'paid' && (
                    req.reference_number.toLowerCase().includes(query) ||
                    req.student_id?.toLowerCase().includes(query) ||
                    (req.first_name + ' ' + req.last_name).toLowerCase().includes(query)
                )
            );
            if (matches.length === 0) {
                searchResults.style.display = 'none';
                searchResults.innerHTML = '';
                return;
            }
            searchResults.innerHTML = matches.map(req =>
                `<div class='search-result-item' style='padding:8px; cursor:pointer;' data-ref='${req.reference_number}'>
                    <strong>${req.reference_number}</strong> - ${req.first_name} ${req.last_name} (${req.student_id || ''})
                </div>`
            ).join('');
            searchResults.style.display = 'block';
        });

        searchResults.addEventListener('click', function(e) {
            const item = e.target.closest('.search-result-item');
            if (item) {
                const ref = item.getAttribute('data-ref');
                selectRequestByReference(ref);
                searchResults.style.display = 'none';
                searchInput.value = ref;
            }
        });

        referenceInput.addEventListener('change', function() {
            selectRequestByReference(this.value);
        });

        amountReceivedInput.addEventListener('input', function() {
            const amountDue = parseFloat((amountDueInput.value || '').replace(/[^\d.]/g, '')) || 0;
            const received = parseFloat(this.value) || 0;
            const change = received - amountDue;
            changeInput.value = '₱' + (change > 0 ? change.toLocaleString(undefined, {minimumFractionDigits: 2, maximumFractionDigits: 2}) : '0.00');
        });

        function selectRequestByReference(ref) {
            const req = approvedRequests.find(r => r.reference_number === ref);
            selectedRequest = req;
            if (req) {
                referenceInput.value = req.reference_number;
                studentNameInput.value = req.first_name + ' ' + req.last_name;
                documentTypeInput.value = req.requested_documents.map(doc => `${doc.document_type} (${doc.quantity})`).join(', ');
                amountDueInput.value = '₱' + formatAmount(req).toLocaleString();
            } else {
                studentNameInput.value = '';
                documentTypeInput.value = '';
                amountDueInput.value = '';
            }
        }

        // Process Payment button functionality
        const processPaymentBtn = document.getElementById('processPaymentBtn');
        processPaymentBtn.addEventListener('click', function(e) {
            e.preventDefault();
            if (!selectedRequest) {
                alert('Please select a valid reference number.');
                return;
            }
            const amountReceived = parseFloat(amountReceivedInput.value) || 0;
            const amountDue = parseFloat((amountDueInput.value || '').replace(/[^-\u007F]/g, '')) || 0;
            if (amountReceived < amountDue) {
                alert('Amount received is less than amount due.');
                return;
            }
            fetch('/process-payment', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify({
                    reference_number: selectedRequest.reference_number,
                    amount_received: amountReceived
                })
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    alert('Payment processed and email sent!');
                    // Update status in Document Requests table if present
                    const row = document.querySelector(`#documentRequestsUI tr[data-reference='${selectedRequest.reference_number}']`);
                    if (row) {
                        const statusCell = row.querySelector('td:nth-child(5) span');
                        if (statusCell) {
                            statusCell.textContent = 'Paid';
                            statusCell.className = 'status-badge status-paid';
                        }
                    }
                    // Clear all Process Payment sidebar input fields
                    referenceInput.value = '';
                    studentNameInput.value = '';
                    documentTypeInput.value = '';
                    amountDueInput.value = '';
                    amountReceivedInput.value = '';
                    changeInput.value = '₱0.00';
                    selectedRequest = null;
                    // Optionally update UI or reload
                    // window.location.reload();
                } else {
                    alert('Failed to process payment: ' + (data.message || 'Unknown error'));
                }
            })
            .catch(() => alert('Failed to process payment.'));
        });

        // Hide search results on click outside
        window.addEventListener('click', function(e) {
            if (!searchResults.contains(e.target) && e.target !== searchInput) {
                searchResults.style.display = 'none';
            }
        });
    </script>
</body>
</html>