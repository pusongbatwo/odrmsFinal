<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Registrar Dashboard</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --primary: #8B0000;
            --primary-light: #A52A2A;
            --secondary: #D4AF37;
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

        .sidebar.collapsed {
            width: 80px;
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

        .sidebar.collapsed .logo-text {
            display: none;
        }

        .toggle-btn {
            background: none;
            border: none;
            font-size: 18px;
            cursor: pointer;
            color: var(--light);
            opacity: 0.8;
            transition: opacity 0.2s;
        }

        .toggle-btn:hover {
            opacity: 1;
        }

        .sidebar.collapsed .toggle-btn {
            transform: rotate(180deg);
            margin: 0 auto;
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
            letter-spacing: 0.5px;
        }

        .sidebar.collapsed .menu-title {
            display: none;
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
            border-radius: 0 4px 4px 0;
        }

        .menu-icon {
            font-size: 20px;
            margin-right: 12px;
            width: 24px;
            text-align: center;
            color: var(--secondary);
        }

        .sidebar.collapsed .menu-text {
            display: none;
        }

        .sidebar.collapsed .menu-link {
            justify-content: center;
            padding: 12px 0;
        }

        .sidebar.collapsed .menu-icon {
            margin-right: 0;
            font-size: 22px;
        }

        .menu-badge {
            margin-left: auto;
            background: var(--secondary);
            color: var(--primary);
            font-size: 10px;
            font-weight: 600;
            padding: 2px 6px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            min-width: 18px;
            height: 18px;
            transition: all 0.3s ease;
        }

        .menu-badge.updating {
            transform: scale(1.2);
            background: var(--danger);
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

        .sidebar.collapsed .user-details {
            display: none;
        }

        .sidebar.collapsed .user-profile {
            justify-content: center;
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
            margin-bottom: 2px;
            color: var(--light);
        }

        .user-role {
            font-size: 12px;
            color: rgba(255, 255, 255, 0.6);
        }

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
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
            width: 300px;
            border: 1px solid var(--gray);
        }

        .search-bar input {
            border: none;
            outline: none;
            flex-grow: 1;
            padding: 4px 8px;
            font-size: 14px;
            background: transparent;
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

        .notification-icon, .message-icon {
            font-size: 20px;
            color: var(--gray-dark);
            position: relative;
            cursor: pointer;
            transition: color 0.2s;
        }

        .notification-icon:hover, .message-icon:hover {
            color: var(--primary);
        }

        .notification-badge, .message-badge {
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

        .message-badge {
            background: var(--secondary);
            color: var(--primary);
        }

        .dashboard-content {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
            gap: 1.5rem;
            margin-bottom: 1.5rem;
        }

        .analytics-card {
            background: var(--light);
            border-radius: 12px;
            padding: 1.5rem;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            border-top: 3px solid var(--primary);
        }

        .analytics-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.1);
        }

        .card-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
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
            background: rgba(139, 0, 0, 0.1);
            color: var(--primary);
        }

        .card-icon.pending {
            background: rgba(255, 193, 7, 0.1);
            color: var(--warning);
        }

        .card-icon.processed {
            background: rgba(139, 0, 0, 0.1);
            color: var(--primary);
        }

        .card-icon.completed {
            background: rgba(76, 175, 80, 0.1);
            color: var(--success);
        }

        .card-icon.rejected {
            background: rgba(244, 67, 54, 0.1);
            color: var(--danger);
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

        .card-change.negative {
            color: var(--danger);
        }

        .progress-card {
            background: var(--light);
            border-radius: 12px;
            padding: 1.5rem;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
            text-align: center;
            transition: transform 0.3s ease;
            border-top: 3px solid var(--secondary);
            position: relative;
        }

        .progress-card:hover {
            transform: translateY(-5px);
        }

        .progress-container {
            position: relative;
            width: 120px;
            height: 120px;
            margin: 0 auto 1rem;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .progress-svg {
            position: absolute;
            top: 0;
            left: 0;
            width: 120px;
            height: 120px;
            transform: rotate(-90deg);
            z-index: 1;
        }

        .circular-progress {
            position: relative;
            width: 100px;
            height: 100px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: #fff;
            border-radius: 50%;
            z-index: 2;
            margin: 0 auto;
            box-shadow: 0 2px 8px rgba(0,0,0,0.03);
        }

        .progress-number {
            position: relative;
            font-size: 24px;
            font-weight: 600;
            color: var(--primary);
            z-index: 3;
            text-align: center;
            width: 100%;
        }

        .progress-title {
            font-size: 16px;
            font-weight: 500;
            margin-bottom: 8px;
            color: var(--primary);
        }

        .progress-subtitle {
            font-size: 12px;
            color: var(--gray-dark);
        }

        .recent-requests {
            background: var(--light);
            border-radius: 12px;
            padding: 1.5rem;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
            margin-top: 1.5rem;
            border-top: 3px solid var(--primary);
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
            display: flex;
            align-items: center;
            gap: 4px;
            transition: color 0.2s;
        }

        .view-all:hover {
            color: var(--secondary);
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
            letter-spacing: 0.5px;
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

        .status-processing {
            background: rgba(139, 0, 0, 0.1);
            color: var(--primary);
        }

        .status-completed {
            background: rgba(76, 175, 80, 0.1);
            color: var(--success);
        }

        .status-rejected {
            background: rgba(244, 67, 54, 0.1);
            color: var(--danger);
        }

        .action-btn {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 8px 16px;
            border: none;
            border-radius: 6px;
            font-size: 14px;
            font-weight: 500;
            cursor: pointer;
            transition: background 0.2s, color 0.2s, box-shadow 0.2s;
            box-shadow: 0 2px 6px rgba(0,0,0,0.04);
        }

        .approve-btn {
            background: #4CAF50;
            color: #fff;
        }

        .approve-btn:hover {
            background: #388E3C;
        }

        .reject-btn {
            background: #F44336;
            color: #fff;
            margin-left: 8px;
        }

        .reject-btn:hover {
            background: #B71C1C;
        }

        .progress-svg {
            position: absolute;
            width: 120px;
            height: 120px;
            transform: rotate(-90deg);
        }

        .progress-circle {
            fill: none;
            stroke-width: 8;
            stroke-linecap: round;
            stroke-dasharray: 314;
            stroke-dashoffset: 314;
            animation: progress-animation 1.5s ease-in-out forwards;
        }

        .progress-pending {
            stroke: var(--warning);
        }

        .progress-processing {
            stroke: var(--primary);
        }

        .progress-completed {
            stroke: var(--success);
        }

        .progress-rejected {
            stroke: var(--danger);
        }

        @keyframes progress-animation {
            to {
                stroke-dashoffset: var(--dash-offset);
            }
        }

        @media (max-width: 1200px) {
            .sidebar {
                width: 80px;
            }

            .sidebar.collapsed {
                width: 0;
                overflow: hidden;
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

            .user-actions {
                width: 100%;
                justify-content: flex-end;
            }
        }
        .empty-state {
            padding: 2rem;
            text-align: center;
        }
        .status-badge {
            padding: 0.25rem 0.5rem;
            border-radius: 0.25rem;
            font-size: 0.875rem;
            font-weight: 500;
        }
        .status-pending { background-color: #FEF3C7; color: #92400E; }
        .status-processing { background-color: #DBEAFE; color: #1E40AF; }
        .status-completed { background-color: #D1FAE5; color: #065F46; }
        .status-unknown { background-color: #F3F4F6; color: #6B7280; }

        /* New styles for feature UIs */
        .feature-ui {
            display: none;
            margin-top: 1.5rem;
        }
        
        .feature-ui.active {
            display: block;
        }
        
        .document-actions, .student-actions, .report-actions {
            display: flex;
            align-items: center;
            margin-bottom: 1.5rem;
            gap: 1rem;
        }
        
        .action-btn.primary {
            background: var(--primary);
            color: white;
            padding: 8px 16px;
            border-radius: 6px;
            border: none;
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: 8px;
        }
        
        .action-btn.secondary {
            background: var(--gray);
            color: var(--dark);
            padding: 8px 16px;
            border-radius: 6px;
            border: none;
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: 8px;
        }
        
        .filter-tabs {
            display: flex;
            gap: 8px;
            margin-bottom: 1rem;
        }
        
        .filter-tab {
            padding: 8px 16px;
            background: var(--gray);
            border: none;
            border-radius: 6px;
            cursor: pointer;
        }
        
        .filter-tab.active {
            background: var(--primary);
            color: white;
        }
        
        .filter-options {
            display: flex;
            gap: 1rem;
            margin-bottom: 1.5rem;
        }
        
        .filter-select, .filter-date {
            padding: 8px 12px;
            border-radius: 6px;
            border: 1px solid var(--gray);
        }
        

        
        .report-tabs {
            display: flex;
            gap: 8px;
            margin-bottom: 1.5rem;
        }
        
        .report-tab {
            padding: 8px 16px;
            background: var(--gray);
            border: none;
            border-radius: 6px;
            cursor: pointer;
        }
        
        .report-tab.active {
            background: var(--primary);
            color: white;
        }
        
        .report-charts {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 1.5rem;
            margin-bottom: 1.5rem;
        }
        
        .chart-container {
            background: white;
            padding: 1rem;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.05);
        }
        
        .chart-container h4 {
            margin-bottom: 1rem;
            color: var(--primary);
        }
        
        .date-range-picker {
            display: flex;
            align-items: center;
            gap: 8px;
        }

        /* Dashboard sections that need to be hidden */
        .dashboard-sections {
            display: block;
        }
        
        .dashboard-sections.hidden {
            display: none;
        }

        /* Import Button Styles */
        .import-btn {
            background: var(--primary);
            color: white;
            padding: 8px 16px;
            border-radius: 6px;
            border: none;
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: 8px;
            margin-right: 10px;
            transition: background-color 0.2s;
        }

        .import-btn:hover {
            background: var(--primary-light);
        }

        .import-modal {
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

        .import-modal-content {
            background: white;
            border-radius: 8px;
            padding: 1.5rem;
            width: 500px;
            max-width: 90%;
        }

        .import-modal-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1rem;
        }

        .import-modal-title {
            font-size: 1.25rem;
            font-weight: 600;
            color: var(--primary);
        }

        .import-modal-close {
            background: none;
            border: none;
            font-size: 1.5rem;
            cursor: pointer;
            color: var(--gray-dark);
        }

        .import-modal-body {
            margin: 1.5rem 0;
        }

        .file-upload {
            border: 2px dashed var(--gray);
            border-radius: 8px;
            padding: 2rem;
            text-align: center;
            margin-bottom: 1rem;
        }

        .file-upload-input {
            display: none;
        }

        .file-upload-label {
            display: block;
            cursor: pointer;
        }

        .file-upload-icon {
            font-size: 2rem;
            color: var(--primary);
            margin-bottom: 0.5rem;
        }

        .file-upload-text {
            color: var(--gray-dark);
        }

        .file-upload-hint {
            font-size: 0.875rem;
            color: var(--gray-dark);
            margin-top: 0.5rem;
        }

        .import-modal-footer {
            display: flex;
            justify-content: flex-end;
            gap: 0.5rem;
        }

        .action-btn-group {
            display: flex;
            flex-direction: row;
            gap: 10px;
            align-items: center;
            justify-content: flex-start;
        }
        .action-btn {
            min-width: 80px;
            height: 28px;
            font-size: 12px;
            padding: 4px 10px;
            justify-content: center;
        }

        /* Department Logo Grid Consistent Image Size */
        .department-logo-img {
            width: 96px;
            height: 96px;
            object-fit: contain;
            border-radius: 16px;
            background: #f5f5f5;
            display: block;
            margin: 0 auto;
        }

        /* Custom Context Menu for Department Logo */
        #logoContextMenu {
            position: absolute;
            z-index: 2000;
            background: #fff;
            border: 1px solid #ccc;
            border-radius: 6px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.15);
            display: none;
            min-width: 180px;
            padding: 0.5rem 0;
        }
        #logoContextMenu button {
            width: 100%;
            background: none;
            border: none;
            text-align: left;
            padding: 10px 20px;
            font-size: 15px;
            color: #333;
            cursor: pointer;
            transition: background 0.2s;
        }
        #logoContextMenu button:hover {
            background: #f5f5f5;
        }

        /* Custom pagination styling for dashboard */
        .pagination {
            display: flex !important;
            gap: 8px !important;
            justify-content: flex-end !important;
            margin-top: 1rem !important;
        }
        .pagination .page-item {
            display: inline-block !important;
        }
        .pagination .page-link {
            background: var(--gray) !important;
            color: var(--dark) !important;
            border: none !important;
            border-radius: 6px !important;
            padding: 8px 16px !important;
            margin: 0 2px !important;
            font-size: 14px !important;
            font-weight: 500 !important;
            cursor: pointer !important;
            transition: background 0.2s, color 0.2s !important;
            text-decoration: none !important;
        }
        .pagination .page-link:hover {
            background: var(--primary-light) !important;
            color: #fff !important;
        }
        .pagination .active .page-link,
        .pagination .page-link.active {
            background: var(--primary) !important;
            color: #fff !important;
            border: none !important;
        }
        .pagination .disabled .page-link {
            background: var(--gray) !important;
            color: var(--gray-dark) !important;
            cursor: not-allowed !important;
        }
    </style>
</head>
<body>
    <aside class="sidebar">
        <div class="sidebar-header">
            <div class="logo">
                <div class="logo-icon">
                    <i class="fas fa-university"></i>
                </div>
                <span class="logo-text">Registrar Panel</span>
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
                    <a href="#" class="menu-link" data-section="documentRequests">
                        <i class="fas fa-file-alt menu-icon"></i>
                        <span class="menu-text">Document Requests</span>
                        <span class="menu-badge" id="pendingRequestsBadge">{{ $analytics['pending'] ?? 0 }}</span>
                    </a>
                </li>
                <li class="menu-item">
                    <a href="#" class="menu-link" data-section="studentRecords">
                        <i class="fas fa-users menu-icon"></i>
                        <span class="menu-text">Student Records</span>
                    </a>
                </li>
            </ul>
            <h3 class="menu-title">Administration</h3>
            <ul class="menu-items">
                <li class="menu-item">
                    <a href="#" class="menu-link" data-section="reports">
                        <i class="fas fa-chart-bar menu-icon"></i>
                        <span class="menu-text">Reports</span>
                    </a>
                </li>
                <li class="menu-item">
                    <a href="#" class="menu-link" data-section="systemLog">
                        <i class="fas fa-clipboard-list menu-icon"></i>
                        <span class="menu-text">System Log</span>
                    </a>
                </li>
                <li class="menu-item">
                    <a href="#" class="menu-link" data-section="liveChat">
                        <i class="fas fa-comments menu-icon"></i>
                        <span class="menu-text">Live Chat</span>
                        <span class="menu-badge" id="liveChatBadge" style="display:none;">0</span>
                    </a>
                </li>
            </ul>
        </div>
        <div class="sidebar-footer">
            <div class="user-profile">
                <img src="https://randomuser.me/api/portraits/women/45.jpg" alt="User" class="avatar">
                <div class="user-details">
                    <div class="user-name">Sarah Johnson</div>
                    <div class="user-role">Registrar Admin</div>
                </div>
            </div>
        </div>
    </aside>
    <main class="main-content">
        <div class="header">
            <div class="page-title">
                <h1 id="pageTitle">Document Request Dashboard</h1>
            </div>
            <div class="search-bar">
                <i class="fas fa-search search-icon"></i>
                <input type="text" placeholder="Search requests...">
            </div>
        </div>

        <!-- Dashboard Sections -->
        <div class="dashboard-sections" id="dashboardSections">
            <div class="dashboard-content">
                <div class="analytics-card">
                    <div class="card-header">
                        <div>
                            <div class="card-title">Pending Requests</div>
                            <div class="card-value" id="pendingRequests">{{ $analytics['pending'] ?? 0 }}</div>
                            <div class="card-change positive">
                                <i class="fas fa-arrow-up"></i> 0% from last week
                            </div>
                        </div>
                        <div class="card-icon pending">
                            <i class="fas fa-clock"></i>
                        </div>
                    </div>
                </div>
                <div class="analytics-card">
                    <div class="card-header">
                        <div>
                            <div class="card-title">Approved Requests</div>
                            <div class="card-value" id="approvedRequests">{{ $analytics['approved'] ?? 0 }}</div>
                            <div class="card-change positive">
                                <i class="fas fa-arrow-up"></i> 0% from last week
                            </div>
                        </div>
                        <div class="card-icon processed">
                            <i class="fas fa-check"></i>
                        </div>
                    </div>
                </div>
                <div class="analytics-card">
                    <div class="card-header">
                        <div>
                            <div class="card-title">Completed</div>
                            <div class="card-value" id="completedRequests">{{ $analytics['completed'] ?? 0 }}</div>
                            <div class="card-change positive">
                                <i class="fas fa-arrow-up"></i> 0% from last week
                            </div>
                        </div>
                        <div class="card-icon completed">
                            <i class="fas fa-check-circle"></i>
                        </div>
                    </div>
                </div>
                <div class="analytics-card">
                    <div class="card-header">
                        <div>
                            <div class="card-title">Rejected</div>
                            <div class="card-value" id="rejectedRequests">{{ $analytics['rejected'] ?? 0 }}</div>
                            <div class="card-change negative">
                                <i class="fas fa-arrow-down"></i> 0% from last week
                            </div>
                        </div>
                        <div class="card-icon rejected">
                            <i class="fas fa-times-circle"></i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="dashboard-content">
                <div class="progress-card">
                    <div class="progress-container">
                        <svg class="progress-svg">
                            <circle class="progress-circle progress-pending" cx="60" cy="60" r="50" style="--dash-offset: 188.4;"></circle>
                        </svg>
                        <div class="circular-progress">
                            <div class="progress-number" id="pendingRequestsProgress">{{ $analytics['pending'] ?? 0 }}</div>
                        </div>
                    </div>
                    <h3 class="progress-title">Pending Requests</h3>
                    <p class="progress-subtitle">Current active requests</p>
                </div>
                <div class="progress-card">
                    <div class="progress-container">
                        <svg class="progress-svg">
                            <circle class="progress-circle progress-processing" cx="60" cy="60" r="50" style="--dash-offset: 125.6;"></circle>
                        </svg>
                        <div class="circular-progress">
                            <div class="progress-number" id="approvedRequestsProgress">{{ $analytics['approved'] ?? 0 }}</div>
                        </div>
                    </div>
                    <h3 class="progress-title">Approved Requests</h3>
                    <p class="progress-subtitle">Requests approved by registrar</p>
                </div>
                <div class="progress-card">
                    <div class="progress-container">
                        <svg class="progress-svg">
                            <circle class="progress-circle progress-completed" cx="60" cy="60" r="50" style="--dash-offset: 219.8;"></circle>
                        </svg>
                        <div class="circular-progress">
                            <div class="progress-number" id="completedRequestsProgress">{{ $analytics['completed'] ?? 0 }}</div>
                        </div>
                    </div>
                    <h3 class="progress-title">Completed</h3>
                    <p class="progress-subtitle">This month</p>
                </div>
                <div class="progress-card">
                    <div class="progress-container">
                        <svg class="progress-svg">
                            <circle class="progress-circle progress-rejected" cx="60" cy="60" r="50" style="--dash-offset: 282.6;"></circle>
                        </svg>
                        <div class="circular-progress">
                            <div class="progress-number" id="rejectedRequestsProgress">{{ $analytics['rejected'] ?? 0 }}</div>
                        </div>
                    </div>
                    <h3 class="progress-title">Rejected</h3>
                    <p class="progress-subtitle">This month</p>
                </div>
            </div>
            <div class="recent-requests">
                <div class="section-header">
                    <h2 class="section-title">Recent Document Requests</h2>
                    <a href="#" class="view-all">
                        View All
                        <i class="fas fa-chevron-right"></i>
                    </a>
                </div>
                <table id="requestTable">
                    <thead>
                        <tr>
                            <th>Reference #</th>
                            <th>Student Name</th>
                            <th>Course</th>
                            <th>Status</th>
                            <th>Requested Documents</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($dashboardRequests as $req)
                        <tr>
                            <td>{{ $req->reference_number }}</td>
                            <td>{{ $req->first_name }} {{ $req->last_name }}</td>
                            <td>{{ $req->course }}</td>
                            <td>{{ ucfirst($req->status) }}</td>
                            <td>
                                @foreach($req->requestedDocuments as $doc)
                                    {{ $doc->document_type }} ({{ $doc->quantity }})<br>
                                @endforeach
                            </td>
                            <td>
                                @if($req->status == 'pending')
                                    <div class="action-btn-group">
                                        <button type="button" class="action-btn approve-btn verify-btn" 
                                            data-request-id="{{ $req->id }}" 
                                            data-student-id="{{ $req->student_id ?? '' }}" 
                                            data-student-name="{{ $req->first_name ?? '' }} {{ $req->last_name ?? '' }}"
                                            title="Verify">
                                            <i class="fas fa-user-check"></i> Verify
                                        </button>
                                        <form action="{{ route('registrar.complete', $req->id) }}" method="POST" style="display:inline;">
                                            @csrf
                                            <button type="submit" class="action-btn reject-btn" title="Complete">
                                                <i class="fas fa-check-double"></i> Complete
                                            </button>
                                        </form>
                                    </div>
                                @else
                                    <span>{{ ucfirst($req->status) }}</span>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Document Requests UI -->
        <div id="documentRequestsUI" class="feature-ui">
            <div class="recent-requests">
                <div class="section-header">
                    <h2 class="section-title">Document Requests Management</h2>
                </div>
                <div class="document-actions">
                    <div class="search-bar" style="width: 300px; margin-right: auto;">
                        <i class="fas fa-search search-icon"></i>
                        <input type="text" placeholder="Search documents...">
                    </div>
                </div>
                <div class="document-filters">
                    <div class="filter-tabs">
                        <button class="filter-tab active">All Requests</button>
                        <button class="filter-tab">Pending</button>
                        <button class="filter-tab">Approved</button>
                        <button class="filter-tab">Completed</button>
                        <button class="filter-tab">Rejected</button>
                    </div>
                    <div class="filter-options">
                        <select class="filter-select">
                            <option>All Document Types</option>
                            <option>Transcript</option>
                            <option>Diploma</option>
                            <option>Certificate</option>
                        </select>
                        <input type="date" class="filter-date" placeholder="Filter by date">
                    </div>
                </div>
                <table id="documentRequestsTable">
                    <thead>
                        <tr>
                            <th>Request ID</th>
                            <th>Student</th>
                            <th>Document Type(s)</th>
                            <th>Date Requested</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($requests as $req)
                        <tr
                            data-status="{{ strtolower($req->status) }}"
                            data-documents="@foreach($req->requestedDocuments as $doc){{ strtolower($doc->document_type) }},@endforeach"
                            data-date="{{ $req->created_at ? $req->created_at->format('Y-m-d') : ($req->request_date ? \Carbon\Carbon::parse($req->request_date)->format('Y-m-d') : '') }}"
                        >
                            <td>{{ $req->reference_number }}</td>
                            <td>{{ $req->first_name }} {{ $req->last_name }}</td>
                            <td>
                                @foreach($req->requestedDocuments as $doc)
                                    {{ $doc->document_type }} ({{ $doc->quantity }})<br>
                                @endforeach
                            </td>
                            <td>{{ $req->created_at ? $req->created_at->format('m/d/Y') : ($req->request_date ? \Carbon\Carbon::parse($req->request_date)->format('m/d/Y') : '') }}</td>
                            <td><span class="status-badge status-{{ strtolower($req->status) }}">{{ ucfirst($req->status) }}</span></td>
                            <td>
                                @if($req->status == 'pending')
                                    <div style="display: flex; gap: 8px; align-items: center;">
                                        <button type="button" class="action-btn approve-btn verify-btn" 
                                            data-request-id="{{ $req->id }}" 
                                            data-student-id="{{ $req->student_id ?? '' }}" 
                                            data-student-name="{{ $req->first_name ?? '' }} {{ $req->last_name ?? '' }}"
                                            title="Verify">
                                            <i class="fas fa-user-check"></i> Verify
                                        </button>
                                        <form action="{{ route('registrar.complete', $req->id) }}" method="POST" style="display:inline;">
                                            @csrf
                                            <button type="submit" class="action-btn reject-btn" title="Complete">
                                                <i class="fas fa-check-double"></i> Complete
                                            </button>
                                        </form>
                                    </div>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                <!-- Pagination Controls -->
                <div style="margin-top: 1rem; display: flex; justify-content: flex-end; gap: 8px;">
                    {{ $requests->links('pagination::bootstrap-4') }}
                </div>
            </div>
        </div>

        <!-- Student Records UI -->
        <div id="studentRecordsUI" class="feature-ui">
            <div class="recent-requests">
                <div class="section-header">
                    <h2 class="section-title">Student Records Management</h2>
                </div>
                <!-- Department Logo Grid -->
                <div id="departmentGrid" style="display: flex; flex-wrap: wrap; gap: 2rem; justify-content: center; margin-bottom: 2rem;">
                    @php
                        $departments = [
                            ["name" => "BACHELOR OF SCIENCE IN INFORMATION TECHNOLOGY", "img" => "/images/it.png"],
                            ["name" => "BACHELOR OF SCIENCE IN ENTREPRENEURSHIP", "img" => "/images/bse.png"],
                            ["name" => "BACHELOR OF SCIENCE IN CRIMINOLOGY", "img" => "/images/CRIM.png"],
                            ["name" => "BACHELOR OF ELEMENTARY EDUCATION", "img" => "/images/beed.png"],
                            ["name" => "BACHELOR OF EARLY CHILDHOOD EDUCATION", "img" => "/images/beced.png"],
                            ["name" => "BACHELOR OF SCIENCE IN HOSPITALITY MANAGEMENT", "img" => "/images/hm.png"],
                            ["name" => "BACHELOR OF PUBLIC ADMINISTRATION", "img" => "/images/BPA.jpg"],
                        ];
                    @endphp
                    @foreach($departments as $dept)
                    <div class="department-logo-card" data-department="{{ $dept['name'] }}" style="text-align: center; cursor: pointer; width: 180px; padding-bottom: 1rem;">
                        <img src="{{ $dept['img'] }}" alt="{{ $dept['name'] }}" class="department-logo-img">
                        <div style="margin-top: 0.5rem; font-weight: 600; font-size: 13px;">{{ $dept['name'] }}</div>
                    </div>
                    @endforeach
                </div>
                <!-- Student Records Table (always visible) -->
                <div id="studentRecordsTableSection" style="display:none;">
                    <div style="display:flex;align-items:center;gap:1rem;margin-bottom:1rem;">
                        <label for="schoolYearFilter" style="font-weight:600;color:#8B0000;">School Year:</label>
                        <select id="schoolYearFilter" class="filter-select" style="min-width:140px; border:1.5px solid #8B0000; border-radius:8px; padding:6px 12px; font-size:1rem;">
                            @php
                                $startYear = 2022;
                                $currentYear = date('Y');
                                $schoolYears = [];
                                for ($y = $startYear; $y <= $currentYear; $y++) {
                                    $schoolYears[] = $y . '-' . ($y+1);
                                }
                            @endphp
                            @foreach(array_reverse($schoolYears) as $sy)
                                <option value="{{ $sy }}">{{ $sy }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div style="max-height:60vh;overflow-y:auto;">
                        <table style="width:100%;border-collapse:collapse;">
                            <thead>
                                <tr style="background:#f5f5f5;">
                                    <th>Student ID</th>
                                    <th>Name</th>
                                    <th>Program</th>
                                    <th>Year Level</th>
                                    <th>School Year</th>
                                </tr>
                            </thead>
                            <tbody id="studentRecordsTableBody">
                                <!-- Populated by JS -->
                            </tbody>
                        </table>
                    </div>
                </div>
                <!-- Custom Context Menu for Department Logo -->
                <div id="logoContextMenu">
                    <button id="uploadLogoBtn">Upload Department Logo</button>
                    <button id="updateLogoBtn">Update Department Logo</button>
                </div>
                <!-- Hidden file input for logo upload -->
                <input type="file" id="logoFileInput" accept="image/*" style="display:none;">
                <!-- Student Table and Filters (hidden by default) -->

<div id="studentTableSection" style="display: none;">
    <div class="student-actions" style="display:flex;align-items:center;gap:10px;justify-content:flex-end;margin-bottom:1.5rem;">
        <button class="action-btn primary" id="openAddStudentModalBtn" style="background:#8B0000;color:#fff;font-weight:600;letter-spacing:1px;">
            <i class="fas fa-user-plus"></i> Add Student Manually
        </button>
        <button class="import-btn" id="openImportStudentModalBtn">
            <i class="fas fa-file-import"></i> Import Student Records
        </button>
        <button class="action-btn secondary" id="backToGridBtn" style="margin-left: 10px;">
            <i class="fas fa-arrow-left"></i> Back
        </button>
    </div>
</div>

<!-- Add Student Modal -->
<div id="addStudentModal" class="import-modal" style="display:none;z-index:2001;">
    <div class="import-modal-content" style="border-top:6px solid #8B0000;">
        <div class="import-modal-header">
            <h3 class="import-modal-title" style="color:#8B0000;">Add Student Record</h3>
            <button class="import-modal-close" onclick="closeAddStudentModal()">&times;</button>
        </div>
        <form method="POST" action="{{ route('students.store') }}">
            @csrf
            <div style="margin-bottom:1rem;">
                <label for="student_id" style="font-weight:600;color:#8B0000;">Student ID</label>
                <input type="text" name="student_id" id="student_id_modal" class="form-control" required style="border:1.5px solid #8B0000;border-radius:8px;">
            </div>
            <div class="row" style="margin-bottom:1rem;display:flex;flex-wrap:wrap;gap:1rem;">
                <div class="col" style="flex:1;min-width:180px;display:flex;flex-direction:column;gap:0.5rem;">
                    <label for="first_name" style="font-weight:600;color:#8B0000;">First Name</label>
                    <input type="text" name="first_name" id="first_name_modal" class="form-control" required style="border:1.5px solid #8B0000;border-radius:8px;padding:0.75rem;font-size:1rem;">
                </div>
                <div class="col" style="flex:1;min-width:180px;display:flex;flex-direction:column;gap:0.5rem;">
                    <label for="middle_name" style="font-weight:600;color:#8B0000;">Middle Name</label>
                    <input type="text" name="middle_name" id="middle_name_modal" class="form-control" style="border:1.5px solid #8B0000;border-radius:8px;padding:0.75rem;font-size:1rem;">
                </div>
                <div class="col" style="flex:1;min-width:180px;display:flex;flex-direction:column;gap:0.5rem;">
                    <label for="last_name" style="font-weight:600;color:#8B0000;">Last Name</label>
                    <input type="text" name="last_name" id="last_name_modal" class="form-control" required style="border:1.5px solid #8B0000;border-radius:8px;padding:0.75rem;font-size:1rem;">
                </div>
            </div>
            <div style="margin-bottom:1rem;display:flex;flex-direction:column;gap:0.5rem;">
                <label for="program" style="font-weight:600;color:#8B0000;">Program</label>
                <select name="program" id="program_modal" class="form-control" required style="border:1.5px solid #8B0000;border-radius:8px;padding:0.75rem;font-size:1rem;">
                    <option value="">Select Program</option>
                    <option value="BACHELOR OF SCIENCE IN INFORMATION TECHNOLOGY" {{ (isset($selectedDepartment) && $selectedDepartment == 'BACHELOR OF SCIENCE IN INFORMATION TECHNOLOGY') ? 'selected' : '' }}>BACHELOR OF SCIENCE IN INFORMATION TECHNOLOGY</option>
                    <option value="BACHELOR OF SCIENCE IN ENTREPRENEURSHIP" {{ (isset($selectedDepartment) && $selectedDepartment == 'BACHELOR OF SCIENCE IN ENTREPRENEURSHIP') ? 'selected' : '' }}>BACHELOR OF SCIENCE IN ENTREPRENEURSHIP</option>
                    <option value="BACHELOR OF SCIENCE IN CRIMINOLOGY" {{ (isset($selectedDepartment) && $selectedDepartment == 'BACHELOR OF SCIENCE IN CRIMINOLOGY') ? 'selected' : '' }}>BACHELOR OF SCIENCE IN CRIMINOLOGY</option>
                    <option value="BACHELOR OF ELEMENTARY EDUCATION" {{ (isset($selectedDepartment) && $selectedDepartment == 'BACHELOR OF ELEMENTARY EDUCATION') ? 'selected' : '' }}>BACHELOR OF ELEMENTARY EDUCATION</option>
                    <option value="BACHELOR OF EARLY CHILDHOOD EDUCATION" {{ (isset($selectedDepartment) && $selectedDepartment == 'BACHELOR OF EARLY CHILDHOOD EDUCATION') ? 'selected' : '' }}>BACHELOR OF EARLY CHILDHOOD EDUCATION</option>
                    <option value="BACHELOR OF SCIENCE IN HOSPITALITY MANAGEMENT" {{ (isset($selectedDepartment) && $selectedDepartment == 'BACHELOR OF SCIENCE IN HOSPITALITY MANAGEMENT') ? 'selected' : '' }}>BACHELOR OF SCIENCE IN HOSPITALITY MANAGEMENT</option>
                    <option value="BACHELOR OF PUBLIC ADMINISTRATION" {{ (isset($selectedDepartment) && $selectedDepartment == 'BACHELOR OF PUBLIC ADMINISTRATION') ? 'selected' : '' }}>BACHELOR OF PUBLIC ADMINISTRATION</option>
                </select>
            </div>
            <div style="margin-bottom:1rem;">
                <label for="year_level" style="font-weight:600;color:#8B0000;">Year Level</label>
                <select name="year_level" id="year_level_modal" class="form-control" required style="border:1.5px solid #8B0000;border-radius:8px;">
                    <option value="">Select Year Level</option>
                    <option value="1st Year">1st Year</option>
                    <option value="2nd Year">2nd Year</option>
                    <option value="3rd Year">3rd Year</option>
                    <option value="4th Year">4th Year</option>
                </select>
            </div>
            <div style="margin-bottom:1rem;">
                <label for="school_year" style="font-weight:600;color:#8B0000;">School Year</label>
                <select name="school_year" id="school_year_modal" class="form-control" required style="border:1.5px solid #8B0000;border-radius:8px;">
                    @php
                        $startYear = 2022;
                        $currentYear = date('Y');
                        $schoolYears = [];
                        for ($y = $startYear; $y <= $currentYear; $y++) {
                            $schoolYears[] = $y . '-' . ($y+1);
                        }
                    @endphp
                    @foreach(array_reverse($schoolYears) as $sy)
                        <option value="{{ $sy }}">{{ $sy }}</option>
                    @endforeach
                </select>
            </div>
            <div style="margin-bottom:1.5rem;">
                <label for="status" style="font-weight:600;color:#8B0000;">Status</label>
                <select name="status" id="status_modal" class="form-control" required style="border:1.5px solid #8B0000;border-radius:8px;">
                    <option value="active">Active</option>
                    <option value="on leave">On Leave</option>
                    <option value="graduated">Graduated</option>
                    <option value="dropped">Dropped</option>
                </select>
            </div>
            <button type="submit" class="btn btn-primary" style="width:100%;padding:0.75rem 0;font-size:1rem;background:#8B0000;color:#fff;border:none;border-radius:8px;font-weight:600;letter-spacing:1px;box-shadow:0 2px 8px rgba(139,0,0,0.10);transition:background 0.2s;">Add Student</button>
        </form>
    </div>
</div>


<!-- SweetAlert2 CDN -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

@if(session('student_added'))
    <script>
        Swal.fire({
            icon: 'success',
            title: 'Student Added!',
            text: "{{ session('student_added') }}",
            confirmButtonColor: '#8B0000',
        });
    </script>
@endif

@if(session('success'))
    <script>
        Swal.fire({
            icon: 'success',
            title: 'Request Approved!',
            text: "{{ session('success') }}",
            confirmButtonColor: '#8B0000',
        });
    </script>
@endif

<!-- Import Student Modal (reuse existing import modal) -->
<!-- The import modal already exists as #importModal -->

<script>
  // Student Records Table Logic (robust show/hide and filtering)
document.addEventListener('DOMContentLoaded', function() {
    let students = @json($students);
    let selectedDepartment = null;
    let selectedSchoolYear = document.getElementById('schoolYearFilter').value;
    const tableSection = document.getElementById('studentRecordsTableSection');
    const tableBody = document.getElementById('studentRecordsTableBody');
    const departmentGrid = document.getElementById('departmentGrid');
    const schoolYearFilter = document.getElementById('schoolYearFilter');
    const backBtn = document.getElementById('backToGridBtn');

    // Helper: flatten all students for a department
    function getAllStudentsForDepartment(dept) {
        let all = [];
        if (students[dept]) {
            Object.values(students[dept]).forEach(function(records) {
                if (Array.isArray(records)) {
                    all = all.concat(records);
                }
            });
        }
        return all;
    }

    // Render table for selected department, optionally filtered by school year
    function renderTable(filterBySchoolYear = false) {
        if (!selectedDepartment) {
            tableSection.style.display = 'none';
            return;
        }
        tableSection.style.display = 'block';
        tableBody.innerHTML = '';
        let records = [];
        if (filterBySchoolYear && students[selectedDepartment] && students[selectedDepartment][selectedSchoolYear]) {
            records = students[selectedDepartment][selectedSchoolYear];
        } else {
            records = getAllStudentsForDepartment(selectedDepartment);
        }
        if (!records || records.length === 0) {
            tableBody.innerHTML = '<tr><td colspan="5" style="text-align:center;color:#888;">No records found.</td></tr>';
            return;
        }
        records.forEach(function(student) {
            let schoolYearDisplay = student.school_year || (student.school_years ? student.school_years.join(', ') : selectedSchoolYear);
            let row = document.createElement('tr');
            row.innerHTML = `
                <td>${student.student_id || ''}</td>
                <td>${student.first_name || ''} ${student.last_name || ''}</td>
                <td>${student.program || ''}</td>
                <td>${student.year_level || ''}</td>
                <td>${schoolYearDisplay}</td>
            `;
            tableBody.appendChild(row);
        });
    }

    // Department selection
    if (departmentGrid) {
        departmentGrid.querySelectorAll('.department-logo-card').forEach(function(card) {
            card.addEventListener('click', function() {
                selectedDepartment = card.getAttribute('data-department');
                renderTable(false); // Show all students for department
            });
        });
    }

    // School year filter (optional: add a button to filter by year)
    if (schoolYearFilter) {
        schoolYearFilter.addEventListener('change', function() {
            selectedSchoolYear = this.value;
            renderTable(true); // Filter by school year
        });
    }

    // Back button logic
    if (backBtn) {
        backBtn.addEventListener('click', function() {
            selectedDepartment = null;
            tableSection.style.display = 'none';
        });
    }

    // Initial state: table hidden
    tableSection.style.display = 'none';
});

// Modal logic for Add Student
function openAddStudentModal() {
    var modal = document.getElementById('addStudentModal');
    if (modal) modal.style.display = 'flex';
}
function closeAddStudentModal() {
    var modal = document.getElementById('addStudentModal');
    if (modal) modal.style.display = 'none';
}
var addBtn = document.getElementById('openAddStudentModalBtn');
if (addBtn) addBtn.addEventListener('click', openAddStudentModal);
// Modal logic for Import Student
var importBtn = document.getElementById('openImportStudentModalBtn');
if (importBtn) importBtn.addEventListener('click', function() {
    var importModal = document.getElementById('importModal');
    if (importModal) importModal.style.display = 'flex';
});
</script>
            </div>
        </div>

        <!-- Import Records Modal -->
        <div id="importModal" class="import-modal">
            <div class="import-modal-content">
                <div class="import-modal-header">
                    <h3 class="import-modal-title">Import Student Records</h3>
                    <button class="import-modal-close" onclick="closeImportModal()">&times;</button>
                </div>
                <div class="import-modal-body">
                    <div class="file-upload">
                        <input type="file" id="fileInput" class="file-upload-input" accept=".csv, .xlsx, .xls">
                        <label for="fileInput" class="file-upload-label">
                            <div class="file-upload-icon">
                                <i class="fas fa-cloud-upload-alt"></i>
                            </div>
                            <div class="file-upload-text">Click to upload or drag and drop</div>
                            <div class="file-upload-hint">CSV, XLSX (Max. 10MB)</div>
                        </label>
                    </div>
                    <div id="fileInfo" style="display: none;">
                        <p>Selected file: <span id="fileName"></span></p>
                    </div>
                </div>
                <div class="import-modal-footer">
                    <button class="btn btn-secondary" onclick="closeImportModal()">Cancel</button>
                    <button class="btn btn-primary" onclick="importFile()">Import</button>
                </div>
            </div>
        </div>



        <!-- Reports UI -->
        <div id="reportsUI" class="feature-ui">
            <div class="recent-requests">
                <div class="section-header">
                    <h2 class="section-title">System Reports</h2>
                    <div class="report-actions">
                        <button class="action-btn primary">
                            <i class="fas fa-file-export"></i> Export Report
                        </button>
                        <div class="date-range-picker">
                            <input type="date" class="filter-date">
                            <span>to</span>
                            <input type="date" class="filter-date">
                        </div>
                    </div>
                </div>
                <div class="report-tabs">
                    <button class="report-tab active">Document Requests</button>
                    <button class="report-tab">Student Records</button>
                    <button class="report-tab">User Activity</button>
                </div>
                <div class="report-content">
                    <div class="report-filters">
                        <select class="filter-select">
                            <option>All Document Types</option>
                            <option>Transcript</option>
                            <option>Diploma</option>
                        </select>
                        <select class="filter-select">
                            <option>All Statuses</option>
                            <option>Pending</option>
                            <option>Completed</option>
                        </select>
                    </div>
                    <div class="report-charts">
                        <div class="chart-container">
                            <h4>Requests by Month</h4>
                            <div class="chart-placeholder" style="height: 300px; background: #f5f5f5; display: flex; align-items: center; justify-content: center;">
                                [Chart: Document Requests by Month]
                            </div>
                        </div>
                        <div class="chart-container">
                            <h4>Requests by Type</h4>
                            <div class="chart-placeholder" style="height: 300px; background: #f5f5f5; display: flex; align-items: center; justify-content: center;">
                                [Chart: Document Requests by Type]
                            </div>
                        </div>
                    </div>
                    <table>
                        <thead>
                            <tr>
                                <th>Report</th>
                                <th>Period</th>
                                <th>Generated</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>Document Requests - May 2023</td>
                                <td>05/01/2023 - 05/31/2023</td>
                                <td>06/02/2023</td>
                                <td>
                                    <button class="action-btn"><i class="fas fa-download"></i></button>
                                    <button class="action-btn"><i class="fas fa-print"></i></button>
                                </td>
                            </tr>
                            <tr>
                                <td>Student Records - Spring 2023</td>
                                <td>01/01/2023 - 05/31/2023</td>
                                <td>06/05/2023</td>
                                <td>
                                    <button class="action-btn"><i class="fas fa-download"></i></button>
                                    <button class="action-btn"><i class="fas fa-print"></i></button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- System Log UI -->
        <div id="systemLogUI" class="feature-ui">
            <div class="recent-requests">
                <div class="section-header">
                    <h2 class="section-title">System Log</h2>
                </div>
                <div class="system-log-list">
                    @if(isset($systemLogs) && count($systemLogs) > 0)
                        @foreach($systemLogs as $log)
                        <div class="log-item">
                            <div class="log-icon">
                                @if($log->type === 'login')
                                    <i class="fas fa-sign-in-alt" style="color:#1E40AF;"></i>
                                @elseif($log->type === 'request_received')
                                    <i class="fas fa-inbox" style="color:#92400E;"></i>
                                @elseif($log->type === 'approved')
                                    <i class="fas fa-check-circle" style="color:#065F46;"></i>
                                @elseif($log->type === 'rejected')
                                    <i class="fas fa-times-circle" style="color:#B71C1C;"></i>
                                @elseif($log->type === 'student_added')
                                    <i class="fas fa-user-plus" style="color:#8B0000;"></i>
                                @else
                                    <i class="fas fa-info-circle"></i>
                                @endif
                            </div>
                            <div class="log-content">
                                <div style="font-weight:600;">{{ $log->message }}</div>
                                <div style="font-size:12px;color:#888;">{{ $log->created_at->format('Y-m-d H:i:s') }}</div>
                            </div>
                        </div>
                        @endforeach
                    @else
                        <div class="empty-state">No system log entries yet.</div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Live Chat/Inquiries UI -->
        <div id="liveChatUI" class="feature-ui">
            <div style="display:flex;gap:0;height:600px;max-height:70vh;background:#fff;border-radius:12px;box-shadow:0 2px 12px rgba(0,0,0,0.08);overflow:hidden;">
                <!-- Conversation List -->
                <div id="chat-convo-list" style="width:260px;background:#f9f9f9;border-right:1px solid #eee;overflow-y:auto;">
                    <div style="padding:18px 16px 10px;font-weight:600;font-size:17px;color:#8B0000;border-bottom:1px solid #eee;">Conversations</div>
                    <div id="convo-list-items" style="display:flex;flex-direction:column;"></div>
                </div>
                <!-- Chat Messages -->
                <div style="flex:1;display:flex;flex-direction:column;">
                    <div id="chat-header" style="background:linear-gradient(135deg,#8B0000,#6B0000);color:#fff;padding:14px 20px;font-weight:600;font-size:16px;display:flex;align-items:center;gap:10px;min-height:54px;">
                        <i class="fas fa-user-circle"></i>
                        <span id="chat-header-title">Select a conversation</span>
                    </div>
                    <div id="chat-messages" style="flex:1;overflow-y:auto;padding:18px 12px 0 12px;display:flex;flex-direction:column;gap:10px;background:#fff;"></div>
                    <form id="chat-form" style="display:flex;gap:8px;padding:10px 12px 10px 12px;border-top:1px solid #eee;background:#fafafa;" autocomplete="off">
                        <input id="chat-input" type="text" placeholder="Type your message..." style="flex:1;padding:10px 14px;border-radius:20px;border:1px solid #eee;background:#f9f9f9;outline:none;" autocomplete="off" disabled />
                        <button type="submit" style="background:#8B0000;color:#fff;border:none;border-radius:50%;width:40px;height:40px;display:flex;align-items:center;justify-content:center;cursor:pointer;transition:background 0.2s;" disabled><i class="fas fa-paper-plane"></i></button>
                    </form>
                </div>
            </div>
            <script>
            // --- Registrar Live Chat Logic ---
            document.addEventListener('DOMContentLoaded', function() {
                let selectedConvoId = null;
                const convoList = document.getElementById('convo-list-items');
                const chatHeaderTitle = document.getElementById('chat-header-title');
                const chatMessages = document.getElementById('chat-messages');
                const chatForm = document.getElementById('chat-form');
                const chatInput = document.getElementById('chat-input');
                const sendBtn = chatForm.querySelector('button');
                const liveChatBadge = document.getElementById('liveChatBadge');

                function fetchConversations() {
                    fetch('/api/registrar/conversations', { headers: { 'Accept': 'application/json' } })
                        .then(res => res.json())
                        .then(data => {
                            convoList.innerHTML = '';
                            let unreadCount = 0;
                            data.forEach(convo => {
                                const item = document.createElement('div');
                                item.className = 'convo-list-item';
                                item.style = 'padding:14px 16px;cursor:pointer;display:flex;align-items:center;gap:10px;border-bottom:1px solid #eee;background:' + (selectedConvoId===convo.id?'#f5e7c1':'#fff') + ';';
                                item.innerHTML = `<div style='flex:1;'><div style='font-weight:600;color:#8B0000;'>${convo.requester_name||'Requester'}</div><div style='font-size:13px;color:#666;'>${convo.last_message?convo.last_message.substring(0,32):''}</div></div>` + (convo.unread_count>0?`<span style='background:#D4AF37;color:#8B0000;font-size:11px;font-weight:700;padding:2px 8px;border-radius:10px;'>${convo.unread_count}</span>`:'');
                                item.onclick = () => selectConversation(convo.id, convo.requester_name);
                                convoList.appendChild(item);
                                if(convo.unread_count>0) unreadCount += convo.unread_count;
                            });
                            liveChatBadge.style.display = unreadCount>0?'inline-flex':'none';
                            liveChatBadge.textContent = unreadCount;
                        });
                }

                function selectConversation(convoId, requesterName) {
                    selectedConvoId = convoId;
                    chatHeaderTitle.textContent = requesterName ? `Chat with ${requesterName}` : 'Chat';
                    chatInput.disabled = false;
                    sendBtn.disabled = false;
                    fetchMessages();
                    fetchConversations();
                }

                function renderMessages(messages) {
                   

                function fetchMessages() {
                    if(!selectedConvoId) return;
                    fetch(`/api/registrar/conversations/${selectedConvoId}/messages`, { headers: { 'Accept': 'application/json' } })
                        .then(res => res.json())
                        .then(renderMessages);
                }

                chatForm.addEventListener('submit', function(e) {
                    e.preventDefault();
                    if(!selectedConvoId) return;
                    const msg = chatInput.value.trim();
                    if(!msg) return;
                    fetch(`/api/registrar/conversations/${selectedConvoId}/messages`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        },
                        body: JSON.stringify({ message: msg })
                    })
                    .then(res => res.json())
                    .then(() => {
                        chatInput.value
                        fetchMessages();
                        fetchConversations();
                    });
                });

                // Poll for new messages/convos every 10s
                setInterval(() => {
                    fetchConversations();
                    if(selectedConvoId) fetchMessages();
                }, 10000);

                // Section navigation logic
                document.querySelectorAll('.menu-link').forEach(link => {
                    link.addEventListener('click', function(e) {
                        e.preventDefault();
                        document.querySelectorAll('.feature-ui').forEach(ui => ui.classList.remove('active'));
                        const section = this.getAttribute('data-section');
                        if(section) document.getElementById(section+"UI").classList.add('active');
                        document.querySelectorAll('.menu-link').forEach(l => l.classList.remove('active'));
                        this.classList.add('active');
                        if(section==='liveChat') {
                            fetchConversations();
                            chatHeaderTitle.textContent = 'Select a conversation';
                            chatInput.value = '';
                            chatInput.disabled = true;
                            sendBtn.disabled = true;
                            chatMessages.innerHTML = '';
                        }
                    });
                });

                // Initial fetch
                fetchConversations();
            });
            </script>
        </div>
    </main>
    <script>
        document.querySelector('.toggle-btn').addEventListener('click', function() {
            document.querySelector('.sidebar').classList.toggle('collapsed');
        });
        function setCircularProgress(selector, percent) {
            const circle = document.querySelector(selector);
            const radius = 50;
            const circumference = 2 * Math.PI * radius;
            const offset = circumference - (percent / 100) * circumference;
            circle.style.strokeDashoffset = offset;
        }
        setCircularProgress('.progress-pending', 60);
        setCircularProgress('.progress-processing', 40);
        setCircularProgress('.progress-completed', 70);
        setCircularProgress('.progress-rejected', 10);
        document.addEventListener('DOMContentLoaded', function() {
            const cards = document.querySelectorAll('.analytics-card, .progress-card');
            cards.forEach((card, index) => {
                setTimeout(() => {
                    card.style.opacity = '1';
                    card.style.transform = 'translateY(0)';
                }, index * 100);
            });
            fetchData();
        });
        async function fetchData() {
            try {
                const response = await fetch('fetch_requests.php');
                const data = await response.json();
                populateTable(data.requests);
                updateAnalytics(data.analytics);
            } catch (error) {
                console.error('Error fetching data:', error);
            }
        }
        function populateTable(requests) {
            const tableBody = document.querySelector('#requestTable tbody');
            tableBody.innerHTML = '';
            requests.forEach(request => {
                const row = document.createElement('tr');
                row.innerHTML = `
                    <td class="request-id">${request.request_id}</td>
                    <td>${request.first_name} ${request.last_name}</td>
                    <td>${request.document_type}</td>
                    <td>${request.date_requested}</td>
                    <td><span class="status-badge status-${request.status.toLowerCase()}">${request.status}</span></td>
                    <td>
                        <button class="action-btn"><i class="fas fa-eye"></i></button>
                        <button class="action-btn"><i class="fas fa-edit"></i></button>
                    </td>
                `;
                tableBody.appendChild(row);
            });
        }
        function updateAnalytics(analytics) {
            document.getElementById('pendingRequests').textContent = analytics.pending;
            document.getElementById('approvedRequests').textContent = analytics.approved;
            document.getElementById('completedRequests').textContent = analytics.completed;
            document.getElementById('rejectedRequests').textContent = analytics.rejected;
            document.getElementById('pendingRequestsProgress').textContent = analytics.pending;
            document.getElementById('approvedRequestsProgress').textContent = analytics.approved;
            document.getElementById('completedRequestsProgress').textContent = analytics.completed;
            document.getElementById('rejectedRequestsProgress').textContent = analytics.rejected;
            setCircularProgress('.progress-pending', (analytics.pending / 100) * 60);
            setCircularProgress('.progress-processing', (analytics.approved / 100) * 60);
            setCircularProgress('.progress-completed', (analytics.completed / 100) * 60);
            setCircularProgress('.progress-rejected', (analytics.rejected / 100) * 60);
        }

        // Import Modal Functions
        function openImportModal() {
            document.getElementById('importModal').style.display = 'flex';
        }

        function closeImportModal() {
            document.getElementById('importModal').style.display = 'none';
            document.getElementById('fileInfo').style.display = 'none';
            document.getElementById('fileInput').value = '';
        }

        function importFile() {
            const fileInput = document.getElementById('fileInput');
            if (fileInput.files.length === 0) {
                alert('Please select a file to import');
                return;
            }
            
            const file = fileInput.files[0];
            const fileName = file.name;
            
            // Here you would typically handle the file upload to your server
            // For this example, we'll just show a success message
            alert(`File "${fileName}" imported successfully!`);
            closeImportModal();
            
            // In a real application, you would use something like:
            /*
            const formData = new FormData();
            formData.append('file', file);
            
            fetch('/api/import/students', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                alert('Import successful!');
                closeImportModal();
                // Optionally refresh the student records table
            })
            .catch(error => {
                alert('Error during import: ' + error.message);
            });
            */
        }

        // Handle file selection display
        document.getElementById('fileInput').addEventListener('change', function(e) {
            if (this.files.length > 0) {
                const fileName = this.files[0].name;
                document.getElementById('fileName').textContent = fileName;
                document.getElementById('fileInfo').style.display = 'block';
            } else {
                document.getElementById('fileInfo').style.display = 'none';
            }
        });

        // Handle drag and drop
        const fileUpload = document.querySelector('.file-upload');
        fileUpload.addEventListener('dragover', (e) => {
            e.preventDefault();
            fileUpload.style.borderColor = 'var(--primary)';
            fileUpload.style.backgroundColor = 'rgba(139, 0, 0, 0.05)';
        });

        fileUpload.addEventListener('dragleave', () => {
            fileUpload.style.borderColor = 'var(--gray)';
            fileUpload.style.backgroundColor = 'transparent';
        });

        fileUpload.addEventListener('drop', (e) => {
            e.preventDefault();
            fileUpload.style.borderColor = 'var(--gray)';
            fileUpload.style.backgroundColor = 'transparent';
            
            if (e.dataTransfer.files.length) {
                document.getElementById('fileInput').files = e.dataTransfer.files;
                const fileName = e.dataTransfer.files[0].name;
                document.getElementById('fileName').textContent = fileName;
                document.getElementById('fileInfo').style.display = 'block';
            }
        });

        // Navigation between sections
        document.querySelectorAll('.menu-link').forEach(link => {
            link.addEventListener('click', function(e) {
                e.preventDefault();
                const section = this.getAttribute('data-section');
                // Hide all feature UIs and show dashboard sections by default
                document.querySelectorAll('.feature-ui').forEach(ui => {
                    ui.style.display = 'none';
                });
                document.getElementById('dashboardSections').classList.remove('hidden');
                // Update page title
                const pageTitle = document.getElementById('pageTitle');
                // Handle each section
                if (section === 'dashboard') {
                    document.getElementById('dashboardSections').classList.remove('hidden');
                    pageTitle.textContent = 'Document Request Dashboard';
                } else {
                    document.getElementById('dashboardSections').classList.add('hidden');
                    if (section === 'documentRequests') {
                        document.getElementById('documentRequestsUI').style.display = 'block';
                        pageTitle.textContent = 'Document Requests Management';
                    } else if (section === 'studentRecords') {
                        document.getElementById('studentRecordsUI').style.display = 'block';
                        pageTitle.textContent = 'Student Records Management';
                    } else if (section === 'reports') {
                        document.getElementById('reportsUI').style.display = 'block';
                        pageTitle.textContent = 'System Reports';
                    } else if (section === 'systemLog') {
                        document.getElementById('systemLogUI').style.display = 'block';
                        pageTitle.textContent = 'System Log';
                    } else if (section === 'liveChat') {
                        document.getElementById('liveChatUI').style.display = 'block';
                        pageTitle.textContent = 'Live Chat Inquiries';
                    }
                }
                // Update active menu item
                document.querySelectorAll('.menu-link').forEach(l => l.classList.remove('active'));
                this.classList.add('active');
            });
        });
        

        
        // Report tab switching
        document.querySelectorAll('.report-tab').forEach(tab => {
            tab.addEventListener('click', function() {
                document.querySelectorAll('.report-tab').forEach(t => t.classList.remove('active'));
                this.classList.add('active');
            });
        });

        // Student Records Department Logo Grid Logic
        const departmentCards = document.querySelectorAll('.department-logo-card');
        const departmentGrid = document.getElementById('departmentGrid');
        const studentTableSection = document.getElementById('studentTableSection');
        const studentTable = document.getElementById('studentTable');
        const backToGridBtn = document.getElementById('backToGridBtn');
        const programFilter = document.getElementById('programFilter');
        const yearFilter = document.getElementById('yearFilter');
        const studentSearchInput = document.getElementById('studentSearchInput');
        let selectedDepartment = null;

        // Custom Context Menu Logic
        const logoContextMenu = document.getElementById('logoContextMenu');
        const logoFileInput = document.getElementById('logoFileInput');
        let contextTargetCard = null;

        departmentCards.forEach(card => {
            // Left click: show students
            card.addEventListener('click', function() {
                selectedDepartment = this.getAttribute('data-department');
                departmentGrid.style.display = 'none';
                studentTableSection.style.display = 'block';
                programFilter.value = selectedDepartment;
                filterStudentTable();
            });
            // Right click: show custom context menu
            card.addEventListener('contextmenu', function(e) {
                e.preventDefault();
                contextTargetCard = this;
                logoContextMenu.style.display = 'block';
                logoContextMenu.style.left = e.pageX + 'px';
                logoContextMenu.style.top = e.pageY + 'px';
            });
        });

        // Hide context menu on click elsewhere
        document.addEventListener('click', function(e) {
            if (!logoContextMenu.contains(e.target)) {
                logoContextMenu.style.display = 'none';
            }
        });
        // Hide context menu on scroll
        window.addEventListener('scroll', function() {
            logoContextMenu.style.display = 'none';
        });

        // Upload Logo button
        document.getElementById('uploadLogoBtn').addEventListener('click', function() {
            logoContextMenu.style.display = 'none';
            if (contextTargetCard) {
                logoFileInput.value = '';
                logoFileInput.click();
            }
        });
        // Update Logo button (for demo, same as upload)
        document.getElementById('updateLogoBtn').addEventListener('click', function() {
            logoContextMenu.style.display = 'none';
            if (contextTargetCard) {
                logoFileInput.value = '';
                logoFileInput.click();
            }
        });
        // Handle file input change
        logoFileInput.addEventListener('change', function() {
            if (this.files && this.files[0] && contextTargetCard) {
                const department = contextTargetCard.getAttribute('data-department');
                const formData = new FormData();
                formData.append('department', department);
                formData.append('logo', this.files[0]);
                console.log('Uploading logo for:', department);
                fetch('/registrar/department-logo', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: formData
                })
                .then(res => res.json())
                .then(data => {
                    console.log('Upload response:', data);
                    if (data.success) {
                        const img = contextTargetCard.querySelector('img');
                        img.src = data.logo_url + '?t=' + new Date().getTime(); // bust cache
                    } else {
                        alert('Failed to upload logo.');
                    }
                })
                .catch((e) => {
                    alert('Failed to upload logo.');
                    console.error(e);
                });
            }
        });

        backToGridBtn.addEventListener('click', function() {
            studentTableSection.style.display = 'none';
            departmentGrid.style.display = 'flex';
            selectedDepartment = null;
            programFilter.value = 'All Programs';
            yearFilter.value = 'All Years';
            studentSearchInput.value = '';
            filterStudentTable();
        });

        // Filtering logic (search only)
        function filterStudentTable() {
            const search = studentSearchInput.value.toLowerCase();
            Array.from(studentTable.querySelectorAll('tbody tr')).forEach(row => {
                const rowText = row.textContent.toLowerCase();
                let show = true;
                if (selectedDepartment && row.getAttribute('data-program') !== selectedDepartment) show = false;
                if (search && !rowText.includes(search)) show = false;
                row.style.display = show ? '' : 'none';
            });
        }
        studentSearchInput.addEventListener('input', filterStudentTable);

        // Real-time update for pending requests badge
        function updatePendingRequestsBadge() {
            fetch('/registrar/pending-count', {
                method: 'GET',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Content-Type': 'application/json',
                    'Accept': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                const badge = document.getElementById('pendingRequestsBadge');
                const currentCount = parseInt(badge.textContent) || 0;
                const newCount = data.pending;
                
                console.log('Pending requests count:', newCount);
                
                // Only update if count changed
                if (currentCount !== newCount) {
                    // Add animation class
                    badge.classList.add('updating');
                    
                    // Update the count
                    badge.textContent = newCount;
                    
                    // Hide badge if no pending requests, show if there are
                    if (newCount > 0) {
                        badge.style.display = 'flex';
                    } else {
                        badge.style.display = 'none';
                    }
                    
                    // Remove animation class after animation completes
                    setTimeout(() => {
                        badge.classList.remove('updating');
                    }, 300);
                }
            })
            .catch(error => {
                console.error('Error updating pending count:', error);
            });
        }
        
        // Update immediately when page loads
        updatePendingRequestsBadge();
        
        // Update every 30 seconds
        setInterval(updatePendingRequestsBadge, 30000);

        // Update badge when requests are approved/rejected
        document.addEventListener('submit', function(e) {
            if (e.target.matches('form[action*="/registrar/approve"], form[action*="/registrar/reject"]')) {
                // Update the badge after a short delay to allow the form submission to complete
                setTimeout(updatePendingRequestsBadge, 1000);
            }
        });

        // Document Requests Management Filters
        document.addEventListener('DOMContentLoaded', function() {
            const docTable = document.getElementById('documentRequestsTable');
            if (!docTable) return;
            function getDocRows() {
                const tbody = docTable.querySelector('tbody');
                if (!tbody) return [];
                return Array.from(tbody.querySelectorAll('tr'));
            }
            const statusTabs = document.querySelectorAll('.filter-tab');
            const docTypeSelect = document.querySelector('.filter-select');
            const dateInput = document.querySelector('.filter-date');
            const docSearchInput = document.querySelector('.document-actions .search-bar input');

            let activeStatus = 'all';

            function filterDocRequests() {
                const selectedType = docTypeSelect && docTypeSelect.value ? docTypeSelect.value.toLowerCase() : '';
                const selectedDate = dateInput && dateInput.value ? dateInput.value : '';
                const search = docSearchInput && docSearchInput.value ? docSearchInput.value.toLowerCase() : '';
                getDocRows().forEach(row => {
                    let show = true;
                    // Status filter
                    if (activeStatus !== 'all') {
                        if (row.getAttribute('data-status') !== activeStatus) show = false;
                    }
                    // Document type filter
                    if (selectedType && selectedType !== 'all document types') {
                        const docs = row.getAttribute('data-documents') || '';
                        if (!docs.includes(selectedType)) show = false;
                    }
                    // Date filter
                    if (selectedDate) {
                        if (row.getAttribute('data-date') !== selectedDate) show = false;
                    }
                    // Search filter
                    if (search) {
                        if (!row.textContent.toLowerCase().includes(search)) show = false;
                    }
                    row.style.display = show ? '' : 'none';
                });
            }

            statusTabs.forEach(tab => {
                tab.addEventListener('click', function() {
                    statusTabs.forEach(t => t.classList.remove('active'));
                    this.classList.add('active');
                    const label = this.textContent.trim().toLowerCase();
                    if (label === 'all requests') activeStatus = 'all';
                    else if (label === 'approved') activeStatus = 'approved';
                    else activeStatus = label;
                    filterDocRequests();
                });
            });
            if (docTypeSelect) docTypeSelect.addEventListener('change', filterDocRequests);
            if (dateInput) dateInput.addEventListener('change', filterDocRequests);
            if (docSearchInput) docSearchInput.addEventListener('input', filterDocRequests);
            // Initial filter on page load
            filterDocRequests();
        });
    </script>
</body>
    <!-- Verify Modal Placeholder -->
    <div id="verifyModal" class="import-modal" style="display:none;"></div>
    <script src="/js/registrar-verify-modal.js"></script>
</html>