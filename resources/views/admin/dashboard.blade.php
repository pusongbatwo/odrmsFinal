<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Admin Dashboard</title>
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
            --shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
            --border-radius: 8px;
            --transition: all 0.2s ease;
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
            transition: var(--transition);
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
            border-radius: var(--border-radius);
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
            transition: var(--transition);
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

        .sidebar-footer {
            padding: 1rem 1.5rem;
            border-top: 1px solid rgba(255, 255, 255, 0.1);
        }

        .user-profile {
            display: flex;
            align-items: center;
            gap: 12px;
            margin-bottom: 1rem;
        }

        .avatar {
            width: 36px;
            height: 36px;
            border-radius: 50%;
            object-fit: cover;
            border: 2px solid var(--secondary);
            transition: var(--transition);
        }

        .avatar:hover {
            transform: scale(1.05);
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

        .logout-link {
            display: flex;
            align-items: center;
            padding: 12px 1.5rem;
            color: rgba(255, 255, 255, 0.8);
            text-decoration: none;
            transition: var(--transition);
            position: relative;
            cursor: pointer;
            margin-left: -1.5rem;
            margin-right: -1.5rem;
        }

        .logout-link:hover {
            background: rgba(255, 255, 255, 0.1);
            color: var(--light);
        }

        .logout-link:hover::before {
            content: '';
            position: absolute;
            left: 0;
            top: 0;
            height: 100%;
            width: 4px;
            background: var(--danger);
        }

        .logout-icon {
            font-size: 20px;
            margin-right: 12px;
            color: var(--danger);
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
            border-radius: var(--border-radius);
            box-shadow: var(--shadow);
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
            border-radius: var(--border-radius);
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

        .user-management {
            background: var(--light);
            border-radius: var(--border-radius);
            padding: 1.5rem;
            box-shadow: var(--shadow);
            margin-bottom: 1.5rem;
            border-top: 3px solid var(--secondary);
        }

        .user-management h2 {
            font-size: 18px;
            font-weight: 600;
            color: var(--primary);
            margin-bottom: 1.5rem;
        }

        .user-table {
            width: 100%;
            border-collapse: collapse;
        }

        .user-table th, .user-table td {
            text-align: left;
            padding: 12px 16px;
            border-bottom: 1px solid var(--gray);
        }

        .user-table th {
            font-size: 12px;
            color: var(--gray-dark);
            font-weight: 500;
            text-transform: uppercase;
        }

        .user-table td {
            font-size: 14px;
        }

        .add-user-btn {
            background: var(--primary);
            color: var(--light);
            padding: 10px 15px;
            border: none;
            border-radius: var(--border-radius);
            cursor: pointer;
            margin-bottom: 1rem;
            transition: var(--transition);
        }

        .add-user-btn:hover {
            background: var(--primary-light);
        }

        .edit-btn, .delete-btn {
            background: none;
            border: none;
            cursor: pointer;
            font-size: 16px;
            margin-left: 8px;
            transition: var(--transition);
        }

        .edit-btn {
            color: var(--warning);
        }

        .delete-btn {
            color: var(--danger);
        }

        .edit-btn:hover, .delete-btn:hover {
            transform: scale(1.1);
        }

        .user-modal {
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
            border-radius: var(--border-radius);
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
            transition: var(--transition);
        }

        .close-btn:hover {
            color: var(--primary);
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
            border-radius: var(--border-radius);
            font-size: 14px;
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
            border-radius: var(--border-radius);
            font-weight: 500;
            cursor: pointer;
            border: none;
            transition: var(--transition);
        }

        .btn-secondary {
            background: var(--gray);
            color: var(--dark);
        }

        .btn-primary {
            background: var(--primary);
            color: var(--light);
        }

        .btn:hover {
            transform: scale(1.05);
        }

        .reports-section {
            background: var(--light);
            border-radius: var(--border-radius);
            padding: 1.5rem;
            box-shadow: var(--shadow);
            margin-bottom: 1.5rem;
            border-top: 3px solid var(--secondary);
        }

        .reports-section h2 {
            font-size: 18px;
            font-weight: 600;
            color: var(--primary);
            margin-bottom: 1.5rem;
        }

        .report-card {
            background: rgba(139, 0, 0, 0.05);
            border-radius: var(--border-radius);
            padding: 1rem;
            margin-bottom: 1rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .report-card h3 {
            font-size: 16px;
            color: var(--primary);
            margin-bottom: 0.5rem;
        }

        .report-card p {
            font-size: 14px;
            color: var(--gray-dark);
        }

        .report-card .report-details {
            flex-grow: 1;
        }

        .report-card .view-report-btn {
            background: var(--secondary);
            color: var(--primary);
            padding: 8px 12px;
            border: none;
            border-radius: var(--border-radius);
            cursor: pointer;
            transition: var(--transition);
        }

        .report-card .view-report-btn:hover {
            background: #c29f2f;
        }

        /* System Logs Styles */
        .logs-section {
            background: var(--light);
            border-radius: var(--border-radius);
            padding: 1.5rem;
            box-shadow: var(--shadow);
            margin-bottom: 1.5rem;
            border-top: 3px solid var(--secondary);
            display: none;
        }

        .logs-section h2 {
            font-size: 18px;
            font-weight: 600;
            color: var(--primary);
            margin-bottom: 1.5rem;
        }

        .log-filter {
            display: flex;
            gap: 1rem;
            margin-bottom: 1.5rem;
        }

        .log-filter select, .log-filter input {
            padding: 8px 12px;
            border: 1px solid var(--gray);
            border-radius: var(--border-radius);
        }

        .log-table {
            width: 100%;
            border-collapse: collapse;
        }

        .log-table th, .log-table td {
            text-align: left;
            padding: 12px 16px;
            border-bottom: 1px solid var(--gray);
        }

        .log-table th {
            font-size: 12px;
            color: var(--gray-dark);
            font-weight: 500;
            text-transform: uppercase;
        }

        .log-table td {
            font-size: 14px;
        }

        .log-type {
            display: inline-block;
            padding: 4px 8px;
            border-radius: 4px;
            font-size: 12px;
            font-weight: 500;
        }

        .log-type.info {
            background-color: rgba(33, 150, 243, 0.1);
            color: #2196F3;
        }

        .log-type.warning {
            background-color: rgba(255, 193, 7, 0.1);
            color: #FFC107;
        }

        .log-type.error {
            background-color: rgba(244, 67, 54, 0.1);
            color: #F44336;
        }

        .log-type.success {
            background-color: rgba(76, 175, 80, 0.1);
            color: #4CAF50;
        }

        /* Dashboard sections */
        .dashboard-section {
            display: block;
        }

        /* Document Management Styles */
        .document-management {
            background: var(--light);
            border-radius: var(--border-radius);
            padding: 1.5rem;
            box-shadow: var(--shadow);
            margin-bottom: 1.5rem;
            border-top: 3px solid var(--secondary);
            display: none;
        }

        .document-management h2 {
            font-size: 18px;
            font-weight: 600;
            color: var(--primary);
            margin-bottom: 1.5rem;
        }

        .add-document-btn {
            background: var(--primary);
            color: var(--light);
            padding: 10px 15px;
            border: none;
            border-radius: var(--border-radius);
            cursor: pointer;
            margin-bottom: 1rem;
            transition: var(--transition);
        }

        .add-document-btn:hover {
            background: var(--primary-light);
        }

        .document-table {
            width: 100%;
            border-collapse: collapse;
        }

        .document-table th, .document-table td {
            text-align: left;
            padding: 12px 16px;
            border-bottom: 1px solid var(--gray);
        }

        .document-table th {
            font-size: 12px;
            color: var(--gray-dark);
            font-weight: 500;
            text-transform: uppercase;
        }

        .document-table td {
            font-size: 14px;
        }

        .price-input {
            width: 80px;
            padding: 6px 8px;
            border: 1px solid var(--gray);
            border-radius: var(--border-radius);
        }

        /* Profile Modal Styles */
        .profile-modal {
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
            overflow-y: auto;
            padding: 20px;
        }

        .profile-modal-content {
            background: var(--light);
            border-radius: var(--border-radius);
            width: 500px;
            max-width: 90%;
            max-height: 90vh;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.15);
            position: relative;
            overflow-y: auto;
            margin: auto;
        }

        .profile-modal-body {
            padding: 2rem;
            max-height: calc(90vh - 120px);
            overflow-y: auto;
        }

        .profile-header {
            text-align: center;
            margin-bottom: 2rem;
            border-bottom: 1px solid var(--gray);
            padding-bottom: 1.5rem;
        }

        .profile-avatar-container {
            position: relative;
            display: inline-block;
            margin-bottom: 1rem;
        }

        .profile-avatar {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            object-fit: cover;
            border: 3px solid var(--secondary);
            cursor: pointer;
            transition: var(--transition);
        }

        .profile-avatar:hover {
            transform: scale(1.05);
        }

        .avatar-upload-overlay {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0, 0, 0, 0.5);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            opacity: 0;
            transition: var(--transition);
            cursor: pointer;
        }

        .profile-avatar-container:hover .avatar-upload-overlay {
            opacity: 1;
        }

        .avatar-upload-icon {
            color: white;
            font-size: 20px;
        }

        .profile-name {
            font-size: 20px;
            font-weight: 600;
            color: var(--primary);
            margin-bottom: 0.5rem;
        }

        .profile-role {
            font-size: 14px;
            color: var(--gray-dark);
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .profile-form {
            margin-bottom: 2rem;
        }

        .profile-actions {
            display: flex;
            gap: 1rem;
            justify-content: center;
            margin-top: 1.5rem;
            padding-top: 1.5rem;
            border-top: 1px solid var(--gray);
        }

        .btn-logout {
            background: var(--danger);
            color: var(--light);
            padding: 10px 20px;
            border: none;
            border-radius: var(--border-radius);
            cursor: pointer;
            transition: var(--transition);
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .btn-logout:hover {
            background: #d32f2f;
            transform: scale(1.05);
        }

        .file-input {
            display: none;
        }

        /* Reports Modal Styles */
        .report-chart {
            background: var(--light);
            border-radius: var(--border-radius);
            padding: 1.5rem;
            margin-bottom: 1.5rem;
            box-shadow: var(--shadow);
        }

        .report-chart h4 {
            color: var(--primary);
            margin-bottom: 1rem;
            font-size: 16px;
            font-weight: 600;
        }

        .report-stats {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1rem;
            margin-bottom: 1.5rem;
        }

        .stat-card {
            background: var(--light);
            border-radius: var(--border-radius);
            padding: 1rem;
            text-align: center;
            box-shadow: var(--shadow);
            border-left: 4px solid var(--secondary);
            transition: var(--transition);
        }

        .stat-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.1);
        }

        .stat-card h5 {
            color: var(--primary);
            font-size: 24px;
            margin-bottom: 0.5rem;
            font-weight: 600;
        }

        .stat-card p {
            color: var(--gray-dark);
            font-size: 14px;
            margin: 0;
        }

        .report-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 1rem;
        }

        .report-table th, .report-table td {
            text-align: left;
            padding: 12px 16px;
            border-bottom: 1px solid var(--gray);
        }

        .report-table th {
            background: rgba(139, 0, 0, 0.05);
            font-weight: 600;
            color: var(--primary);
            font-size: 14px;
        }

        .report-table td {
            font-size: 14px;
        }

        .report-table tr:hover {
            background: rgba(139, 0, 0, 0.02);
        }

        .chart-placeholder {
            height: 300px;
            background: rgba(139, 0, 0, 0.05);
            border-radius: var(--border-radius);
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--gray-dark);
            font-style: italic;
        }

        /* Mobile responsiveness for profile modal */
        @media (max-width: 768px) {
            .profile-modal {
                padding: 10px;
            }
            
            .profile-modal-content {
                width: 95%;
                max-height: 95vh;
                margin: 0;
            }
            
            .profile-modal-body {
                max-height: calc(95vh - 80px);
                padding: 1rem;
            }

            .report-stats {
                grid-template-columns: 1fr;
            }
        }

        @media (max-width: 1200px) {
            .sidebar {
                width: 80px;
            }

            .logo-text, .menu-title, .menu-text, .user-details, .logout-link span {
                display: none;
            }

            .menu-link {
                justify-content: center;
                padding: 12px 0;
            }

            .logout-link {
                justify-content: center;
                padding: 12px 0;
                margin-left: 0;
                margin-right: 0;
            }

            .menu-icon {
                margin-right: 0;
                font-size: 22px;
            }

            .logout-icon {
                margin-right: 0;
                font-size: 22px;
            }

            .search-bar {
                width: 200px;
            }
        }

        @media (max-width: 768px) {
            .header {
                flex-direction: column;
                align-items: flex-start;
                gap: 1rem;
            }

            .search-bar {
                width: 100%;
            }
        }
    </style>
