<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
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
            <div class="user-profile">
                <img src="https://randomuser.me/api/portraits/men/75.jpg" alt="Admin" class="avatar">
                <div class="user-details">
                    <div class="user-name">Admin User</div>
                    <div class="user-role">Administrator</div>
                </div>
            </div>
            <div class="logout-link" onclick="logout()">
                <i class="fas fa-sign-out-alt logout-icon"></i>
                <span class="menu-text">Logout</span>
            </div>
        </div>
    </aside>

    <main class="main-content">
        <div class="header">
            <div class="page-title">
                <h1 id="pageTitle">Admin Dashboard</h1>
            </div>
            <div class="search-bar">
                <i class="fas fa-search search-icon"></i>
                <input type="text" placeholder="Search...">
            </div>
            <div class="user-actions">
                <div class="notification-icon">
                    <i class="fas fa-bell"></i>
                    <span class="notification-badge">5</span>
                </div>
            </div>
        </div>

        <!-- Dashboard Section -->
        <div class="dashboard-section" id="dashboardSection">
            <div class="user-management">
                <h2>User Management</h2>
                <button class="add-user-btn" onclick="openUserModal()">Add New User</button>
                <table class="user-table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Role</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>1</td>
                            <td>John Doe</td>
                            <td>john.doe@example.com</td>
                            <td>Registrar</td>
                            <td>
                                <button class="edit-btn"><i class="fas fa-edit"></i></button>
                                <button class="delete-btn"><i class="fas fa-trash-alt"></i></button>
                            </td>
                        </tr>
                        <tr>
                            <td>2</td>
                            <td>Jane Smith</td>
                            <td>jane.smith@example.com</td>
                            <td>Cashier</td>
                            <td>
                                <button class="edit-btn"><i class="fas fa-edit"></i></button>
                                <button class="delete-btn"><i class="fas fa-trash-alt"></i></button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <div class="reports-section">
                <h2>Reports Overview</h2>

                <div class="report-card">
                    <div class="report-details">
                        <h3>Cashier Daily Summary</h3>
                        <p>Total payments processed: 56, Total amount: $2,500</p>
                    </div>
                    <button class="view-report-btn">View Report</button>
                </div>

                <div class="report-card">
                    <div class="report-details">
                        <h3>Registrar Document Requests</h3>
                        <p>New requests: 89, Completed requests: 67</p>
                    </div>
                    <button class="view-report-btn">View Report</button>
                </div>

                <div class="report-card">
                    <div class="report-details">
                        <h3>Cashier Monthly Report</h3>
                        <p>Total payments: 1,200, Total amount: $55,000</p>
                    </div>
                    <button class="view-report-btn">View Report</button>
                </div>

                <div class="report-card">
                    <div class="report-details">
                        <h3>Registrar Document Type Breakdown</h3>
                        <p>Transcript: 60%, Diploma: 25%, Certification: 15%</p>
                    </div>
                    <button class="view-report-btn">View Report</button>
                </div>
            </div>
        </div>

        <!-- Users Section -->
        <div class="user-management" id="usersSection" style="display: none;">
            <h2>User Management</h2>
            <button class="add-user-btn" onclick="openUserModal()">Add New User</button>
            <div class="search-bar" style="margin-bottom: 1rem; width: 300px;">
                <i class="fas fa-search search-icon"></i>
                <input type="text" placeholder="Search users...">
            </div>
            <table class="user-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Role</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>1</td>
                        <td>John Doe</td>
                        <td>john.doe@example.com</td>
                        <td>Registrar</td>
                        <td><span style="color: var(--success);">Active</span></td>
                        <td>
                            <button class="edit-btn"><i class="fas fa-edit"></i></button>
                            <button class="delete-btn"><i class="fas fa-trash-alt"></i></button>
                            <button class="edit-btn"><i class="fas fa-key"></i></button>
                        </td>
                    </tr>
                    <tr>
                        <td>2</td>
                        <td>Jane Smith</td>
                        <td>jane.smith@example.com</td>
                        <td>Cashier</td>
                        <td><span style="color: var(--success);">Active</span></td>
                        <td>
                            <button class="edit-btn"><i class="fas fa-edit"></i></button>
                            <button class="delete-btn"><i class="fas fa-trash-alt"></i></button>
                            <button class="edit-btn"><i class="fas fa-key"></i></button>
                        </td>
                    </tr>
                    <tr>
                        <td>3</td>
                        <td>Robert Johnson</td>
                        <td>robert.j@example.com</td>
                        <td>Admin</td>
                        <td><span style="color: var(--danger);">Inactive</span></td>
                        <td>
                            <button class="edit-btn"><i class="fas fa-edit"></i></button>
                            <button class="delete-btn"><i class="fas fa-trash-alt"></i></button>
                            <button class="edit-btn"><i class="fas fa-key"></i></button>
                        </td>
                    </tr>
                    <tr>
                        <td>4</td>
                        <td>Sarah Williams</td>
                        <td>sarah.w@example.com</td>
                        <td>Registrar</td>
                        <td><span style="color: var(--success);">Active</span></td>
                        <td>
                            <button class="edit-btn"><i class="fas fa-edit"></i></button>
                            <button class="delete-btn"><i class="fas fa-trash-alt"></i></button>
                            <button class="edit-btn"><i class="fas fa-key"></i></button>
                        </td>
                    </tr>
                </tbody>
            </table>
            <div style="margin-top: 1rem; display: flex; justify-content: space-between; align-items: center;">
                <div style="font-size: 14px; color: var(--gray-dark);">
                    Showing 1 to 4 of 4 entries
                </div>
                <div style="display: flex; gap: 8px;">
                    <button class="btn btn-secondary" disabled>Previous</button>
                    <button class="btn btn-secondary active">1</button>
                    <button class="btn btn-secondary" disabled>Next</button>
                </div>
            </div>
        </div>

        <!-- Documents Section -->
        <div class="document-management" id="documentsSection">
            <h2>Document Types Management</h2>
            <button class="add-document-btn" onclick="openDocumentModal()">Add New Document Type</button>
            <div class="search-bar" style="margin-bottom: 1rem; width: 300px;">
                <i class="fas fa-search search-icon"></i>
                <input type="text" placeholder="Search documents...">
            </div>
            <table class="document-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Document Type</th>
                        <th>Description</th>
                        <th>Price</th>
                        <th>Processing Time</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>1</td>
                        <td>Transcript of Records</td>
                        <td>Official academic transcript</td>
                        <td><input type="number" class="price-input" value="250"></td>
                        <td>3-5 days</td>
                        <td>
                            <button class="edit-btn"><i class="fas fa-save"></i></button>
                            <button class="delete-btn"><i class="fas fa-trash-alt"></i></button>
                        </td>
                    </tr>
                    <tr>
                        <td>2</td>
                        <td>Diploma Copy</td>
                        <td>Certified copy of diploma</td>
                        <td><input type="number" class="price-input" value="350"></td>
                        <td>5-7 days</td>
                        <td>
                            <button class="edit-btn"><i class="fas fa-save"></i></button>
                            <button class="delete-btn"><i class="fas fa-trash-alt"></i></button>
                        </td>
                    </tr>
                    <tr>
                        <td>3</td>
                        <td>Certificate of Enrollment</td>
                        <td>Current enrollment status</td>
                        <td><input type="number" class="price-input" value="100"></td>
                        <td>1 day</td>
                        <td>
                            <button class="edit-btn"><i class="fas fa-save"></i></button>
                            <button class="delete-btn"><i class="fas fa-trash-alt"></i></button>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <!-- Reports Section -->
        <div class="reports-section" id="reportsSection" style="display: none;">
            <h2>Reports Overview</h2>

            <div class="report-card">
                <div class="report-details">
                    <h3>Cashier Daily Summary</h3>
                    <p>Total payments processed: 56, Total amount: $2,500</p>
                </div>
                <button class="view-report-btn">View Report</button>
            </div>

            <div class="report-card">
                <div class="report-details">
                    <h3>Registrar Document Requests</h3>
                    <p>New requests: 89, Completed requests: 67</p>
                </div>
                <button class="view-report-btn">View Report</button>
            </div>

            <div class="report-card">
                <div class="report-details">
                    <h3>Cashier Monthly Report</h3>
                    <p>Total payments: 1,200, Total amount: $55,000</p>
                </div>
                <button class="view-report-btn">View Report</button>
            </div>

            <div class="report-card">
                <div class="report-details">
                    <h3>Registrar Document Type Breakdown</h3>
                    <p>Transcript: 60%, Diploma: 25%, Certification: 15%</p>
                </div>
                <button class="view-report-btn">View Report</button>
            </div>
        </div>

        <!-- System Logs Section -->
        <div class="logs-section" id="logsSection">
            <h2>System Logs</h2>
            
            <div class="log-filter">
                <select>
                    <option>All Log Types</option>
                    <option>Info</option>
                    <option>Warning</option>
                    <option>Error</option>
                    <option>Success</option>
                </select>
                <input type="date" style="width: 150px;">
                <input type="text" placeholder="Search logs...">
                <button class="btn btn-primary">Filter</button>
            </div>
            
            <table class="log-table">
                <thead>
                    <tr>
                        <th>Timestamp</th>
                        <th>Type</th>
                        <th>User</th>
                        <th>Action</th>
                        <th>Details</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>2023-06-15 09:23:45</td>
                        <td><span class="log-type info">Info</span></td>
                        <td>admin@example.com</td>
                        <td>User login</td>
                        <td>Successful login from IP 192.168.1.100</td>
                    </tr>
                    <tr>
                        <td>2023-06-15 10:12:33</td>
                        <td><span class="log-type success">Success</span></td>
                        <td>admin@example.com</td>
                        <td>User created</td>
                        <td>Created new user jane.smith@example.com</td>
                    </tr>
                    <tr>
                        <td>2023-06-15 11:45:21</td>
                        <td><span class="log-type warning">Warning</span></td>
                        <td>john.doe@example.com</td>
                        <td>Failed login</td>
                        <td>3 failed login attempts</td>
                    </tr>
                    <tr>
                        <td>2023-06-15 13:30:15</td>
                        <td><span class="log-type error">Error</span></td>
                        <td>system</td>
                        <td>Database connection</td>
                        <td>Connection timeout for 30 seconds</td>
                    </tr>
                    <tr>
                        <td>2023-06-15 14:22:10</td>
                        <td><span class="log-type info">Info</span></td>
                        <td>admin@example.com</td>
                        <td>Password change</td>
                        <td>Password updated successfully</td>
                    </tr>
                </tbody>
            </table>
            
            <div style="margin-top: 1rem; display: flex; justify-content: space-between; align-items: center;">
                <div style="font-size: 14px; color: var(--gray-dark);">
                    Showing 1 to 5 of 42 entries
                </div>
                <div style="display: flex; gap: 8px;">
                    <button class="btn btn-secondary" disabled>Previous</button>
                    <button class="btn btn-secondary active">1</button>
                    <button class="btn btn-secondary">2</button>
                    <button class="btn btn-secondary">3</button>
                    <button class="btn btn-secondary">Next</button>
                </div>
            </div>
        </div>

        <div class="user-modal" id="userModal">
            <div class="modal-content">
                <div class="modal-header">
                    <h3 class="modal-title">Add/Edit User</h3>
                    <button class="close-btn" onclick="closeUserModal()">&times;</button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label class="form-label">Name</label>
                        <input type="text" class="form-input" placeholder="Enter name">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Email</label>
                        <input type="email" class="form-input" placeholder="Enter email">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Role</label>
                        <select class="form-input">
                            <option value="registrar">Registrar</option>
                            <option value="cashier">Cashier</option>
                            <option value="admin">Administrator</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Status</label>
                        <select class="form-input">
                            <option value="active">Active</option>
                            <option value="inactive">Inactive</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" onclick="closeUserModal()">Cancel</button>
                    <button class="btn btn-primary">Save User</button>
                </div>
            </div>
        </div>

        <!-- Document Modal -->
        <div class="user-modal" id="documentModal" style="display: none;">
            <div class="modal-content">
                <div class="modal-header">
                    <h3 class="modal-title">Add/Edit Document Type</h3>
                    <button class="close-btn" onclick="closeDocumentModal()">&times;</button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label class="form-label">Document Type</label>
                        <input type="text" class="form-input" placeholder="Enter document type">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Description</label>
                        <textarea class="form-input" placeholder="Enter description" rows="3"></textarea>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Price</label>
                        <input type="number" class="form-input" placeholder="Enter price">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Processing Time</label>
                        <input type="text" class="form-input" placeholder="e.g., 3-5 days">
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" onclick="closeDocumentModal()">Cancel</button>
                    <button class="btn btn-primary">Save Document</button>
                </div>
            </div>
        </div>
    </main>

    <script>
        document.querySelector('.toggle-btn').addEventListener('click', function() {
            document.querySelector('.sidebar').classList.toggle('collapsed');
        });

        function openUserModal() {
            document.getElementById('userModal').style.display = 'flex';
        }

        function closeUserModal() {
            document.getElementById('userModal').style.display = 'none';
        }

        function openDocumentModal() {
            document.getElementById('documentModal').style.display = 'flex';
        }

        function closeDocumentModal() {
            document.getElementById('documentModal').style.display = 'none';
        }

        function logout() {
            if (confirm('Are you sure you want to logout?')) {
                // Here you would typically redirect to your logout endpoint
                // For this example, we'll just show an alert
                alert('Logging out...');
                // In a real application, you would use something like:
                // window.location.href = '/logout';
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
            } else if (sectionId === 'documents') {
                document.getElementById('documentsSection').style.display = 'block';
                document.getElementById('pageTitle').textContent = 'Document Management';
            } else if (sectionId === 'reports') {
                document.getElementById('reportsSection').style.display = 'block';
                document.getElementById('pageTitle').textContent = 'Reports Overview';
            } else if (sectionId === 'logs') {
                document.getElementById('logsSection').style.display = 'block';
                document.getElementById('pageTitle').textContent = 'System Logs';
            }
        }
    </script>
</body>
</html>