</head>
<body>
    <aside class="sidebar">
        <div class="sidebar-header">
            <div class="logo">
                <div class="logo-icon">
                    <i class="fas fa-user-shield"></i>
                </div>
                <span class="logo-text">Admin Panel</span>
            </div>
            <button class="toggle-btn">
                <i class="fas fa-chevron-left"></i>
            </button>
        </div>

        <div class="sidebar-menu">
            <h3 class="menu-title">Admin</h3>
            <ul class="menu-items">
                <li class="menu-item">
                    <a href="#" class="menu-link active" onclick="showSection('dashboard')">
                        <i class="fas fa-tachometer-alt menu-icon"></i>
                        <span class="menu-text">Dashboard</span>
                    </a>
                </li>
                <li class="menu-item">
                    <a href="#" class="menu-link" onclick="showSection('users')">
                        <i class="fas fa-users menu-icon"></i>
                        <span class="menu-text">Manage Users</span>
                    </a>
                </li>
                <li class="menu-item">
                    <a href="#" class="menu-link" onclick="showSection('documents')">
                        <i class="fas fa-file-alt menu-icon"></i>
                        <span class="menu-text">Manage Documents</span>
                    </a>
                </li>
                <li class="menu-item">
                    <a href="#" class="menu-link" onclick="showSection('reports')">
                        <i class="fas fa-chart-pie menu-icon"></i>
                        <span class="menu-text">Reports Overview</span>
                    </a>
                </li>
                <li class="menu-item">
                    <a href="#" class="menu-link" onclick="showSection('logs')">
                        <i class="fas fa-chart-line menu-icon"></i>
                        <span class="menu-text">System Logs</span>
                    </a>
                </li>
            </ul>
        </div>

        <div class="sidebar-footer">
            <div class="user-profile" onclick="openProfileModal()" style="cursor: pointer;">
                <img src="{{ Auth::user()->avatar ? asset('storage/' . Auth::user()->avatar) : 'https://randomuser.me/api/portraits/men/75.jpg' }}" alt="Admin" class="avatar">
                <div class="user-details">
                    <div class="user-name">{{ Auth::user()->full_name }}</div>
                    <div class="user-role">{{ ucfirst(Auth::user()->role) }}</div>
                </div>
            </div>
        </div>
    </aside>

    <main class="main-content">
        <div class="header">
            <div class="page-title">
                <h1 id="pageTitle">Admin Dashboard</h1>
            </div>
            
        </div>

        <!-- Dashboard Section -->
        <div class="dashboard-section" id="dashboardSection">
            <!-- Dashboard Statistics -->
            <div class="user-management">
                <h2>Dashboard Statistics</h2>
                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1rem; margin-bottom: 2rem;">
                    <div style="background: rgba(139, 0, 0, 0.05); padding: 1rem; border-radius: var(--border-radius); text-align: center;">
                        <h3 style="color: var(--primary); font-size: 24px; margin-bottom: 0.5rem;">{{ $stats['total_users'] }}</h3>
                        <p style="color: var(--gray-dark);">Total Users</p>
                    </div>
                    <div style="background: rgba(139, 0, 0, 0.05); padding: 1rem; border-radius: var(--border-radius); text-align: center;">
                        <h3 style="color: var(--primary); font-size: 24px; margin-bottom: 0.5rem;">{{ $stats['total_document_requests'] }}</h3>
                        <p style="color: var(--gray-dark);">Total Requests</p>
                    </div>
                    <div style="background: rgba(255, 193, 7, 0.1); padding: 1rem; border-radius: var(--border-radius); text-align: center;">
                        <h3 style="color: var(--warning); font-size: 24px; margin-bottom: 0.5rem;">{{ $stats['pending_requests'] }}</h3>
                        <p style="color: var(--gray-dark);">Pending Requests</p>
                    </div>
                    <div style="background: rgba(76, 175, 80, 0.1); padding: 1rem; border-radius: var(--border-radius); text-align: center;">
                        <h3 style="color: var(--success); font-size: 24px; margin-bottom: 0.5rem;">{{ $stats['completed_requests'] }}</h3>
                        <p style="color: var(--gray-dark);">Completed Requests</p>
                    </div>
                    <div style="background: rgba(33, 150, 243, 0.1); padding: 1rem; border-radius: var(--border-radius); text-align: center;">
                        <h3 style="color: #2196F3; font-size: 24px; margin-bottom: 0.5rem;">{{ $stats['paid_requests'] }}</h3>
                        <p style="color: var(--gray-dark);">Paid Requests</p>
                    </div>
                </div>
            </div>

            <div class="user-management">
                <h2>Recent Users</h2>
                <table class="user-table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Role</th>
                            <th>Created</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($recentUsers as $user)
                        <tr>
                            <td>{{ $user->id }}</td>
                            <td>{{ $user->full_name }}</td>
                            <td>{{ $user->email }}</td>
                            <td><span style="text-transform: capitalize; color: var(--primary); font-weight: 500;">{{ $user->role }}</span></td>
                            <td>{{ $user->created_at->format('M d, Y') }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" style="text-align: center; color: var(--gray-dark);">No users found</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="reports-section">
                <h2>Recent Document Requests</h2>
                <table class="user-table">
                    <thead>
                        <tr>
                            <th>Reference</th>
                            <th>Student</th>
                            <th>Status</th>
                            <th>Payment</th>
                            <th>Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($recentRequests as $request)
                        <tr>
                            <td>{{ $request->reference_number ?? 'N/A' }}</td>
                            <td>{{ $request->first_name }} {{ $request->last_name }}</td>
                            <td>
                                <span style="padding: 4px 8px; border-radius: 4px; font-size: 12px; font-weight: 500; text-transform: uppercase;
                                    @if($request->status === 'completed') background: rgba(76, 175, 80, 0.1); color: var(--success);
                                    @elseif($request->status === 'pending') background: rgba(255, 193, 7, 0.1); color: var(--warning);
                                    @elseif($request->status === 'approved') background: rgba(33, 150, 243, 0.1); color: #2196F3;
                                    @else background: rgba(244, 67, 54, 0.1); color: var(--danger); @endif">
                                    {{ $request->status }}
                                </span>
                            </td>
                            <td>
                                <span style="padding: 4px 8px; border-radius: 4px; font-size: 12px; font-weight: 500; text-transform: uppercase;
                                    @if($request->payment_status === 'paid') background: rgba(76, 175, 80, 0.1); color: var(--success);
                                    @else background: rgba(244, 67, 54, 0.1); color: var(--danger); @endif">
                                    {{ $request->payment_status }}
                                </span>
                            </td>
                            <td>{{ $request->created_at->format('M d, Y') }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" style="text-align: center; color: var(--gray-dark);">No requests found</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Users Section -->
        <div class="user-management" id="usersSection" style="display: none;">
            <h2>User Management</h2>
            <button class="add-user-btn" onclick="openUserModal()">Add New User</button>
            <div style="display: flex; gap: 1rem; margin-bottom: 1rem; align-items: center;">
                <div class="search-bar" style="width: 300px;">
                <i class="fas fa-search search-icon"></i>
                    <input type="text" placeholder="Search users..." id="userSearch" onkeyup="searchUsers()">
            </div>
                <select id="userRoleFilter" onchange="searchUsers()" style="padding: 8px 12px; border: 1px solid var(--gray); border-radius: var(--border-radius);">
                    <option value="all">All Roles</option>
                    <option value="admin">Admin</option>
                    <option value="registrar">Registrar</option>
                    <option value="cashier">Cashier</option>
                </select>
            </div>
            <div id="usersTableContainer">
            <table class="user-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Username</th>
                        <th>Email</th>
                        <th>Role</th>
                        <th>Created</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                    <tbody id="usersTableBody">
                        @forelse($recentUsers as $user)
                        <tr>
                            <td>{{ $user->id }}</td>
                            <td>{{ $user->full_name }}</td>
                            <td>{{ $user->username }}</td>
                            <td>{{ $user->email }}</td>
                            <td><span style="text-transform: capitalize; color: var(--primary); font-weight: 500;">{{ $user->role }}</span></td>
                            <td>{{ $user->created_at->format('M d, Y') }}</td>
                            <td>
                                <button class="edit-btn" onclick="editUser({{ $user->id }})" title="Edit User"><i class="fas fa-edit"></i></button>
                                <button class="delete-btn" onclick="deleteUser({{ $user->id }})" title="Delete User"><i class="fas fa-trash-alt"></i></button>
                                <button class="edit-btn" onclick="resetPassword({{ $user->id }})" title="Reset Password"><i class="fas fa-key"></i></button>
                        </td>
                    </tr>
                        @empty
                        <tr>
                            <td colspan="7" style="text-align: center; color: var(--gray-dark);">No users found</td>
                    </tr>
                        @endforelse
                </tbody>
            </table>
            </div>
        </div>

        <!-- Documents Section -->
        <div class="document-management" id="documentsSection" style="display: none;">
            <h2>Document Types Management</h2>
            <button class="add-user-btn" onclick="openDocumentModal()" style="margin-bottom: 1rem;">Add New Document Type</button>
            <div class="search-bar" style="margin-bottom: 1rem; width: 300px;">
                <i class="fas fa-search search-icon"></i>
                <input type="text" placeholder="Search documents..." id="documentSearch" onkeyup="searchDocuments()">
            </div>
            <div id="documentsTableContainer">
            <table class="document-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Document Type</th>
                        <th>Description</th>
                        <th>Price (₱)</th>
                        <th>Processing Time</th>
                        <th>Total Requests</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                    <tbody id="documentsTableBody">
                        <!-- Document types will be loaded via AJAX -->
                </tbody>
            </table>
            </div>
        </div>

        <!-- Reports Section -->
        <div class="reports-section" id="reportsSection" style="display: none;">
            <h2>Reports Overview</h2>
            <div id="reportsContainer">
                <!-- Reports will be loaded via AJAX -->
            </div>
        </div>

        <!-- System Logs Section -->
        <div class="logs-section" id="logsSection">
            <h2>System Logs</h2>
            
            <div class="log-filter">
                <select id="logTypeFilter">
                    <option value="all">All Log Types</option>
                    <option value="info">Info</option>
                    <option value="warning">Warning</option>
                    <option value="error">Error</option>
                    <option value="success">Success</option>
                </select>
                <input type="date" id="logDateFilter" style="width: 150px;">
                <input type="text" id="logSearchFilter" placeholder="Search logs...">
                <button class="btn btn-primary" onclick="filterLogs()">Filter</button>
                <button class="btn btn-secondary" onclick="exportLogs()" style="margin-left: 10px;">
                    <i class="fas fa-download"></i> Export
                </button>
            </div>
            
            <div id="logsTableContainer">
            <table class="log-table">
                <thead>
                    <tr>
                        <th>Timestamp</th>
                        <th>Type</th>
                        <th>User</th>
                            <th>Message</th>
                    </tr>
                </thead>
                    <tbody id="logsTableBody">
                        @forelse($recentLogs as $log)
                        <tr>
                            <td>{{ $log->created_at->format('M d, Y H:i:s') }}</td>
                            <td><span class="log-type {{ $log->type }}">{{ ucfirst($log->type) }}</span></td>
                            <td>{{ $log->user ? $log->user->full_name : 'System' }}</td>
                            <td>{{ $log->message }}</td>
                    </tr>
                        @empty
                        <tr>
                            <td colspan="4" style="text-align: center; color: var(--gray-dark);">No logs found</td>
                    </tr>
                        @endforelse
                </tbody>
            </table>
            </div>
        </div>

        <div class="user-modal" id="userModal">
            <div class="modal-content">
                <div class="modal-header">
                    <h3 class="modal-title" id="userModalTitle">Add New User</h3>
                    <button class="close-btn" onclick="closeUserModal()">&times;</button>
                </div>
                <form id="userForm">
                <div class="modal-body">
                        <input type="hidden" id="userId" name="id">
                    <div class="form-group">
                            <label class="form-label">Full Name</label>
                            <input type="text" id="fullName" name="full_name" class="form-input" placeholder="Enter full name" required>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Username</label>
                            <input type="text" id="username" name="username" class="form-input" placeholder="Enter username" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Email</label>
                            <input type="email" id="email" name="email" class="form-input" placeholder="Enter email" required>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Password</label>
                            <input type="password" id="password" name="password" class="form-input" placeholder="Enter password" required>
                            <small style="color: var(--gray-dark);">Leave blank to keep current password when editing</small>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Role</label>
                            <select id="role" name="role" class="form-input" required>
                                <option value="">Select Role</option>
                            <option value="registrar">Registrar</option>
                            <option value="cashier">Cashier</option>
                            <option value="admin">Administrator</option>
                        </select>
                    </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" onclick="closeUserModal()">Cancel</button>
                        <button type="submit" class="btn btn-primary" id="saveUserBtn">Save User</button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Password Reset Modal -->
        <div class="user-modal" id="passwordResetModal" style="display: none;">
            <div class="modal-content">
                <div class="modal-header">
                    <h3 class="modal-title">Reset Password</h3>
                    <button class="close-btn" onclick="closePasswordResetModal()">&times;</button>
                </div>
                <form id="passwordResetForm">
                    <div class="modal-body">
                        <input type="hidden" id="resetUserId" name="user_id">
                    <div class="form-group">
                            <label class="form-label">New Password</label>
                            <input type="password" id="newPassword" name="password" class="form-input" placeholder="Enter new password" required>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Confirm Password</label>
                            <input type="password" id="confirmPassword" class="form-input" placeholder="Confirm new password" required>
                    </div>
                </div>
                <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" onclick="closePasswordResetModal()">Cancel</button>
                        <button type="submit" class="btn btn-primary">Reset Password</button>
                </div>
                </form>
            </div>
        </div>

        <!-- Document Modal -->
        <div class="user-modal" id="documentModal" style="display: none;">
            <div class="modal-content">
                <div class="modal-header">
                    <h3 class="modal-title" id="documentModalTitle">Add/Edit Document Type</h3>
                    <button class="close-btn" onclick="closeDocumentModal()">&times;</button>
                </div>
                <form id="documentForm">
                    <div class="modal-body">
                        <input type="hidden" id="documentId" name="id">
                        <div class="form-group">
                            <label class="form-label">Document Type</label>
                            <input type="text" id="documentType" name="type" class="form-input" placeholder="Enter document type" required>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Description</label>
                            <textarea id="documentDescription" name="description" class="form-input" placeholder="Enter description" rows="3"></textarea>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Price (₱)</label>
                            <input type="number" id="documentPrice" name="price" class="form-input" placeholder="Enter price" step="0.01" min="0" required>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Processing Time</label>
                            <input type="text" id="documentProcessingTime" name="processing_time" class="form-input" placeholder="e.g., 3-5 days" required>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Status</label>
                            <select id="documentIsActive" name="is_active" class="form-input">
                                <option value="1">Active</option>
                                <option value="0">Inactive</option>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" onclick="closeDocumentModal()">Cancel</button>
                        <button type="submit" class="btn btn-primary" id="saveDocumentBtn">Save Document</button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Reports Modal -->
        <div class="user-modal" id="reportsModal" style="display: none;">
            <div class="modal-content" style="width: 90%; max-width: 1200px; max-height: 90vh; overflow-y: auto;">
                <div class="modal-header">
                    <h3 class="modal-title" id="reportsModalTitle">Detailed Report</h3>
                    <button class="close-btn" onclick="closeReportsModal()">&times;</button>
                </div>
                <div class="modal-body" id="reportsModalBody">
                    <!-- Report content will be loaded here -->
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" onclick="closeReportsModal()">Close</button>
                    <button type="button" class="btn btn-primary" onclick="exportCurrentReport()" id="exportReportBtn">
                        <i class="fas fa-download"></i> Export Report
                    </button>
                </div>
            </div>
        </div>

        <!-- Profile Modal -->
        <div class="profile-modal" id="profileModal" onclick="closeProfileModalOnBackdrop(event)">
            <div class="profile-modal-content" onclick="event.stopPropagation()">
                <div class="modal-header">
                    <h3 class="modal-title">Profile Settings</h3>
                    <button class="close-btn" onclick="closeProfileModal()" title="Close">&times;</button>
                </div>
                
                <div class="profile-modal-body">
                    <div class="profile-header">
                        <div class="profile-avatar-container" onclick="document.getElementById('profilePictureInput').click()">
                            <img src="{{ Auth::user()->avatar ? asset('storage/' . Auth::user()->avatar) : 'https://randomuser.me/api/portraits/men/75.jpg' }}" alt="Profile" class="profile-avatar" id="profileAvatar">
                            <div class="avatar-upload-overlay">
                                <i class="fas fa-camera avatar-upload-icon"></i>
                            </div>
                            <input type="file" id="profilePictureInput" class="file-input" accept="image/*" onchange="handleProfilePictureChange(event)">
                        </div>
                        <div class="profile-name" id="profileName">{{ Auth::user()->full_name }}</div>
                        <div class="profile-role" id="profileRole">{{ ucfirst(Auth::user()->role) }}</div>
                    </div>

                    <form id="profileForm" class="profile-form">
                        <div class="form-group">
                            <label class="form-label">Full Name</label>
                            <input type="text" id="profileFullName" name="full_name" class="form-input" value="{{ Auth::user()->full_name }}" required>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Username</label>
                            <input type="text" id="profileUsername" name="username" class="form-input" value="{{ Auth::user()->username }}" required>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Email</label>
                            <input type="email" id="profileEmail" name="email" class="form-input" value="{{ Auth::user()->email }}" required>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Current Password</label>
                            <input type="password" id="currentPassword" name="current_password" class="form-input" placeholder="Enter current password">
                            <small style="color: var(--gray-dark);">Required only if changing password</small>
                        </div>
                        <div class="form-group">
                            <label class="form-label">New Password</label>
                            <input type="password" id="newPassword" name="new_password" class="form-input" placeholder="Enter new password">
                            <small style="color: var(--gray-dark);">Leave blank to keep current password</small>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Confirm New Password</label>
                            <input type="password" id="confirmNewPassword" name="confirm_password" class="form-input" placeholder="Confirm new password">
                        </div>
                        
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" onclick="closeProfileModal()">Cancel</button>
                            <button type="submit" class="btn btn-primary">Update Profile</button>
                        </div>
                    </form>

                    <div class="profile-actions">
                        <button class="btn-logout" onclick="logout()">
                            <i class="fas fa-sign-out-alt"></i>
                            Logout
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <script>
        document.querySelector('.toggle-btn').addEventListener('click', function() {
            document.querySelector('.sidebar').classList.toggle('collapsed');
        });

        // CSRF token for AJAX requests
        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || 
                         '{{ csrf_token() }}';

        // User Management Functions
        function openUserModal(userId = null) {
            const modal = document.getElementById('userModal');
            const form = document.getElementById('userForm');
            const title = document.getElementById('userModalTitle');
            const passwordField = document.getElementById('password');
            
            form.reset();
            
            if (userId) {
                title.textContent = 'Edit User';
                passwordField.required = false;
                passwordField.placeholder = 'Leave blank to keep current password';
                loadUserData(userId);
            } else {
                title.textContent = 'Add New User';
                passwordField.required = true;
                passwordField.placeholder = 'Enter password';
            }
            
            modal.style.display = 'flex';
        }

        function closeUserModal() {
            document.getElementById('userModal').style.display = 'none';
            document.getElementById('userForm').reset();
        }

        function loadUserData(userId) {
            // This would typically load user data via AJAX
            // For now, we'll implement a basic version
            fetch(`/admin/users/${userId}`)
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        const user = data.user;
                        document.getElementById('userId').value = user.id;
                        document.getElementById('fullName').value = user.full_name;
                        document.getElementById('username').value = user.username;
                        document.getElementById('email').value = user.email;
                        document.getElementById('role').value = user.role;
                    }
                })
                .catch(error => {
                    console.error('Error loading user data:', error);
                    alert('Error loading user data');
                });
        }

        function editUser(userId) {
            openUserModal(userId);
        }

        function deleteUser(userId) {
            if (confirm('Are you sure you want to delete this user?')) {
                fetch(`/admin/users/${userId}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': csrfToken,
                        'Content-Type': 'application/json'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert('User deleted successfully');
                        loadUsers();
                    } else {
                        alert('Error: ' + data.message);
                    }
                })
                .catch(error => {
                    console.error('Error deleting user:', error);
                    alert('Error deleting user');
                });
            }
        }

        function resetPassword(userId) {
            document.getElementById('resetUserId').value = userId;
            document.getElementById('passwordResetModal').style.display = 'flex';
        }

        function closePasswordResetModal() {
            document.getElementById('passwordResetModal').style.display = 'none';
            document.getElementById('passwordResetForm').reset();
        }

        function searchUsers() {
            const search = document.getElementById('userSearch').value;
            const role = document.getElementById('userRoleFilter').value;
            
            const params = new URLSearchParams();
            if (search) params.append('search', search);
            if (role !== 'all') params.append('role', role);
            
            fetch(`/admin/users?${params}`)
                .then(response => response.json())
                .then(users => {
                    const tbody = document.getElementById('usersTableBody');
                    tbody.innerHTML = '';
                    
                    users.forEach(user => {
                        const row = document.createElement('tr');
                        row.innerHTML = `
                            <td>${user.id}</td>
                            <td>${user.full_name}</td>
                            <td>${user.username}</td>
                            <td>${user.email}</td>
                            <td><span style="text-transform: capitalize; color: var(--primary); font-weight: 500;">${user.role}</span></td>
                            <td>${new Date(user.created_at).toLocaleDateString()}</td>
                            <td>
                                <button class="edit-btn" onclick="editUser(${user.id})" title="Edit User"><i class="fas fa-edit"></i></button>
                                <button class="delete-btn" onclick="deleteUser(${user.id})" title="Delete User"><i class="fas fa-trash-alt"></i></button>
                                <button class="edit-btn" onclick="resetPassword(${user.id})" title="Reset Password"><i class="fas fa-key"></i></button>
                            </td>
                        `;
                        tbody.appendChild(row);
                    });
                })
                .catch(error => {
                    console.error('Error searching users:', error);
                });
        }

        function searchDocuments() {
            const search = document.getElementById('documentSearch').value;
            
            const params = new URLSearchParams();
            if (search) params.append('search', search);
            
            fetch(`/admin/documents?${params}`)
                .then(response => response.json())
                .then(documents => {
                    const tbody = document.getElementById('documentsTableBody');
                    tbody.innerHTML = '';
                    
                    documents.forEach((doc, index) => {
                        const row = document.createElement('tr');
                        row.innerHTML = `
                            <td>${doc.id}</td>
                            <td>${doc.type}</td>
                            <td>${doc.description || 'N/A'}</td>
                            <td>₱${doc.price}</td>
                            <td>${doc.processing_time}</td>
                            <td>${doc.total_requests}</td>
                            <td><span style="padding: 4px 8px; border-radius: 4px; font-size: 12px; font-weight: 500; text-transform: uppercase; ${doc.is_active ? 'background: rgba(76, 175, 80, 0.1); color: var(--success);' : 'background: rgba(244, 67, 54, 0.1); color: var(--danger);'}">${doc.is_active ? 'Active' : 'Inactive'}</span></td>
                            <td>
                                <button class="edit-btn" onclick="editDocument(${doc.id})" title="Edit Document"><i class="fas fa-edit"></i></button>
                                <button class="delete-btn" onclick="deleteDocument(${doc.id})" title="Delete Document"><i class="fas fa-trash-alt"></i></button>
                            </td>
                        `;
                        tbody.appendChild(row);
                    });
                })
                .catch(error => {
                    console.error('Error searching documents:', error);
                });
        }

        function performGlobalSearch() {
            const search = document.getElementById('globalSearch').value;
            
            // This could be expanded to search across all sections
            console.log('Global search:', search);
        }


        function exportLogs() {
            const type = document.getElementById('logTypeFilter').value;
            const date = document.getElementById('logDateFilter').value;
            const search = document.getElementById('logSearchFilter').value;
            
            const params = new URLSearchParams();
            if (type !== 'all') params.append('type', type);
            if (date) params.append('date', date);
            if (search) params.append('search', search);
            
            window.open(`/admin/system-logs/export?${params}`, '_blank');
        }

        // Global variable to store current report type
        let currentReportType = null;

        function viewCashierReport() {
            currentReportType = 'cashier';
            document.getElementById('reportsModalTitle').textContent = 'Cashier Detailed Report';
            document.getElementById('reportsModalBody').innerHTML = '<div style="text-align: center; padding: 2rem;"><i class="fas fa-spinner fa-spin"></i> Loading cashier report...</div>';
            document.getElementById('reportsModal').style.display = 'flex';
            
            fetch('/admin/reports/cashier-detailed')
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        displayCashierReport(data.data);
                    } else {
                        document.getElementById('reportsModalBody').innerHTML = '<div style="text-align: center; padding: 2rem; color: var(--danger);">Error loading cashier report: ' + data.message + '</div>';
                    }
                })
                .catch(error => {
                    console.error('Error loading cashier report:', error);
                    document.getElementById('reportsModalBody').innerHTML = '<div style="text-align: center; padding: 2rem; color: var(--danger);">Error loading cashier report</div>';
                });
        }

        function viewRegistrarReport() {
            currentReportType = 'registrar';
            document.getElementById('reportsModalTitle').textContent = 'Registrar Detailed Report';
            document.getElementById('reportsModalBody').innerHTML = '<div style="text-align: center; padding: 2rem;"><i class="fas fa-spinner fa-spin"></i> Loading registrar report...</div>';
            document.getElementById('reportsModal').style.display = 'flex';
            
            fetch('/admin/reports/registrar-detailed')
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        displayRegistrarReport(data.data);
                    } else {
                        document.getElementById('reportsModalBody').innerHTML = '<div style="text-align: center; padding: 2rem; color: var(--danger);">Error loading registrar report: ' + data.message + '</div>';
                    }
                })
                .catch(error => {
                    console.error('Error loading registrar report:', error);
                    document.getElementById('reportsModalBody').innerHTML = '<div style="text-align: center; padding: 2rem; color: var(--danger);">Error loading registrar report</div>';
                });
        }

        function viewDocumentBreakdown() {
            currentReportType = 'documents';
            document.getElementById('reportsModalTitle').textContent = 'Document Type Breakdown Report';
            document.getElementById('reportsModalBody').innerHTML = '<div style="text-align: center; padding: 2rem;"><i class="fas fa-spinner fa-spin"></i> Loading document breakdown...</div>';
            document.getElementById('reportsModal').style.display = 'flex';
            
            fetch('/admin/reports/document-breakdown')
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        displayDocumentBreakdown(data.data);
                    } else {
                        document.getElementById('reportsModalBody').innerHTML = '<div style="text-align: center; padding: 2rem; color: var(--danger);">Error loading document breakdown: ' + data.message + '</div>';
                    }
                })
                .catch(error => {
                    console.error('Error loading document breakdown:', error);
                    document.getElementById('reportsModalBody').innerHTML = '<div style="text-align: center; padding: 2rem; color: var(--danger);">Error loading document breakdown</div>';
                });
        }

        function displayCashierReport(data) {
            const html = `
                <div class="report-stats">
                    <div class="stat-card">
                        <h5>${data.pending_payments || 0}</h5>
                        <p>Pending Payments</p>
                    </div>
                    <div class="stat-card">
                        <h5>₱${(data.paid_today || 0).toLocaleString()}</h5>
                        <p>Paid Today</p>
                    </div>
                    <div class="stat-card">
                        <h5>₱${(data.total_collected_month || 0).toLocaleString()}</h5>
                        <p>Total Collected (This Month)</p>
                    </div>
                    <div class="stat-card">
                        <h5>${data.total_approved || 0}</h5>
                        <p>Total Approved</p>
                    </div>
                </div>

                <div class="report-chart">
                    <h4>Payment Trends (Last 7 Days)</h4>
                    <div class="chart-placeholder">
                        <canvas id="paymentTrendsChart" width="400" height="200"></canvas>
                    </div>
                </div>

                <div class="report-chart">
                    <h4>Monthly Payment Distribution</h4>
                    <div class="chart-placeholder">
                        <canvas id="monthlyPaymentsChart" width="400" height="200"></canvas>
                    </div>
                </div>

                <div class="report-chart">
                    <h4>Recent Payments</h4>
                    <table class="report-table">
                        <thead>
                            <tr>
                                <th>Date</th>
                                <th>Student</th>
                                <th>Amount</th>
                                <th>Documents</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            ${data.recent_payments.map(payment => `
                                <tr>
                                    <td>${new Date(payment.date).toLocaleDateString()}</td>
                                    <td>${payment.student_name}</td>
                                    <td>₱${payment.amount.toLocaleString()}</td>
                                    <td>${payment.document_count}</td>
                                    <td><span style="padding: 4px 8px; border-radius: 4px; background: rgba(76, 175, 80, 0.1); color: var(--success); font-size: 12px;">${payment.status}</span></td>
                                </tr>
                            `).join('')}
                        </tbody>
                    </table>
                </div>
            `;
            document.getElementById('reportsModalBody').innerHTML = html;
            
            // Create charts after DOM is updated
            setTimeout(() => {
                createPaymentTrendsChart(data);
                createMonthlyPaymentsChart(data);
            }, 100);
        }

        function displayRegistrarReport(data) {
            const html = `
                <div class="report-stats">
                    <div class="stat-card">
                        <h5>${data.total_requests}</h5>
                        <p>Total Requests</p>
                    </div>
                    <div class="stat-card">
                        <h5>${data.completed_requests}</h5>
                        <p>Completed</p>
                    </div>
                    <div class="stat-card">
                        <h5>${data.pending_requests}</h5>
                        <p>Pending</p>
                    </div>
                    <div class="stat-card">
                        <h5>${data.average_processing_days} days</h5>
                        <p>Avg Processing Time</p>
                    </div>
                </div>

                <div class="report-chart">
                    <h4>Request Status Distribution</h4>
                    <div class="chart-placeholder">
                        <canvas id="statusDistributionChart" width="400" height="200"></canvas>
                    </div>
                </div>

                <div class="report-chart">
                    <h4>Processing Time Analysis</h4>
                    <div class="chart-placeholder">
                        <canvas id="processingTimeChart" width="400" height="200"></canvas>
                    </div>
                </div>

                <div class="report-chart">
                    <h4>Recent Requests</h4>
                    <table class="report-table">
                        <thead>
                            <tr>
                                <th>Date</th>
                                <th>Student</th>
                                <th>Documents</th>
                                <th>Status</th>
                                <th>Processing Days</th>
                            </tr>
                        </thead>
                        <tbody>
                            ${data.recent_requests.map(request => `
                                <tr>
                                    <td>${new Date(request.date).toLocaleDateString()}</td>
                                    <td>${request.student_name}</td>
                                    <td>${request.document_count}</td>
                                    <td><span style="padding: 4px 8px; border-radius: 4px; font-size: 12px; ${getStatusStyle(request.status)}">${request.status}</span></td>
                                    <td>${request.processing_days || 'N/A'}</td>
                                </tr>
                            `).join('')}
                        </tbody>
                    </table>
                </div>
            `;
            document.getElementById('reportsModalBody').innerHTML = html;
            
            // Create charts after DOM is updated
            setTimeout(() => {
                createStatusDistributionChart(data);
                createProcessingTimeChart(data);
            }, 100);
        }

        function displayDocumentBreakdown(data) {
            const html = `
                <div class="report-stats">
                    <div class="stat-card">
                        <h5>${data.total_document_types}</h5>
                        <p>Document Types</p>
                    </div>
                    <div class="stat-card">
                        <h5>${data.total_requests}</h5>
                        <p>Total Requests</p>
                    </div>
                    <div class="stat-card">
                        <h5>${data.most_requested.type}</h5>
                        <p>Most Requested</p>
                    </div>
                    <div class="stat-card">
                        <h5>₱${data.total_revenue.toLocaleString()}</h5>
                        <p>Total Revenue</p>
                    </div>
                </div>

                <div class="report-chart">
                    <h4>Document Type Distribution</h4>
                    <div class="chart-placeholder">
                        <canvas id="documentTypeChart" width="400" height="200"></canvas>
                    </div>
                </div>

                <div class="report-chart">
                    <h4>Revenue by Document Type</h4>
                    <div class="chart-placeholder">
                        <canvas id="revenueChart" width="400" height="200"></canvas>
                    </div>
                </div>

                <div class="report-chart">
                    <h4>Document Type Breakdown</h4>
                    <table class="report-table">
                        <thead>
                            <tr>
                                <th>Document Type</th>
                                <th>Requests</th>
                                <th>Price</th>
                                <th>Revenue</th>
                                <th>% of Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            ${data.document_breakdown.map(doc => `
                                <tr>
                                    <td>${doc.type}</td>
                                    <td>${doc.count}</td>
                                    <td>₱${doc.price.toLocaleString()}</td>
                                    <td>₱${doc.revenue.toLocaleString()}</td>
                                    <td>${doc.percentage.toFixed(1)}%</td>
                                </tr>
                            `).join('')}
                        </tbody>
                    </table>
                </div>
            `;
            document.getElementById('reportsModalBody').innerHTML = html;
            
            // Create charts after DOM is updated
            setTimeout(() => {
                createDocumentTypeChart(data);
                createRevenueChart(data);
            }, 100);
        }

        function getStatusStyle(status) {
            switch(status.toLowerCase()) {
                case 'completed':
                    return 'background: rgba(76, 175, 80, 0.1); color: var(--success);';
                case 'pending':
                    return 'background: rgba(255, 193, 7, 0.1); color: var(--warning);';
                case 'approved':
                    return 'background: rgba(33, 150, 243, 0.1); color: #2196F3;';
                case 'rejected':
                    return 'background: rgba(244, 67, 54, 0.1); color: var(--danger);';
                default:
                    return 'background: rgba(158, 158, 158, 0.1); color: var(--gray-dark);';
            }
        }

        function closeReportsModal() {
            document.getElementById('reportsModal').style.display = 'none';
            currentReportType = null;
        }

        function exportCurrentReport() {
            if (!currentReportType) {
                alert('No report selected for export');
                return;
            }
            
            // Show loading state
            const exportBtn = document.getElementById('exportReportBtn');
            const originalText = exportBtn.innerHTML;
            exportBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Exporting...';
            exportBtn.disabled = true;
            
            // Create export URL based on current report type
            let exportUrl;
            switch(currentReportType) {
                case 'cashier':
                    exportUrl = '/admin/reports/cashier-detailed/export';
                    break;
                case 'registrar':
                    exportUrl = '/admin/reports/registrar-detailed/export';
                    break;
                case 'documents':
                    exportUrl = '/admin/reports/document-breakdown/export';
                    break;
                default:
                    alert('Invalid report type for export');
                    exportBtn.innerHTML = originalText;
                    exportBtn.disabled = false;
                    return;
            }
            
            // Use window.open for download
            try {
                // Create a new window/tab to trigger download
                const downloadWindow = window.open(exportUrl, '_blank');
                
                // If popup was blocked, try alternative method
                if (!downloadWindow || downloadWindow.closed || typeof downloadWindow.closed == 'undefined') {
                    // Fallback: create a form and submit it
                    const form = document.createElement('form');
                    form.method = 'GET';
                    form.action = exportUrl;
                    form.target = '_blank';
                    form.style.display = 'none';
                    document.body.appendChild(form);
                    form.submit();
                    document.body.removeChild(form);
                }
                
                // Reset button state after a short delay
                setTimeout(() => {
                    exportBtn.innerHTML = originalText;
                    exportBtn.disabled = false;
                }, 2000);
                
            } catch (error) {
                console.error('Export error:', error);
                alert('Export failed. Please try again.');
                exportBtn.innerHTML = originalText;
                exportBtn.disabled = false;
            }
        }

        // Document Management Functions
        function openDocumentModal(documentId = null) {
            const modal = document.getElementById('documentModal');
            const form = document.getElementById('documentForm');
            const title = document.getElementById('documentModalTitle');
            
            form.reset();
            
            if (documentId) {
                title.textContent = 'Edit Document Type';
                loadDocumentData(documentId);
            } else {
                title.textContent = 'Add New Document Type';
            }
            
            modal.style.display = 'flex';
        }

        function closeDocumentModal() {
            document.getElementById('documentModal').style.display = 'none';
            document.getElementById('documentForm').reset();
        }

        function loadDocumentData(documentId) {
            fetch(`/admin/documents/${documentId}`)
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        const doc = data.document_type;
                        document.getElementById('documentId').value = doc.id;
                        document.getElementById('documentType').value = doc.type;
                        document.getElementById('documentDescription').value = doc.description || '';
                        document.getElementById('documentPrice').value = doc.price;
                        document.getElementById('documentProcessingTime').value = doc.processing_time;
                        document.getElementById('documentIsActive').value = doc.is_active ? '1' : '0';
                    }
                })
                .catch(error => {
                    console.error('Error loading document data:', error);
                    alert('Error loading document data');
                });
        }

        function editDocument(documentId) {
            openDocumentModal(documentId);
        }

        function deleteDocument(documentId) {
            if (confirm('Are you sure you want to delete this document type?')) {
                fetch(`/admin/documents/${documentId}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': csrfToken,
                        'Content-Type': 'application/json'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert('Document type deleted successfully');
                        searchDocuments();
                    } else {
                        alert('Error: ' + data.message);
                    }
                })
                .catch(error => {
                    console.error('Error deleting document:', error);
                    alert('Error deleting document type');
                });
            }
        }


        function loadUsers() {
            fetch('/admin/users')
                .then(response => response.json())
                .then(users => {
                    const tbody = document.getElementById('usersTableBody');
                    tbody.innerHTML = '';
                    
                    users.forEach(user => {
                        const row = document.createElement('tr');
                        row.innerHTML = `
                            <td>${user.id}</td>
                            <td>${user.full_name}</td>
                            <td>${user.username}</td>
                            <td>${user.email}</td>
                            <td><span style="text-transform: capitalize; color: var(--primary); font-weight: 500;">${user.role}</span></td>
                            <td>${new Date(user.created_at).toLocaleDateString()}</td>
                            <td>
                                <button class="edit-btn" onclick="editUser(${user.id})" title="Edit User"><i class="fas fa-edit"></i></button>
                                <button class="delete-btn" onclick="deleteUser(${user.id})" title="Delete User"><i class="fas fa-trash-alt"></i></button>
                                <button class="edit-btn" onclick="resetPassword(${user.id})" title="Reset Password"><i class="fas fa-key"></i></button>
                            </td>
                        `;
                        tbody.appendChild(row);
                    });
                })
                .catch(error => {
                    console.error('Error loading users:', error);
                });
        }

        function loadDocuments() {
            fetch('/admin/documents')
                .then(response => response.json())
                .then(documents => {
                    const tbody = document.getElementById('documentsTableBody');
                    tbody.innerHTML = '';
                    
                    documents.forEach((doc, index) => {
                        const row = document.createElement('tr');
                        row.innerHTML = `
                            <td>${doc.id}</td>
                            <td>${doc.type}</td>
                            <td>${doc.description || 'N/A'}</td>
                            <td>₱${doc.price}</td>
                            <td>${doc.processing_time}</td>
                            <td>${doc.total_requests}</td>
                            <td><span style="padding: 4px 8px; border-radius: 4px; font-size: 12px; font-weight: 500; text-transform: uppercase; ${doc.is_active ? 'background: rgba(76, 175, 80, 0.1); color: var(--success);' : 'background: rgba(244, 67, 54, 0.1); color: var(--danger);'}">${doc.is_active ? 'Active' : 'Inactive'}</span></td>
                            <td>
                                <button class="edit-btn" onclick="editDocument(${doc.id})" title="Edit Document"><i class="fas fa-edit"></i></button>
                                <button class="delete-btn" onclick="deleteDocument(${doc.id})" title="Delete Document"><i class="fas fa-trash-alt"></i></button>
                            </td>
                        `;
                        tbody.appendChild(row);
                    });
                })
                .catch(error => {
                    console.error('Error loading documents:', error);
                });
        }

        function loadReports() {
            fetch('/admin/reports')
                .then(response => response.json())
                .then(data => {
                    const container = document.getElementById('reportsContainer');
                    container.innerHTML = `
                        <div class="report-card">
                            <div class="report-details">
                                <h3>Cashier Reports</h3>
                                <p>Pending: ${data.cashier.pending_payments || 0}, Paid Today: ₱${(data.cashier.paid_today || 0).toLocaleString()}, This Month: ₱${(data.cashier.total_collected_month || 0).toLocaleString()}</p>
                            </div>
                            <button class="view-report-btn" onclick="viewCashierReport()">View Report</button>
                        </div>
                        <div class="report-card">
                            <div class="report-details">
                                <h3>Registrar Document Requests</h3>
                                <p>New requests: ${data.registrar.new_requests}, Completed requests: ${data.registrar.completed_requests}</p>
                            </div>
                            <button class="view-report-btn" onclick="viewRegistrarReport()">View Report</button>
                        </div>
                        <div class="report-card">
                            <div class="report-details">
                                <h3>Document Type Breakdown</h3>
                                <p>Top document types: ${data.document_breakdown.slice(0, 3).map(d => d.document_type).join(', ')}</p>
                            </div>
                            <button class="view-report-btn" onclick="viewDocumentBreakdown()">View Report</button>
                        </div>
                    `;
                })
                .catch(error => {
                    console.error('Error loading reports:', error);
                });
        }

        function filterLogs() {
            const type = document.getElementById('logTypeFilter').value;
            const date = document.getElementById('logDateFilter').value;
            const search = document.getElementById('logSearchFilter').value;
            
            const params = new URLSearchParams();
            if (type !== 'all') params.append('type', type);
            if (date) params.append('date', date);
            if (search) params.append('search', search);
            
            fetch(`/admin/system-logs?${params}`)
                .then(response => response.json())
                .then(data => {
                    const tbody = document.getElementById('logsTableBody');
                    tbody.innerHTML = '';
                    
                    data.data.forEach(log => {
                        const row = document.createElement('tr');
                        row.innerHTML = `
                            <td>${new Date(log.created_at).toLocaleString()}</td>
                            <td><span class="log-type ${log.type}">${log.type.charAt(0).toUpperCase() + log.type.slice(1)}</span></td>
                            <td>${log.user ? log.user.full_name : 'System'}</td>
                            <td>${log.message}</td>
                        `;
                        tbody.appendChild(row);
                    });
                })
                .catch(error => {
                    console.error('Error filtering logs:', error);
                });
        }

        // Form submission handlers
        document.getElementById('userForm').addEventListener('submit', function(e) {
            e.preventDefault();
            
            const formData = new FormData(this);
            const userId = document.getElementById('userId').value;
            const isEdit = userId !== '';
            
            const url = isEdit ? `/admin/users/${userId}` : '/admin/users';
            const method = isEdit ? 'PUT' : 'POST';
            
            fetch(url, {
                method: method,
                headers: {
                    'X-CSRF-TOKEN': csrfToken,
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify(Object.fromEntries(formData))
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert(isEdit ? 'User updated successfully' : 'User created successfully');
                    closeUserModal();
                    loadUsers();
                } else {
                    alert('Error: ' + (data.message || 'Unknown error'));
                }
            })
            .catch(error => {
                console.error('Error saving user:', error);
                alert('Error saving user');
            });
        });

        // Password reset form handler
        document.getElementById('passwordResetForm').addEventListener('submit', function(e) {
            e.preventDefault();
            
            const password = document.getElementById('newPassword').value;
            const confirmPassword = document.getElementById('confirmPassword').value;
            const userId = document.getElementById('resetUserId').value;
            
            if (password !== confirmPassword) {
                alert('Passwords do not match');
                return;
            }
            
            if (password.length < 8) {
                alert('Password must be at least 8 characters long');
                return;
            }
            
            fetch(`/admin/users/${userId}/reset-password`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': csrfToken,
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({ password: password })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert('Password reset successfully');
                    closePasswordResetModal();
                } else {
                    alert('Error: ' + (data.message || 'Unknown error'));
                }
            })
            .catch(error => {
                console.error('Error resetting password:', error);
                alert('Error resetting password');
            });
        });

        // Document form handler
        document.getElementById('documentForm').addEventListener('submit', function(e) {
            e.preventDefault();
            
            const formData = new FormData(this);
            const documentId = document.getElementById('documentId').value;
            const isEdit = documentId !== '';
            
            const url = isEdit ? `/admin/documents/${documentId}` : '/admin/documents';
            const method = isEdit ? 'PUT' : 'POST';
            
            // Convert FormData to object and handle boolean conversion
            const data = Object.fromEntries(formData);
            data.is_active = data.is_active === '1';
            
            fetch(url, {
                method: method,
                headers: {
                    'X-CSRF-TOKEN': csrfToken,
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify(data)
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert(isEdit ? 'Document type updated successfully' : 'Document type created successfully');
                    closeDocumentModal();
                    searchDocuments();
                } else {
                    alert('Error: ' + (data.message || 'Unknown error'));
                }
            })
            .catch(error => {
                console.error('Error saving document:', error);
                alert('Error saving document type');
            });
        });

        // Profile form handler
        document.getElementById('profileForm').addEventListener('submit', function(e) {
            e.preventDefault();
            
            // Get password values using multiple methods
            const newPasswordField = document.getElementById('newPassword');
            const confirmPasswordField = document.getElementById('confirmNewPassword');
            const currentPasswordField = document.getElementById('currentPassword');
            
            let newPassword = newPasswordField ? newPasswordField.value : '';
            let confirmPassword = confirmPasswordField ? confirmPasswordField.value : '';
            let currentPassword = currentPasswordField ? currentPasswordField.value : '';
            
            // Fallback: try to get values using form data
            if (!newPassword || !confirmPassword || !currentPassword) {
                const formData = new FormData(this);
                newPassword = newPassword || formData.get('new_password') || '';
                confirmPassword = confirmPassword || formData.get('confirm_password') || '';
                currentPassword = currentPassword || formData.get('current_password') || '';
                
                console.log('Fallback: Using FormData values');
            }
            
            // Debug: Log the password values and field existence
            console.log('Password field debugging:');
            console.log('newPasswordField exists:', !!newPasswordField);
            console.log('confirmPasswordField exists:', !!confirmPasswordField);
            console.log('currentPasswordField exists:', !!currentPasswordField);
            console.log('newPassword value:', newPassword ? '[HIDDEN - length: ' + newPassword.length + ']' : 'EMPTY');
            console.log('confirmPassword value:', confirmPassword ? '[HIDDEN - length: ' + confirmPassword.length + ']' : 'EMPTY');
            console.log('currentPassword value:', currentPassword ? '[HIDDEN - length: ' + currentPassword.length + ']' : 'EMPTY');
            
            // Validate password fields
            if (newPassword && newPassword !== confirmPassword) {
                alert('New passwords do not match');
                return;
            }
            
            if (newPassword && newPassword.length < 8) {
                alert('New password must be at least 8 characters long');
                return;
            }
            
            if (newPassword && !currentPassword) {
                alert('Current password is required when changing password');
                return;
            }
            
            const formData = new FormData(this);
            const profilePicture = document.getElementById('profilePictureInput').files[0];
            
            // Create FormData for file upload
            const data = new FormData();
            data.append('full_name', formData.get('full_name'));
            data.append('username', formData.get('username'));
            data.append('email', formData.get('email'));
            
            // Always append password fields if they have values
            if (currentPassword && currentPassword.trim() !== '') {
            data.append('current_password', currentPassword);
                console.log('Appending current_password');
            }
            if (newPassword && newPassword.trim() !== '') {
            data.append('new_password', newPassword);
                console.log('Appending new_password');
            }
            if (confirmPassword && confirmPassword.trim() !== '') {
            data.append('confirm_password', confirmPassword);
                console.log('Appending confirm_password');
            }
            
            // Debug: Log the data being sent
            console.log('Sending profile update data:');
            for (let [key, value] of data.entries()) {
                if (key.includes('password')) {
                    console.log(key + ': [HIDDEN - length: ' + value.length + ']');
                } else {
                    console.log(key + ': ' + value);
                }
            }
            
            if (profilePicture) {
                data.append('profile_picture', profilePicture);
            }
            
            fetch('/admin/profile/update', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': csrfToken
                },
                body: data
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert('Profile updated successfully');
                    closeProfileModal();
                    // Update the sidebar user info
                    const userNameElement = document.querySelector('.user-name');
                    const userRoleElement = document.querySelector('.user-role');
                    const avatarElement = document.querySelector('.avatar');
                    const profileNameElement = document.getElementById('profileName');
                    const profileRoleElement = document.getElementById('profileRole');
                    
                    if (userNameElement) {
                        userNameElement.textContent = data.user.full_name;
                    }
                    if (userRoleElement) {
                        userRoleElement.textContent = data.user.role.charAt(0).toUpperCase() + data.user.role.slice(1);
                    }
                    if (avatarElement && data.user.avatar) {
                        avatarElement.src = data.user.avatar;
                    }
                    
                    // Also update the profile modal header
                    if (profileNameElement) {
                        profileNameElement.textContent = data.user.full_name;
                    }
                    if (profileRoleElement) {
                        profileRoleElement.textContent = data.user.role.charAt(0).toUpperCase() + data.user.role.slice(1);
                    }
                    
                    // Update form field values
                    document.getElementById('profileFullName').value = data.user.full_name;
                    document.getElementById('profileUsername').value = data.user.username;
                    document.getElementById('profileEmail').value = data.user.email;
                    
                    // Update profile modal avatar
                    const profileAvatarElement = document.getElementById('profileAvatar');
                    if (profileAvatarElement && data.user.avatar) {
                        profileAvatarElement.src = data.user.avatar;
                    }
                } else {
                    alert('Error: ' + (data.message || 'Unknown error'));
                }
            })
            .catch(error => {
                console.error('Error updating profile:', error);
                alert('Error updating profile');
            });
        });

        // Profile Modal Functions
        function openProfileModal() {
            document.getElementById('profileModal').style.display = 'flex';
            document.body.style.overflow = 'hidden'; // Prevent background scrolling
            
            // Refresh profile data when opening modal
            refreshProfileData();
        }
        
        function refreshProfileData() {
            // Update the profile modal with current user data
            const userNameElement = document.querySelector('.user-name');
            const userRoleElement = document.querySelector('.user-role');
            const avatarElement = document.querySelector('.avatar');
            
            if (userNameElement) {
                document.getElementById('profileName').textContent = userNameElement.textContent;
                document.getElementById('profileFullName').value = userNameElement.textContent;
            }
            if (userRoleElement) {
                document.getElementById('profileRole').textContent = userRoleElement.textContent;
            }
            if (avatarElement) {
                document.getElementById('profileAvatar').src = avatarElement.src;
            }
        }

        function closeProfileModal() {
            document.getElementById('profileModal').style.display = 'none';
            document.body.style.overflow = 'auto'; // Restore background scrolling
            document.getElementById('profileForm').reset();
        }

        function closeProfileModalOnBackdrop(event) {
            if (event.target === event.currentTarget) {
                closeProfileModal();
            }
        }

        // Close modal with Escape key
        document.addEventListener('keydown', function(event) {
            if (event.key === 'Escape') {
                const profileModal = document.getElementById('profileModal');
                if (profileModal.style.display === 'flex') {
                    closeProfileModal();
                }
            }
        });

        function handleProfilePictureChange(event) {
            const file = event.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    document.getElementById('profileAvatar').src = e.target.result;
                };
                reader.readAsDataURL(file);
            }
        }

        function logout() {
            if (confirm('Are you sure you want to logout?')) {
                // Create a form and submit it to logout
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = '/logout';
                
                const csrfInput = document.createElement('input');
                csrfInput.type = 'hidden';
                csrfInput.name = '_token';
                csrfInput.value = csrfToken;
                
                form.appendChild(csrfInput);
                document.body.appendChild(form);
                form.submit();
            }
        }

        function showSection(sectionId) {
            // Hide all sections
            document.getElementById('dashboardSection').style.display = 'none';
            document.getElementById('usersSection').style.display = 'none';
            document.getElementById('documentsSection').style.display = 'none';
            document.getElementById('reportsSection').style.display = 'none';
            document.getElementById('logsSection').style.display = 'none';
            
            // Update active menu item
            const menuLinks = document.querySelectorAll('.menu-link');
            menuLinks.forEach(link => link.classList.remove('active'));
            event.currentTarget.classList.add('active');
            
            // Show selected section and update page title
            if (sectionId === 'dashboard') {
                document.getElementById('dashboardSection').style.display = 'block';
                document.getElementById('pageTitle').textContent = 'Admin Dashboard';
            } else if (sectionId === 'users') {
                document.getElementById('usersSection').style.display = 'block';
                document.getElementById('pageTitle').textContent = 'User Management';
                searchUsers(); // Use searchUsers instead of loadUsers to include filters
            } else if (sectionId === 'documents') {
                document.getElementById('documentsSection').style.display = 'block';
                document.getElementById('pageTitle').textContent = 'Document Management';
                searchDocuments(); // Use searchDocuments instead of loadDocuments to include filters
            } else if (sectionId === 'reports') {
                document.getElementById('reportsSection').style.display = 'block';
                document.getElementById('pageTitle').textContent = 'Reports Overview';
                loadReports();
            } else if (sectionId === 'logs') {
                document.getElementById('logsSection').style.display = 'block';
                document.getElementById('pageTitle').textContent = 'System Logs';
                filterLogs();
            }
        }

        // Chart creation functions
        function createPaymentTrendsChart(data) {
            const ctx = document.getElementById('paymentTrendsChart');
            if (!ctx) return;

            // Use real data from backend
            const last7Days = [];
            const amounts = [];
            
            // Use daily_payments data from backend
            if (data.daily_payments && data.daily_payments.length > 0) {
                data.daily_payments.forEach(day => {
                    const date = new Date(day.date);
                    const dateLabel = date.toLocaleDateString('en-US', { month: 'short', day: 'numeric' });
                    last7Days.push(dateLabel);
                    amounts.push(day.amount);
                });
            } else {
                // Fallback: generate last 7 days with zeros
                for (let i = 6; i >= 0; i--) {
                    const date = new Date();
                    date.setDate(date.getDate() - i);
                    const dateLabel = date.toLocaleDateString('en-US', { month: 'short', day: 'numeric' });
                    last7Days.push(dateLabel);
                    amounts.push(0);
                }
            }

            new Chart(ctx, {
                type: 'line',
                data: {
                    labels: last7Days,
                    datasets: [{
                        label: 'Daily Revenue (₱)',
                        data: amounts,
                        borderColor: 'rgb(75, 192, 192)',
                        backgroundColor: 'rgba(75, 192, 192, 0.1)',
                        tension: 0.4,
                        fill: true
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: {
                            display: false
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                callback: function(value) {
                                    return '₱' + value.toLocaleString();
                                }
                            }
                        }
                    }
                }
            });
        }

        function createMonthlyPaymentsChart(data) {
            const ctx = document.getElementById('monthlyPaymentsChart');
            if (!ctx) return;

            // Use real data from backend
            const months = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
            let monthlyData;
            
            // Use monthly_data from backend if available
            if (data.monthly_data && data.monthly_data.length > 0) {
                monthlyData = data.monthly_data;
            } else {
                // Fallback: initialize with zeros
                monthlyData = new Array(12).fill(0);
            }

            new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: months,
                    datasets: [{
                        label: 'Payments',
                        data: monthlyData,
                        backgroundColor: 'rgba(54, 162, 235, 0.6)',
                        borderColor: 'rgba(54, 162, 235, 1)',
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: {
                            display: false
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    }
                }
            });
        }

        function createStatusDistributionChart(data) {
            const ctx = document.getElementById('statusDistributionChart');
            if (!ctx) return;

            new Chart(ctx, {
                type: 'doughnut',
                data: {
                    labels: ['Completed', 'Pending', 'Approved', 'Rejected'],
                    datasets: [{
                        data: [
                            data.completed_requests || 0,
                            data.pending_requests || 0,
                            data.approved_requests || 0,
                            data.rejected_requests || 0
                        ],
                        backgroundColor: [
                            'rgba(76, 175, 80, 0.8)',
                            'rgba(255, 193, 7, 0.8)',
                            'rgba(33, 150, 243, 0.8)',
                            'rgba(244, 67, 54, 0.8)'
                        ],
                        borderColor: [
                            'rgba(76, 175, 80, 1)',
                            'rgba(255, 193, 7, 1)',
                            'rgba(33, 150, 243, 1)',
                            'rgba(244, 67, 54, 1)'
                        ],
                        borderWidth: 2
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: {
                            position: 'bottom'
                        }
                    }
                }
            });
        }

        function createProcessingTimeChart(data) {
            const ctx = document.getElementById('processingTimeChart');
            if (!ctx) return;

            // Use real data from backend
            const timeRanges = ['0-1 days', '2-3 days', '4-7 days', '8-14 days', '15+ days'];
            let timeData;
            
            // Use processing_time_distribution from backend if available
            if (data.processing_time_distribution && data.processing_time_distribution.length > 0) {
                timeData = data.processing_time_distribution;
            } else {
                // Fallback: calculate from recent requests
                timeData = [0, 0, 0, 0, 0];
                data.recent_requests.forEach(request => {
                    const processingDays = request.processing_days;
                    if (processingDays !== null && processingDays !== undefined) {
                        if (processingDays <= 1) {
                            timeData[0]++;
                        } else if (processingDays <= 3) {
                            timeData[1]++;
                        } else if (processingDays <= 7) {
                            timeData[2]++;
                        } else if (processingDays <= 14) {
                            timeData[3]++;
                        } else {
                            timeData[4]++;
                        }
                    }
                });
            }

            new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: timeRanges,
                    datasets: [{
                        label: 'Number of Requests',
                        data: timeData,
                        backgroundColor: 'rgba(156, 39, 176, 0.6)',
                        borderColor: 'rgba(156, 39, 176, 1)',
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: {
                            display: false
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    }
                }
            });
        }

        function createDocumentTypeChart(data) {
            const ctx = document.getElementById('documentTypeChart');
            if (!ctx) return;

            // Use actual data from the report
            const labels = data.document_breakdown.map(doc => doc.type);
            const counts = data.document_breakdown.map(doc => doc.count);

            new Chart(ctx, {
                type: 'pie',
                data: {
                    labels: labels,
                    datasets: [{
                        data: counts,
                        backgroundColor: [
                            'rgba(255, 99, 132, 0.8)',
                            'rgba(54, 162, 235, 0.8)',
                            'rgba(255, 205, 86, 0.8)',
                            'rgba(75, 192, 192, 0.8)',
                            'rgba(153, 102, 255, 0.8)',
                            'rgba(255, 159, 64, 0.8)',
                            'rgba(199, 199, 199, 0.8)',
                            'rgba(83, 102, 255, 0.8)'
                        ],
                        borderColor: [
                            'rgba(255, 99, 132, 1)',
                            'rgba(54, 162, 235, 1)',
                            'rgba(255, 205, 86, 1)',
                            'rgba(75, 192, 192, 1)',
                            'rgba(153, 102, 255, 1)',
                            'rgba(255, 159, 64, 1)',
                            'rgba(199, 199, 199, 1)',
                            'rgba(83, 102, 255, 1)'
                        ],
                        borderWidth: 2
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: {
                            position: 'right'
                        }
                    }
                }
            });
        }

        function createRevenueChart(data) {
            const ctx = document.getElementById('revenueChart');
            if (!ctx) return;

            // Use actual data from the report
            const labels = data.document_breakdown.map(doc => doc.type);
            const revenues = data.document_breakdown.map(doc => doc.revenue);

            new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: labels,
                    datasets: [{
                        label: 'Revenue (₱)',
                        data: revenues,
                        backgroundColor: 'rgba(76, 175, 80, 0.6)',
                        borderColor: 'rgba(76, 175, 80, 1)',
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: {
                            display: false
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                callback: function(value) {
                                    return '₱' + value.toLocaleString();
                                }
                            }
                        },
                        x: {
                            ticks: {
                                maxRotation: 45,
                                minRotation: 45
                            }
                        }
                    }
                }
            });
        }
    </script>
    
    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</body>
</html>