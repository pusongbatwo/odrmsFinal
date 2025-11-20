@php
    $departments = [
        ["name" => "BACHELOR OF SCIENCE IN INFORMATION TECHNOLOGY", "img" => "/images/it.png"],
        ["name" => "BACHELOR OF SCIENCE IN ENTREPRENEURSHIP", "img" => "/images/bse.png"],
        ["name" => "BACHELOR OF SCIENCE IN CRIMINOLOGY", "img" => "/images/CRIM.png"],
        ["name" => "BACHELOR OF ELEMENTARY EDUCATION", "img" => "/images/beed.png"],
        ["name" => "BACHELOR OF EARLY CHILDHOOD EDUCATION", "img" => "/images/beced.png"],
        ["name" => "BACHELOR OF SCIENCE IN HOSPITALITY MANAGEMENT", "img" => "/images/hm.png"],
        ["name" => "BACHELOR OF SCIENCE IN PUBLIC ADMINISTRATION", "img" => "/images/BPA.jpg"],
    ];
@endphp
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Registrar Dashboard</title>
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Chart.js CDN -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
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

        /* Unified Progress Card Styles */
        .unified-progress-card {
            background: var(--light);
            border-radius: 12px;
            padding: 1.5rem;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
            text-align: center;
            transition: transform 0.3s ease;
            border-top: 3px solid var(--secondary);
            position: relative;
            grid-column: 1 / -1;
        }

        .unified-progress-card:hover {
            transform: translateY(-5px);
        }

        .unified-progress-container {
            position: relative;
            width: 100%;
            max-width: 320px;
            aspect-ratio: 1/1;
            margin: 0 auto;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .unified-progress-svg {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            transform: rotate(-90deg);
        }

        .progress-segment {
            transition: all 1.2s cubic-bezier(0.4, 0, 0.2, 1);
            transform-origin: center;
        }
        
        .completed-segment {
            animation: segment-animation 1.5s ease-out forwards;
            animation-delay: 0s;
        }
        
        .approved-segment {
            animation: segment-animation 1.5s ease-out forwards;
            animation-delay: 0.2s;
        }
        
        .pending-segment {
            animation: segment-animation 1.5s ease-out forwards;
            animation-delay: 0.4s;
        }
        
        .rejected-segment {
            animation: segment-animation 1.5s ease-out forwards;
            animation-delay: 0.6s;
        }
        
        @keyframes segment-animation {
            from {
                stroke-dasharray: 0 502.4;
                opacity: 0;
            }
            to {
                stroke-dasharray: var(--dash-array);
                opacity: 1;
            }
        }

        .pending-segment {
            stroke: #FFC107;
        }

        .approved-segment {
            stroke: #2196F3;
        }

        .completed-segment {
            stroke: #4CAF50;
        }

        .rejected-segment {
            stroke: #F44336;
        }

        .unified-progress-center {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            text-align: center;
            z-index: 10;
            width: 80%;
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        .total-requests {
            font-size: 3.5vw;
            font-weight: 700;
            color: var(--primary);
            line-height: 1;
            word-break: break-word;
        }

        .total-label {
            font-size: 1.2vw;
            color: var(--gray-dark);
            margin-top: 4px;
            word-break: break-word;
        @media (max-width: 600px) {
            .unified-progress-container {
                max-width: 220px;
            }
            .unified-progress-svg {
                max-width: 220px;
                max-height: 220px;
            }
            .unified-progress-center {
                width: 90%;
            }
            .total-requests {
                font-size: 28px;
            }
            .total-label {
                font-size: 12px;
            }
        }
        }

        .progress-legend {
            display: flex;
            flex-direction: row;
            gap: 0.5rem;
            margin-top: 0.5rem;
            margin-bottom: 0;
            align-items: center;
        @media (max-width: 600px) {
            .progress-legend {
                flex-direction: column;
                gap: 0.3rem;
                margin-top: 0.3rem;
                align-items: flex-start;
            }
            .legend-item {
                min-width: 0;
                font-size: 14px;
            }
        }
        }

        .legend-item {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 12px;
            background: #f8f9fa;
            border-radius: 8px;
            transition: transform 0.2s ease;
        }

        .legend-item:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .legend-color {
            width: 16px;
            height: 16px;
            border-radius: 50%;
            flex-shrink: 0;
        }

        .pending-color {
            background: #FFC107;
        }

        .approved-color {
            background: #2196F3;
        }

        .completed-color {
            background: #4CAF50;
        }

        .rejected-color {
            background: #F44336;
        }

        .legend-text {
            display: flex;
            flex-direction: column;
            flex-grow: 1;
        }

        .legend-label {
            font-size: 14px;
            font-weight: 600;
            color: var(--dark);
        }

        .legend-value {
            font-size: 18px;
            font-weight: 700;
            color: var(--primary);
        }
        
        .legend-percent {
            font-size: 14px;
            font-weight: 500;
            color: var(--gray-dark);
            margin-top: 2px;
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

        /* Release Notification Styles */
        .release-banner {
            background: linear-gradient(135deg, #e3f2fd 0%, #bbdefb 100%);
            border-left: 4px solid #2196F3;
            padding: 1rem 1.5rem;
            border-radius: 8px;
            margin-bottom: 1.5rem;
            display: flex;
            align-items: center;
            gap: 1rem;
            box-shadow: 0 2px 8px rgba(33, 150, 243, 0.2);
            animation: slideInDown 0.5s ease-out;
        }

        @keyframes slideInDown {
            from {
                opacity: 0;
                transform: translateY(-20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .release-banner.urgent {
            background: linear-gradient(135deg, #fff3cd 0%, #ffeaa7 100%);
            border-left-color: #ffc107;
        }

        .release-banner.overdue {
            background: linear-gradient(135deg, #ffe5e5 0%, #ffcccb 100%);
            border-left-color: #f44336;
        }

        .release-banner-icon {
            font-size: 24px;
            color: #2196F3;
        }

        .release-banner.urgent .release-banner-icon {
            color: #f57c00;
        }

        .release-banner.overdue .release-banner-icon {
            color: #f44336;
            animation: pulse 2s infinite;
        }

        @keyframes pulse {
            0%, 100% { transform: scale(1); }
            50% { transform: scale(1.1); }
        }

        .release-banner-content {
            flex-grow: 1;
        }

        .release-banner-title {
            font-weight: 600;
            color: #2196F3;
            margin-bottom: 0.25rem;
        }

        .release-banner.urgent .release-banner-title {
            color: #f57c00;
        }

        .release-banner.overdue .release-banner-title {
            color: #f44336;
        }

        .release-banner-text {
            font-size: 14px;
            color: #666;
        }

        .release-banner-close {
            background: none;
            border: none;
            font-size: 20px;
            color: #666;
            cursor: pointer;
            transition: color 0.2s;
        }

        .release-banner-close:hover {
            color: #333;
        }

        .release-badge {
            display: inline-flex;
            align-items: center;
            gap: 4px;
            padding: 2px 8px;
            border-radius: 12px;
            font-size: 11px;
            font-weight: 600;
            margin-left: 8px;
        }

        .release-badge.ready {
            background: rgba(76, 175, 80, 0.1);
            color: #4CAF50;
        }

        .release-badge.soon {
            background: rgba(33, 150, 243, 0.15);
            color: #2196F3;
        }

        .release-badge.urgent {
            background: rgba(255, 193, 7, 0.15);
            color: #f57c00;
        }

        .release-badge.overdue {
            background: rgba(244, 67, 54, 0.15);
            color: #f44336;
            animation: blink 2s infinite;
        }

        @keyframes blink {
            0%, 100% { opacity: 1; }
            50% { opacity: 0.7; }
        }

        .release-icon {
            font-size: 10px;
        }

        .request-row-release-soon {
            background: rgba(33, 150, 243, 0.05);
        }

        .request-row-release-urgent {
            background: rgba(255, 193, 7, 0.05);
        }

        .request-row-release-overdue {
            background: rgba(244, 67, 54, 0.08);
            border-left: 3px solid #f44336;
        }

        /* Specific Request Reminder Banner Styles */
        .specific-request-banner {
            background: linear-gradient(135deg, #e8f5e8 0%, #c8e6c9 100%);
            border-left: 4px solid #4CAF50;
            padding: 1rem 1.5rem;
            border-radius: 8px;
            margin-bottom: 1.5rem;
            display: flex;
            align-items: center;
            gap: 1rem;
            box-shadow: 0 2px 8px rgba(76, 175, 80, 0.2);
            animation: slideInDown 0.5s ease-out;
        }

        .specific-request-banner.urgent {
            background: linear-gradient(135deg, #fff3cd 0%, #ffeaa7 100%);
            border-left-color: #ffc107;
        }

        .specific-request-banner.critical {
            background: linear-gradient(135deg, #ffe5e5 0%, #ffcccb 100%);
            border-left-color: #f44336;
        }

        .specific-request-banner-icon {
            font-size: 24px;
            color: #4CAF50;
        }

        .specific-request-banner.urgent .specific-request-banner-icon {
            color: #f57c00;
        }

        .specific-request-banner.critical .specific-request-banner-icon {
            color: #f44336;
            animation: pulse 2s infinite;
        }

        .specific-request-banner-content {
            flex-grow: 1;
        }

        .specific-request-banner-title {
            font-weight: 600;
            color: #4CAF50;
            margin-bottom: 0.25rem;
        }

        .specific-request-banner.urgent .specific-request-banner-title {
            color: #f57c00;
        }

        .specific-request-banner.critical .specific-request-banner-title {
            color: #f44336;
        }

        .specific-request-banner-text {
            font-size: 14px;
            color: #666;
        }

        .specific-request-banner-close {
            background: none;
            border: none;
            font-size: 20px;
            color: #666;
            cursor: pointer;
            transition: color 0.2s;
        }

        .specific-request-banner-close:hover {
            color: #333;
        }

        .specific-request-details {
            background: rgba(255, 255, 255, 0.7);
            padding: 0.5rem 1rem;
            border-radius: 6px;
            margin-top: 0.5rem;
            font-size: 13px;
        }

        .requester-name {
            font-weight: 600;
            color: #2e7d32;
        }

        .days-remaining {
            font-weight: 600;
            color: #f57c00;
        }

        .days-remaining.critical {
            color: #f44336;
            animation: blink 2s infinite;
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

        .notify-btn {
            background: #4CAF50;
            color: white;
        }

        .notify-btn:hover {
            background: #45a049;
            transform: translateY(-1px);
        }

        .notify-btn:disabled {
            background: #ccc;
            cursor: not-allowed;
            transform: none;
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
            
            /* Unified Progress Card Mobile Styles */
            .unified-progress-container {
                width: 150px;
                height: 150px;
            }
            
            .unified-progress-svg {
                width: 150px;
                height: 150px;
            }
            
            .total-requests {
                font-size: 24px;
            }
            
            .total-label {
                font-size: 12px;
            }
            
            .progress-legend {
                grid-template-columns: 1fr;
                gap: 0.75rem;
            }
            
            .legend-item {
                padding: 10px;
            }
            
            .legend-value {
                font-size: 16px;
            }
            
            /* Document Progress Bars Mobile Styles */
            .document-progress-section {
                margin: 1rem 0;
                padding: 1rem;
            }
            
            .progress-bars-container {
                grid-template-columns: repeat(auto-fit, minmax(100px, 1fr)) !important;
                gap: 0.75rem !important;
                height: 200px !important;
            }
            
            .progress-bar-container {
                height: 140px !important;
                width: 28px !important;
            }
            
            .document-count {
                font-size: 1rem !important;
            }
            
            .document-percentage {
                font-size: 0.7rem !important;
            }
            
            .document-type-name {
                font-size: 0.6rem !important;
                max-width: 80px !important;
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

        /* Progress Bar Styles */
        .progress-bar-fill {
            min-height: 4px !important;
            opacity: 1 !important;
            visibility: visible !important;
        }

        .progress-bar-container {
            position: relative;
            background: #e9ecef !important;
            border-radius: 6px !important;
            overflow: hidden !important;
        }

        .progress-bar-item {
            opacity: 0;
            animation: fadeInUp 0.6s ease forwards;
        }

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .progress-bar-item:nth-child(1) { animation-delay: 0.1s; }
        .progress-bar-item:nth-child(2) { animation-delay: 0.2s; }
        .progress-bar-item:nth-child(3) { animation-delay: 0.3s; }
        .progress-bar-item:nth-child(4) { animation-delay: 0.4s; }
        .progress-bar-item:nth-child(5) { animation-delay: 0.5s; }
        .progress-bar-item:nth-child(6) { animation-delay: 0.6s; }
        .progress-bar-item:nth-child(7) { animation-delay: 0.7s; }
        .progress-bar-item:nth-child(8) { animation-delay: 0.8s; }
        
        /* Ensure progress bars are visible and properly sized */
        .progress-bar-fill {
            min-height: 4px !important;
            opacity: 1 !important;
            visibility: visible !important;
            display: block !important;
        }
        
        /* Force progress bar heights to be visible */
        .progress-bar-container {
            position: relative !important;
            background: #e9ecef !important;
            border-radius: 6px !important;
            overflow: hidden !important;
        }
        
        /* Editable cell styles */
        .editable-cell {
            position: relative;
            cursor: pointer;
            transition: background-color 0.2s;
        }
        
        .editable-cell:hover {
            background-color: #f0f8ff !important;
        }
        
        .editable-cell.editing {
            background-color: #fff3cd !important;
            padding: 0 !important;
        }
        
        .editable-input {
            width: 100%;
            height: 100%;
            padding: 8px 12px;
            border: 2px solid #8B0000;
            border-radius: 4px;
            font-size: 14px;
            font-family: inherit;
            background: #fff;
            outline: none;
        }
        
        .editable-select {
            width: 100%;
            height: 100%;
            padding: 8px 12px;
            border: 2px solid #8B0000;
            border-radius: 4px;
            font-size: 14px;
            font-family: inherit;
            background: #fff;
            outline: none;
            cursor: pointer;
        }
        
        .edit-icon {
            position: absolute;
            top: 2px;
            right: 2px;
            font-size: 10px;
            color: #8B0000;
            opacity: 0;
            transition: opacity 0.2s;
        }
        
        .editable-cell:hover .edit-icon {
            opacity: 1;
        }
        
        .save-indicator {
            display: inline-block;
            margin-left: 8px;
            font-size: 12px;
        }
        
        .save-indicator.saving {
            color: #FFC107;
        }
        
        .save-indicator.saved {
            color: #4CAF50;
        }
        
        .save-indicator.error {
            color: #F44336;
        }

    </style>
</head>
<body>
    @php
        $departments = [
            ["name" => "BACHELOR OF SCIENCE IN INFORMATION TECHNOLOGY", "img" => "/images/it.png"],
            ["name" => "BACHELOR OF SCIENCE IN ENTREPRENEURSHIP", "img" => "/images/bse.png"],
            ["name" => "BACHELOR OF SCIENCE IN CRIMINOLOGY", "img" => "/images/CRIM.png"],
            ["name" => "BACHELOR OF ELEMENTARY EDUCATION", "img" => "/images/beed.png"],
            ["name" => "BACHELOR OF EARLY CHILDHOOD EDUCATION", "img" => "/images/beced.png"],
            ["name" => "BACHELOR OF SCIENCE IN HOSPITALITY MANAGEMENT", "img" => "/images/hm.png"],
            ["name" => "BACHELOR OF SCIENCE IN PUBLIC ADMINISTRATION", "img" => "/images/BPA.jpg"],
        ];
    @endphp
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
                <!-- Live Chat menu removed per request (UI-only change) -->
            </ul>
        </div>
        <div class="sidebar-footer">
            <div class="user-profile">
                <img src="{{ auth()->user() && auth()->user()->avatar ? asset('storage/' . auth()->user()->avatar) : 'https://randomuser.me/api/portraits/women/45.jpg' }}" alt="User" class="avatar" id="profileAvatar">
                <div class="user-details">
                    <div class="user-name" id="profileUsername">{{ auth()->user() ? auth()->user()->username : 'Guest' }}</div>
                    <div class="user-role">{{ auth()->user() ? ucfirst(auth()->user()->role) : 'Role' }}</div>
                </div>
            </div>
        </div>
    </aside>
    <!-- Profile Edit Modal -->
    <div id="profileEditModal" style="display:none; position:fixed; top:0; left:0; width:100vw; height:100vh; background:rgba(0,0,0,0.4); z-index:2000; align-items:center; justify-content:center;">
        <div style="background:#fff; border-radius:12px; padding:2rem; width:350px; max-width:95vw; position:relative; box-shadow:0 4px 24px rgba(0,0,0,0.15);">
            <button id="closeProfileModal" style="position:absolute; top:12px; right:12px; background:none; border:none; font-size:22px; color:#8B0000; cursor:pointer;">&times;</button>
            <h2 style="text-align:center; color:#8B0000; font-size:20px; margin-bottom:1.2rem;">Edit Profile</h2>
            <form id="profileEditForm" enctype="multipart/form-data" method="POST" action="{{ route('registrar.profile.update') }}">
                @csrf
                <div style="text-align:center; margin-bottom:1rem;">
                    <img id="editAvatarPreview" src="{{ auth()->user() && auth()->user()->avatar ? asset('storage/' . auth()->user()->avatar) : 'https://randomuser.me/api/portraits/women/45.jpg' }}" alt="Avatar" style="width:80px; height:80px; border-radius:50%; object-fit:cover; border:2px solid #8B0000;">
                    <br>
                    <input type="file" name="avatar" id="avatarInput" accept="image/*" style="margin-top:8px;">
                </div>
                <div style="margin-bottom:1rem;">
                    <label for="username" style="font-weight:600; color:#8B0000;">Username</label>
                    <input type="text" name="username" id="editUsername" value="{{ auth()->user() ? auth()->user()->username : '' }}" style="width:100%; padding:8px; border-radius:6px; border:1px solid #ccc; margin-top:4px;">
                </div>
                <div style="margin-bottom:1rem;">
                    <label for="password" style="font-weight:600; color:#8B0000;">New Password</label>
                    <input type="password" name="password" id="editPassword" style="width:100%; padding:8px; border-radius:6px; border:1px solid #ccc; margin-top:4px;">
                </div>
                <div style="margin-bottom:1rem;">
                    <label for="password_confirmation" style="font-weight:600; color:#8B0000;">Confirm Password</label>
                    <input type="password" name="password_confirmation" id="editPasswordConfirm" style="width:100%; padding:8px; border-radius:6px; border:1px solid #ccc; margin-top:4px;">
                </div>
                <button type="submit" style="width:100%; background:#8B0000; color:#fff; border:none; border-radius:6px; padding:10px; font-size:15px; font-weight:600; cursor:pointer;">Save Changes</button>
            </form>
            <form method="POST" action="{{ route('logout') }}" style="margin-top:1.2rem;">
                @csrf
                <button type="submit" style="width:100%; background:#F44336; color:#fff; border:none; border-radius:6px; padding:10px; font-size:15px; font-weight:600; cursor:pointer;">Logout</button>
            </form>
        </div>
    </div>
    <main class="main-content">
        <div class="header">
            <div class="page-title">
                <h1 id="pageTitle">Document Request Dashboard</h1>
            </div>
            

        </div>

        <script>
        // Profile modal logic
        document.addEventListener('DOMContentLoaded', function() {
            var modal = document.getElementById('profileEditModal');
            var closeBtn = document.getElementById('closeProfileModal');
            var avatarInput = document.getElementById('avatarInput');
            var avatarPreview = document.getElementById('editAvatarPreview');
            var profileAvatar = document.getElementById('profileAvatar');
            var profileUsername = document.getElementById('profileUsername');
            var editUsername = document.getElementById('editUsername');

            // Show modal when user-profile is clicked
            var userProfile = document.querySelector('.user-profile');
            if (userProfile) {
                userProfile.onclick = function() {
                    modal.style.display = 'flex';
                    avatarPreview.src = profileAvatar.src;
                    editUsername.value = profileUsername.textContent;
                };
            }
            closeBtn.onclick = function() {
                modal.style.display = 'none';
            };
            avatarInput.onchange = function(e) {
                if (e.target.files && e.target.files[0]) {
                    var reader = new FileReader();
                    reader.onload = function(ev) {
                        avatarPreview.src = ev.target.result;
                    };
                    reader.readAsDataURL(e.target.files[0]);
                }
            };
        });
        </script>
        <!-- Release Notification Banner -->
        <div id="releaseBanner" style="display: none;"></div>
        
        <!-- Specific Document Request Reminder Banner -->
        <div id="specificRequestBanner" style="display: none;"></div>

        <!-- Dashboard Sections -->
        <div class="dashboard-sections" id="dashboardSections" style="display: block;">
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
                <div class="unified-progress-card" style="display: flex; flex-direction: column; align-items: center; justify-content: center; min-height: 340px;">
                    <div style="width: 100%; text-align: center; margin-bottom: 12px;">
                        <span style="font-size: 24px; font-weight: 700; color: #8B0000; letter-spacing: 1px;">Request Status Overview</span>
                    </div>
                    <div style="display: flex; flex-direction: row; align-items: center; justify-content: center; max-width: 540px; width: 100%; margin: 0 auto 0.5rem auto; padding: 0.5rem 0; gap: 0.1rem;">
                    @php
                        $total = ($analytics['pending'] ?? 0) + ($analytics['approved'] ?? 0) + ($analytics['completed'] ?? 0) + ($analytics['rejected'] ?? 0);
                        $pendingPercent = $total > 0 ? ($analytics['pending'] ?? 0) / $total * 100 : 0;
                        $approvedPercent = $total > 0 ? ($analytics['approved'] ?? 0) / $total * 100 : 0;
                        $completedPercent = $total > 0 ? ($analytics['completed'] ?? 0) / $total * 100 : 0;
                        $rejectedPercent = $total > 0 ? ($analytics['rejected'] ?? 0) / $total * 100 : 0;
                        $circumference = 2 * M_PI * 80;
                        $pendingDash = $total > 0 ? ($pendingPercent / 100) * $circumference : 0;
                        $approvedDash = $total > 0 ? ($approvedPercent / 100) * $circumference : 0;
                        $completedDash = $total > 0 ? ($completedPercent / 100) * $circumference : 0;
                        $rejectedDash = $total > 0 ? ($rejectedPercent / 100) * $circumference : 0;
                    @endphp
                        <div class="progress-legend" style="flex: 0.8; display: flex; flex-direction: column; gap: 0.1rem; align-items: flex-center; justify-content: center; min-width: 120px; margin-right: 0; padding: 0 0.2rem;">
                        <div class="legend-item" data-status="rejected" style="display: flex; align-items: center; gap: 5px; padding: 2px 4px; min-width: 120px; cursor: pointer; transition: background 0.2s;">
                            <div class="legend-color rejected-color" style="width: 22px; height: 22px;"></div>
                            <span class="legend-label" style="font-size: 18px; font-weight: 600; color: #F44336;">Rejected</span>
                        </div>
                        <div class="legend-item" data-status="pending" style="display: flex; align-items: center; gap: 6px; padding: 2px 4px; min-width: 120px; cursor: pointer; transition: background 0.2s;">
                            <div class="legend-color pending-color" style="width: 22px; height: 22px;"></div>
                            <span class="legend-label" style="font-size: 18px; font-weight: 600; color: #FFC107;">Pending</span>
                        </div>
                        <div class="legend-item" data-status="approved" style="display: flex; align-items: center; gap: 6px; padding: 2px 4px; min-width: 120px; cursor: pointer; transition: background 0.2s;">
                            <div class="legend-color approved-color" style="width: 22px; height: 22px;"></div>
                            <span class="legend-label" style="font-size: 18px; font-weight: 600; color: #2196F3;">Approved</span>
                        </div>
                        <div class="legend-item" data-status="completed" style="display: flex; align-items: center; gap: 6px; padding: 2px 4px; min-width: 120px; cursor: pointer; transition: background 0.2s;">
                            <div class="legend-color completed-color" style="width: 22px; height: 22px;"></div>
                            <span class="legend-label" style="font-size: 18px; font-weight: 600; color: #4CAF50;">Completed</span>
                        </div>
                    </div>
                        <div class="unified-progress-container" style="flex: 1.1; width: 320px; height: 320px; min-width: 320px; min-height: 320px; margin-left: 0.5rem; position: relative; display: flex; align-items: center; justify-content: center;">
                        <svg class="unified-progress-svg" viewBox="0 0 200 200">
                            <!-- Background circle -->
                            <circle cx="100" cy="100" r="80" fill="none" stroke="#f0f0f0" stroke-width="16"/>
                            <!-- Rejected Requests (Red) - First -->
                            <circle id="rejectedSegment" cx="100" cy="100" r="80" fill="none" stroke="#F44336" stroke-width="16" 
                                stroke-dasharray="{{ $rejectedDash }} {{ $circumference - $rejectedDash }}" 
                                stroke-dashoffset="0" 
                                class="progress-segment rejected-segment" 
                                style="--dash-array: {{ $rejectedDash }} {{ $circumference - $rejectedDash }}; --percent: {{ $rejectedPercent }}; transition: opacity 0.5s;"/>
                            <!-- Pending Requests (Yellow) - After rejected -->
                            <circle id="pendingSegment" cx="100" cy="100" r="80" fill="none" stroke="#FFC107" stroke-width="16" 
                                stroke-dasharray="{{ $pendingDash }} {{ $circumference - $pendingDash }}" 
                                stroke-dashoffset="{{ -$rejectedDash }}" 
                                class="progress-segment pending-segment" 
                                style="--dash-array: {{ $pendingDash }} {{ $circumference - $pendingDash }}; --dash-offset: {{ -$rejectedDash }}; --percent: {{ $pendingPercent }}; transition: opacity 0.5s;"/>
                            <!-- Approved Requests (Blue) - After pending -->
                            <circle id="approvedSegment" cx="100" cy="100" r="80" fill="none" stroke="#2196F3" stroke-width="16" 
                                stroke-dasharray="{{ $approvedDash }} {{ $circumference - $approvedDash }}" 
                                stroke-dashoffset="{{ -($rejectedDash + $pendingDash) }}" 
                                class="progress-segment approved-segment" 
                                style="--dash-array: {{ $approvedDash }} {{ $circumference - $approvedDash }}; --dash-offset: {{ -($rejectedDash + $pendingDash) }}; --percent: {{ $approvedPercent }}; transition: opacity 0.5s;"/>
                            <!-- Completed Requests (Green) - After approved -->
                            <circle id="completedSegment" cx="100" cy="100" r="80" fill="none" stroke="#4CAF50" stroke-width="16" 
                                stroke-dasharray="{{ $completedDash }} {{ $circumference - $completedDash }}" 
                                stroke-dashoffset="{{ -($rejectedDash + $pendingDash + $approvedDash) }}" 
                                class="progress-segment completed-segment" 
                                style="--dash-array: {{ $completedDash }} {{ $circumference - $completedDash }}; --percent: {{ $completedPercent }}; transition: opacity 0.5s;"/>
                        </svg>
                            <div class="unified-progress-center" style="top: 50%; left: 50%; transform: translate(-50%, -50%); display: flex; flex-direction: column; align-items: center; justify-content: center; width: 100%;">
                                <div class="total-requests" id="totalRequests">{{ $total }}</div>
                                <div class="total-label" id="centerLabel">Total Requests</div>
                            </div>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Backup/Sync Student Records to Google Drive with SweetAlert
    const backSyncStudentRecordsBtn = document.getElementById('backSyncStudentRecordsBtn');
    backSyncStudentRecordsBtn.addEventListener('click', function() {
        // Show loading SweetAlert
        Swal.fire({
            title: 'Processing...',
            html: '<div style="display: flex; flex-direction: column; align-items: center; gap: 20px;"><div class="loading" style="width: 50px; height: 50px; border: 5px solid rgba(139, 0, 0, 0.3); border-radius: 50%; border-top-color: #8B0000; animation: spin 1s ease-in-out infinite;"></div><p style="margin: 0; font-size: 16px; color: #666;">Backing up student records to Google Drive...</p></div>',
            allowOutsideClick: false,
            allowEscapeKey: false,
            showConfirmButton: false,
            width: 400,
            customClass: {
                popup: 'swal-custom-popup'
            }
        });

        // Add CSS for loading animation
        if (!document.getElementById('swal-loading-styles')) {
            const style = document.createElement('style');
            style.id = 'swal-loading-styles';
            style.textContent = `
                @keyframes spin {
                    to { transform: rotate(360deg); }
                }
                .swal-custom-popup {
                    border-radius: 15px !important;
                }
            `;
            document.head.appendChild(style);
        }

        // Simulate processing delay and show delayed message
        setTimeout(() => {
            Swal.fire({
                title: 'Slow Internet Connection',
                html: '<div style="display: flex; flex-direction: column; align-items: center; gap: 15px;"><i class="fas fa-exclamation-triangle" style="font-size: 48px; color: #ffc107;"></i><p style="margin: 0; font-size: 16px; color: #666;">The backup process is taking longer than expected due to slow internet connection. Please try again later.</p></div>',
                icon: 'warning',
                confirmButtonText: 'Try Again Later',
                confirmButtonColor: '#8B0000',
                width: 450,
                customClass: {
                    popup: 'swal-custom-popup'
                }
            });
        }, 3000); // 3 second delay
    });

    // Print Student Records
    const printBtn = document.getElementById('printStudentRecordsBtn');
    printBtn.addEventListener('click', function() {
        const tableSection = document.getElementById('studentRecordsTableSection');
        const table = tableSection.querySelector('table');
        if (!table) {
            alert('No student records to print.');
            return;
        }
        const printWindow = window.open('', '', 'width=900,height=700');
        printWindow.document.write('<html><head><title>Print Student Records</title>');
        printWindow.document.write('<style>table{width:100%;border-collapse:collapse;}th,td{border:1px solid #888;padding:8px;text-align:left;}th{background:#f5f5f5;}</style>');
        printWindow.document.write('</head><body>');
        printWindow.document.write('<h2>Student Records</h2>');
        printWindow.document.write(table.outerHTML);
        printWindow.document.write('</body></html>');
        printWindow.document.close();
        printWindow.focus();
        printWindow.print();
        printWindow.close();
    });
    // Export Student Records to CSV
    const exportBtn = document.getElementById('exportStudentRecordsBtn');
    exportBtn.addEventListener('click', function() {
        let rows = Array.from(document.querySelectorAll('#studentRecordsTableSection table tbody tr'));
        if (rows.length === 0) {
            alert('No student records to export.');
            return;
        }
        let csv = 'Student ID,Name,Program,Year Level,School Year\n';
        rows.forEach(row => {
            let cols = Array.from(row.querySelectorAll('td')).map(td => '"' + td.innerText.replace(/"/g, '""') + '"');
            csv += cols.join(',') + '\n';
        });
        let blob = new Blob([csv], { type: 'text/csv' });
        let link = document.createElement('a');
        link.href = URL.createObjectURL(blob);
        link.download = 'student_records.csv';
        document.body.appendChild(link);
        link.click();
        document.body.removeChild(link);
    });
    // Document Request Release Notification System
    function applyReleaseStyles() {
        // Apply release badges and row styles to all release cells
        document.querySelectorAll('.release-cell').forEach(cell => {
            const days = parseInt(cell.getAttribute('data-days'));
            const row = cell.closest('tr');
            
            // Remove existing release classes
            cell.classList.remove('ready', 'soon', 'urgent', 'overdue');
            if (row) {
                row.classList.remove('request-row-release-soon', 'request-row-release-urgent', 'request-row-release-overdue');
            }
            
            // Apply styling based on days until release
            if (days < 0) {
                // Overdue (past release date) - Red with blinking
                cell.innerHTML = `<span class="release-badge overdue">
                    <i class="fas fa-exclamation-triangle release-icon"></i> ${Math.abs(days)} ${Math.abs(days) === 1 ? 'day' : 'days'} overdue
                </span>`;
                if (row) row.classList.add('request-row-release-overdue');
            } else if (days === 0) {
                // Release today - Urgent
                cell.innerHTML = `<span class="release-badge urgent">
                    <i class="fas fa-clock release-icon"></i> Release Today!
                </span>`;
                if (row) row.classList.add('request-row-release-urgent');
            } else if (days <= 2) {
                // 1-2 days - Urgent (Yellow/Orange)
                cell.innerHTML = `<span class="release-badge urgent">
                    <i class="fas fa-hourglass-half release-icon"></i> ${days} ${days === 1 ? 'day' : 'days'} left
                </span>`;
                if (row) row.classList.add('request-row-release-urgent');
            } else if (days <= 4) {
                // 3-4 days - Soon (Blue)
                cell.innerHTML = `<span class="release-badge soon">
                    <i class="fas fa-calendar-check release-icon"></i> ${days} days left
                </span>`;
                if (row) row.classList.add('request-row-release-soon');
            } else {
                // 5+ days - Ready (Green)
                cell.innerHTML = `<span class="release-badge ready">
                    <i class="fas fa-check-circle release-icon"></i> ${days} days left
                </span>`;
            }
        });
    }
    
    // Variable to track if banner was manually dismissed
    let bannerDismissedAt = null;
    let bannerReappearTimer = null;
    let bannerCountdownInterval = null;
    const BANNER_REAPPEAR_DELAY = 5000; // 5 seconds
    
    // Make closeReleaseBanner globally accessible
    window.closeReleaseBanner = function() {
        const banner = document.getElementById('releaseBanner');
        
        // Clear any existing timers
        if (bannerReappearTimer) clearTimeout(bannerReappearTimer);
        if (bannerCountdownInterval) clearInterval(bannerCountdownInterval);
        
        bannerDismissedAt = Date.now();
        
        // Show a small notification that it will reappear
        banner.innerHTML = `
            <div style="background: rgba(33, 150, 243, 0.05); border-left: 3px solid #2196F3; padding: 0.5rem 1rem; border-radius: 6px; display: flex; align-items: center; gap: 0.5rem; font-size: 13px; color: #666;">
                <i class="fas fa-info-circle" style="color: #2196F3;"></i>
                <span>Notification dismissed. Will reappear in <strong id="bannerCountdown">5</strong> seconds...</span>
            </div>
        `;
        banner.style.display = 'block';
        
        // Countdown timer
        let secondsLeft = 5;
        bannerCountdownInterval = setInterval(() => {
            secondsLeft--;
            const countdownElement = document.getElementById('bannerCountdown');
            if (countdownElement) {
                countdownElement.textContent = secondsLeft;
            }
            if (secondsLeft <= 0) {
                clearInterval(bannerCountdownInterval);
            }
        }, 1000);
        
        // Schedule reappearance after delay
        bannerReappearTimer = setTimeout(() => {
            clearInterval(bannerCountdownInterval);
            updateReleaseBanner(true);
        }, BANNER_REAPPEAR_DELAY);
    };
    
    function updateReleaseBanner(forceShow = false) {
        const allRequests = document.querySelectorAll('#requestTable tbody tr, #documentRequestsTable tbody tr');
        const completedRequests = Array.from(allRequests).filter(row => {
            const status = row.getAttribute('data-status');
            return status && status.toLowerCase() === 'completed';
        });
        
        // Count release urgency
        let overdueCount = 0;
        let todayCount = 0;
        let urgentCount = 0; // 1-2 days
        let soonCount = 0; // 3-4 days
        
        completedRequests.forEach(row => {
            const days = parseInt(row.getAttribute('data-days-until-release'));
            if (days < 0) {
                overdueCount++;
            } else if (days === 0) {
                todayCount++;
            } else if (days <= 2) {
                urgentCount++;
            } else if (days <= 4) {
                soonCount++;
            }
        });
        
        const totalNeedingAttention = overdueCount + todayCount + urgentCount;
        const banner = document.getElementById('releaseBanner');
        
        if (totalNeedingAttention > 0) {
            // Check if we should show the banner
            const shouldShow = forceShow || !bannerDismissedAt || (Date.now() - bannerDismissedAt) >= BANNER_REAPPEAR_DELAY;
            
            if (shouldShow) {
                const isOverdue = overdueCount > 0;
                const isUrgent = todayCount > 0 || urgentCount > 0;
                const bannerClass = isOverdue ? 'release-banner overdue' : (isUrgent ? 'release-banner urgent' : 'release-banner');
                const iconClass = isOverdue ? 'fas fa-exclamation-triangle' : (isUrgent ? 'fas fa-clock' : 'fas fa-box');
                const title = isOverdue ? 'Overdue: Documents Not Released!' : (todayCount > 0 ? 'Documents Ready for Release Today!' : 'Upcoming Document Releases');
                
                let message = '';
                if (overdueCount > 0) {
                    message += `<strong>${overdueCount}</strong> document${overdueCount > 1 ? 's' : ''} <strong>overdue for release</strong>`;
                }
                if (todayCount > 0) {
                    if (message) message += ', ';
                    message += `<strong>${todayCount}</strong> document${todayCount > 1 ? 's' : ''} ready for <strong>release today</strong>`;
                }
                if (urgentCount > 0) {
                    if (message) message += ', ';
                    message += `<strong>${urgentCount}</strong> document${urgentCount > 1 ? 's' : ''} ready in <strong>1-2 days</strong>`;
                }
                
                banner.innerHTML = `
                    <div class="${bannerClass}">
                        <i class="${iconClass} release-banner-icon"></i>
                        <div class="release-banner-content">
                            <div class="release-banner-title">${title}</div>
                            <div class="release-banner-text">${message}</div>
                        </div>
                        <button class="release-banner-close" onclick="closeReleaseBanner()" title="Dismiss for 5 seconds">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                `;
                banner.style.display = 'block';
                bannerDismissedAt = null; // Reset dismiss timestamp when showing
                
                // Clear countdown interval if it exists
                if (bannerCountdownInterval) {
                    clearInterval(bannerCountdownInterval);
                    bannerCountdownInterval = null;
                }
            }
        } else {
            banner.style.display = 'none';
            bannerDismissedAt = null;
        }
    }
    
    
    // Specific Document Request Reminder System
    function updateSpecificRequestBanner() {
        const allRequests = document.querySelectorAll('#requestTable tbody tr, #documentRequestsTable tbody tr');
        const completedRequests = Array.from(allRequests).filter(row => {
            const status = row.getAttribute('data-status');
            return status && status.toLowerCase() === 'completed';
        });
        
        // Find the request with exactly 6 days remaining
        let targetRequest = null;
        let targetDays = 6;
        
        // Look for requests with 6 days remaining
        for (let request of completedRequests) {
            const daysUntilRelease = parseInt(request.getAttribute('data-days-until-release'));
            if (daysUntilRelease === targetDays) {
                targetRequest = request;
                break;
            }
        }
        
        // If no 6-day request found, look for the closest one (5-7 days range)
        if (!targetRequest) {
            for (let request of completedRequests) {
                const daysUntilRelease = parseInt(request.getAttribute('data-days-until-release'));
                if (daysUntilRelease >= 5 && daysUntilRelease <= 7) {
                    targetRequest = request;
                    targetDays = daysUntilRelease;
                    break;
                }
            }
        }
        
        const banner = document.getElementById('specificRequestBanner');
        
        if (targetRequest) {
            // Extract request details
            const requestId = targetRequest.cells[0].textContent;
            const studentName = targetRequest.cells[1].textContent;
            const course = targetRequest.cells[2].textContent;
            
            // Determine banner style based on urgency
            let bannerClass = 'specific-request-banner';
            let iconClass = 'fas fa-calendar-check';
            let title = 'Document Release Reminder';
            
            if (targetDays <= 2) {
                bannerClass += ' critical';
                iconClass = 'fas fa-exclamation-triangle';
                title = 'URGENT: Document Release Due Soon!';
            } else if (targetDays <= 4) {
                bannerClass += ' urgent';
                iconClass = 'fas fa-clock';
                title = 'Document Release Reminder';
            }
            
            banner.innerHTML = `
                <div class="${bannerClass}">
                    <i class="${iconClass} specific-request-banner-icon"></i>
                    <div class="specific-request-banner-content">
                        <div class="specific-request-banner-title">${title}</div>
                        <div class="specific-request-banner-text">
                            A completed document request is ready for release
                        </div>
                        <div class="specific-request-details">
                            <strong>Request ID:</strong> ${requestId} | 
                            <strong>Requester:</strong> <span class="requester-name">${studentName}</span> | 
                            <strong>Course:</strong> ${course} | 
                            <strong>Days Remaining:</strong> <span class="days-remaining ${targetDays <= 2 ? 'critical' : ''}">${targetDays} ${targetDays === 1 ? 'day' : 'days'}</span>
                        </div>
                    </div>
                    <button class="specific-request-banner-close" onclick="closeSpecificRequestBanner()" title="Dismiss for 5 seconds">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
            `;
            banner.style.display = 'block';
        } else {
            banner.style.display = 'none';
        }
    }
    
    // Make closeSpecificRequestBanner globally accessible
    window.closeSpecificRequestBanner = function() {
        const banner = document.getElementById('specificRequestBanner');
        banner.style.display = 'none';
        
        // Show a small notification that it will reappear
        banner.innerHTML = `
            <div style="background: rgba(76, 175, 80, 0.05); border-left: 3px solid #4CAF50; padding: 0.5rem 1rem; border-radius: 6px; display: flex; align-items: center; gap: 0.5rem; font-size: 13px; color: #666;">
                <i class="fas fa-info-circle" style="color: #4CAF50;"></i>
                <span>Reminder dismissed. Will check for new requests in 5 seconds...</span>
            </div>
        `;
        banner.style.display = 'block';
        
        // Schedule reappearance after 5 seconds
        setTimeout(() => {
            updateSpecificRequestBanner();
        }, 5000);
    };
    
    // Send pickup notification function
    window.sendPickupNotification = function(button) {
        const requestId = button.getAttribute('data-request-id');
        const email = button.getAttribute('data-email');
        const firstName = button.getAttribute('data-first-name');
        const lastName = button.getAttribute('data-last-name');
        const daysUntilRelease = button.getAttribute('data-days-until-release');
        
        // Disable button to prevent multiple clicks
        button.disabled = true;
        button.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Sending...';
        
        // Send AJAX request to send notification
        fetch('/registrar/send-pickup-notification', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({
                request_id: requestId,
                email: email,
                first_name: firstName,
                last_name: lastName,
                days_until_release: daysUntilRelease
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Show success message
                button.innerHTML = '<i class="fas fa-check"></i> Sent!';
                button.style.background = '#4CAF50';
                
                // Show success notification
                if (typeof Swal !== 'undefined') {
                    Swal.fire({
                        icon: 'success',
                        title: 'Notification Sent!',
                        text: `Pickup notification has been sent to ${firstName} ${lastName}`,
                        timer: 3000,
                        showConfirmButton: false
                    });
                } else {
                    alert(`Pickup notification sent to ${firstName} ${lastName}`);
                }
                
                // Re-enable button after 3 seconds
                setTimeout(() => {
                    button.disabled = false;
                    button.innerHTML = '<i class="fas fa-envelope"></i> Notify Pickup';
                    button.style.background = '#4CAF50';
                }, 3000);
            } else {
                throw new Error(data.message || 'Failed to send notification');
            }
        })
        .catch(error => {
            console.error('Error sending notification:', error);
            
            // Show error message
            button.innerHTML = '<i class="fas fa-times"></i> Failed';
            button.style.background = '#f44336';
            
            // Show error notification
            if (typeof Swal !== 'undefined') {
                Swal.fire({
                    icon: 'error',
                    title: 'Failed to Send',
                    text: 'Could not send pickup notification. Please try again.',
                    timer: 3000,
                    showConfirmButton: false
                });
            } else {
                alert('Failed to send notification. Please try again.');
            }
            
            // Re-enable button after 3 seconds
            setTimeout(() => {
                button.disabled = false;
                button.innerHTML = '<i class="fas fa-envelope"></i> Notify Pickup';
                button.style.background = '#4CAF50';
            }, 3000);
        });
    };
    
    // Apply release styles on page load
    applyReleaseStyles();
    updateReleaseBanner();
    updateSpecificRequestBanner();
    
    // Update every 60 seconds
    setInterval(() => {
        applyReleaseStyles();
        updateReleaseBanner();
        updateSpecificRequestBanner();
    }, 60000);

    const legendItems = document.querySelectorAll('.legend-item');
    const segments = {
        rejected: document.getElementById('rejectedSegment'),
        pending: document.getElementById('pendingSegment'),
        approved: document.getElementById('approvedSegment'),
        completed: document.getElementById('completedSegment')
    };
    const totalRequests = {
        rejected: {{ $analytics['rejected'] ?? 0 }},
        pending: {{ $analytics['pending'] ?? 0 }},
        approved: {{ $analytics['approved'] ?? 0 }},
        completed: {{ $analytics['completed'] ?? 0 }},
        all: {{ $total }}
    };
    const centerLabel = document.getElementById('centerLabel');
    const totalRequestsLabel = document.getElementById('totalRequests');
    let activeStatus = null;

    legendItems.forEach(item => {
        item.addEventListener('click', function() {
            const status = item.getAttribute('data-status');
            activeStatus = status;
            // Hide all segments except selected, fill donut with selected color and keep it until reset
            Object.keys(segments).forEach(key => {
                if (key === status) {
                    segments[key].style.display = '';
                    segments[key].style.transition = 'stroke 0.6s, opacity 0.6s';
                    segments[key].style.stroke = getStatusColor(key);
                    segments[key].setAttribute('stroke-dasharray', '502.4 0'); // fill whole donut
                    segments[key].style.opacity = '1';
                } else {
                    segments[key].style.display = 'none';
                }
            });
            // Update center label and number
            totalRequestsLabel.textContent = totalRequests[status];
            centerLabel.textContent = item.querySelector('.legend-label').textContent + ' Requests';
            // Highlight active legend
            legendItems.forEach(li => li.style.background = '#f8f9fa');
            item.style.background = '#e0e0e0';
        });
    });

    function getStatusColor(status) {
        switch(status) {
            case 'rejected': return '#F44336';
            case 'pending': return '#FFC107';
            case 'approved': return '#2196F3';
            case 'completed': return '#4CAF50';
            default: return '#f0f0f0';
        }
    }

    // Reset on double click anywhere in chart
    document.querySelector('.unified-progress-svg').addEventListener('dblclick', function() {
        Object.keys(segments).forEach(key => {
            segments[key].style.display = '';
            segments[key].style.transition = 'stroke 0.6s, opacity 0.6s';
            segments[key].style.stroke = getStatusColor(key);
            // Restore original dasharray
            switch(key) {
                case 'rejected': segments[key].setAttribute('stroke-dasharray', '{{ $rejectedDash }} {{ $circumference - $rejectedDash }}'); break;
                case 'pending': segments[key].setAttribute('stroke-dasharray', '{{ $pendingDash }} {{ $circumference - $pendingDash }}'); break;
                case 'approved': segments[key].setAttribute('stroke-dasharray', '{{ $approvedDash }} {{ $circumference - $approvedDash }}'); break;
                case 'completed': segments[key].setAttribute('stroke-dasharray', '{{ $completedDash }} {{ $circumference - $completedDash }}'); break;
            }
            segments[key].style.opacity = '1';
        });
        totalRequestsLabel.textContent = totalRequests['all'];
        centerLabel.textContent = 'Total Requests';
        legendItems.forEach(li => li.style.background = '#f8f9fa');
        activeStatus = null;
    });
});
</script>
                    </div>
                    </div>
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
                            <th>Document Request ID</th>
                            <th>Student Name</th>
                            <th>Course</th>
                            <th>Status</th>
                            <th>Release Status</th>
                            <th>Requested Documents</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($dashboardRequests as $req)
                        @php
                            $createdAt = $req->created_at ?? ($req->request_date ? \Carbon\Carbon::parse($req->request_date) : null);
                            // Calculate expected release date (7 days from completion for completed requests)
                            $completedAt = $req->status == 'completed' && $createdAt ? $createdAt->copy()->addDays(7) : null;
                            $daysUntilRelease = $completedAt ? now()->diffInDays($completedAt, false) : null;
                            $isCompleted = $req->status == 'completed';
                        @endphp
                        <tr data-days-until-release="{{ $daysUntilRelease ?? 0 }}" data-status="{{ $req->status }}" data-completed-at="{{ $completedAt ? $completedAt->toDateString() : '' }}">
                            <td>{{ $req->id }}</td>
                            <td>{{ $req->first_name }} {{ $req->last_name }}</td>
                            <td>{{ $req->course }}</td>
                            <td>{{ ucfirst($req->status) }}</td>
                            <td>
                                @if($isCompleted)
                                    <span class="release-cell" data-days="{{ $daysUntilRelease }}">
                                        {{ abs($daysUntilRelease) }} {{ abs($daysUntilRelease) == 1 ? 'day' : 'days' }}
                                    </span>
                                @else
                                    <span style="color: #999;">N/A</span>
                                @endif
                            </td>
                            <td>
                                @foreach($req->requestedDocuments as $doc)
                                    {{ $doc->document_type }} ({{ $doc->quantity }})<br>
                                @endforeach
                            </td>
                            <td>
                                @if($req->status == 'pending_registrar_approval')
                                    <div class="action-btn-group">
                                        <button type="button" class="action-btn approve-btn verify-btn" 
                                            data-request-id="{{ $req->id }}" 
                                            data-student-id="{{ $req->student_id ?? '' }}" 
                                            data-student-name="{{ $req->first_name ?? '' }} {{ $req->last_name ?? '' }}"
                                            title="Verify">
                                            <i class="fas fa-user-check"></i> Verify
                                        </button>
                                        @if($req->payment_status == 'paid')
                                            <form action="{{ route('registrar.complete', $req->id) }}" method="POST" style="display:inline;">
                                                @csrf
                                                <button type="submit" class="action-btn reject-btn" title="Complete">
                                                    <i class="fas fa-check-double"></i> Complete
                                                </button>
                                            </form>
                                        @endif
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
                    <div class="search-bar" style="width: 300px; margin-right: auto; position: relative;">
                        <i class="fas fa-search search-icon"></i>
                        <input type="text" placeholder="Search documents..." id="docSearchInput" autocomplete="off">
                        <ul id="docSearchSuggestions" style="position:absolute;top:38px;left:0;width:100%;background:#fff;z-index:10;list-style:none;padding:0;margin:0;border-radius:0 0 8px 8px;box-shadow:0 4px 12px rgba(0,0,0,0.08);display:none;max-height:180px;overflow-y:auto;"></ul>
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
                        <select class="filter-select" id="releaseSortSelect">
                            <option value="">Sort by Date Requested</option>
                            <option value="urgent">Sort by Most Urgent Release</option>
                            <option value="latest">Sort by Latest Release</option>
                        </select>
                        <input type="date" class="filter-date" placeholder="Filter by date">
                    </div>
                </div>
                <table id="documentRequestsTable">
                    <thead>
                        <tr>
                            <th>Document Request ID</th>
                            <th>Student</th>
                            <th>Document Type(s)</th>
                            <th>Date Requested</th>
                            <th>Release Status</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($requests as $req)
                        @php
                            $reqCreatedAt = $req->created_at ?? ($req->request_date ? \Carbon\Carbon::parse($req->request_date) : null);
                            // Calculate expected release date (7 days from request date for completed requests)
                            $reqCompletedAt = $req->status == 'completed' && $reqCreatedAt ? $reqCreatedAt->copy()->addDays(7) : null;
                            $reqDaysUntilRelease = $reqCompletedAt ? now()->diffInDays($reqCompletedAt, false) : null;
                            $reqIsCompleted = $req->status == 'completed';
                        @endphp
                        <tr
                            data-status="{{ strtolower($req->status) }}"
                            data-documents="@foreach($req->requestedDocuments as $doc){{ strtolower($doc->document_type) }},@endforeach"
                            data-date="{{ $req->created_at ? $req->created_at->format('Y-m-d') : ($req->request_date ? \Carbon\Carbon::parse($req->request_date)->format('Y-m-d') : '') }}"
                            data-days-until-release="{{ $reqDaysUntilRelease ?? 0 }}"
                        >
                            <td>{{ $req->id }}</td>
                            <td>{{ $req->first_name }} {{ $req->last_name }}</td>
                            <td>
                                @foreach($req->requestedDocuments as $doc)
                                    {{ $doc->document_type }} ({{ $doc->quantity }})<br>
                                @endforeach
                            </td>
                            <td>{{ $req->created_at ? $req->created_at->format('m/d/Y') : ($req->request_date ? \Carbon\Carbon::parse($req->request_date)->format('m/d/Y') : '') }}</td>
                            <td>
                                @if($reqIsCompleted)
                                    <span class="release-cell" data-days="{{ $reqDaysUntilRelease }}">
                                        {{ abs($reqDaysUntilRelease) }} {{ abs($reqDaysUntilRelease) == 1 ? 'day' : 'days' }}
                                    </span>
                                @else
                                    <span style="color: #999;">N/A</span>
                                @endif
                            </td>
                            <td><span class="status-badge status-{{ strtolower($req->status) }}">{{ ucfirst($req->status) }}</span></td>
                            <td>
                                @if($req->status == 'pending_registrar_approval')
                                    <div style="display: flex; gap: 8px; align-items: center;">
                                        <button type="button" class="action-btn approve-btn verify-btn" 
                                            data-request-id="{{ $req->id }}" 
                                            data-student-id="{{ $req->student_id ?? '' }}" 
                                            data-student-name="{{ $req->first_name ?? '' }} {{ $req->last_name ?? '' }}"
                                            title="Verify">
                                            <i class="fas fa-user-check"></i> Verify
                                        </button>
                                        @if($req->payment_status == 'paid')
                                            <form action="{{ route('registrar.complete', $req->id) }}" method="POST" style="display:inline;">
                                                @csrf
                                                <button type="submit" class="action-btn reject-btn" title="Complete">
                                                    <i class="fas fa-check-double"></i> Complete
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                @elseif($req->status == 'completed')
                                    <div style="display: flex; gap: 8px; align-items: center;">
                                        <span style="color: #4CAF50; font-weight: 600;">✅ Completed</span>
                                        <button type="button" class="action-btn notify-btn" 
                                            data-request-id="{{ $req->id }}"
                                            data-email="{{ $req->email }}"
                                            data-first-name="{{ $req->first_name }}"
                                            data-last-name="{{ $req->last_name }}"
                                            data-days-until-release="{{ $reqDaysUntilRelease }}"
                                            onclick="sendPickupNotification(this)"
                                            title="Notify requester that document is ready for pickup">
                                            <i class="fas fa-envelope"></i>
                                            Notify Pickup
                                        </button>
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
                <!-- Student Table and Filters (hidden by default) -->
<div id="studentTableSection" style="display: none;">
    <div class="student-actions" style="display:flex;align-items:center;gap:10px;justify-content:flex-end;margin-bottom:1.5rem;">
        <button class="action-btn primary" id="openAddStudentModalBtn" style="background:#8B0000;color:#fff;font-weight:600;letter-spacing:1px;">
            <i class="fas fa-user-plus"></i> Add Records
        </button>
        <button class="import-btn" id="openImportStudentModalBtn">
            <i class="fas fa-file-import"></i> Import Student Records
        </button>
        <button class="action-btn secondary" id="backToGridBtn" style="margin-left: 10px;">
            <i class="fas fa-arrow-left"></i> Back
        </button>
    </div>
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
                            ["name" => "BACHELOR OF SCIENCE IN PUBLIC ADMINISTRATION", "img" => "/images/BPA.jpg"],
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
                    <div style="display:flex;align-items:center;gap:1rem;margin-bottom:1rem;flex-wrap:wrap;">
                        <div style="display:flex;align-items:center;gap:0.5rem;">
                            <label for="schoolYearFilter" style="font-weight:600;color:#8B0000;">School Year:</label>
                            <select id="schoolYearFilter" name="schoolYearFilter" class="filter-select" style="min-width:140px; border:1.5px solid #8B0000; border-radius:8px; padding:6px 12px; font-size:1rem;">
                                <option value="all">All Years</option>
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
                        <div style="display:flex;align-items:center;gap:0.5rem;">
                            <label for="yearLevelFilter" style="font-weight:600;color:#8B0000;">Year Level:</label>
                            <select id="yearLevelFilter" name="yearLevelFilter" class="filter-select" style="min-width:140px; border:1.5px solid #8B0000; border-radius:8px; padding:6px 12px; font-size:1rem;">
                                <option value="all">All Levels</option>
                                <option value="1st Year">1st Year</option>
                                <option value="2nd Year">2nd Year</option>
                                <option value="3rd Year">3rd Year</option>
                                <option value="4th Year">4th Year</option>
                                <option value="Alumni">Alumni</option>
                            </select>
                        </div>
                        <div style="display:flex;gap:0.5rem;margin-left:auto;">
                            <button id="exportStudentRecordsBtn" style="background:#4CAF50;color:#fff;font-weight:600;padding:8px 18px;border:none;border-radius:8px;cursor:pointer;">
                                <i class="fas fa-file-export"></i> Export Records
                            </button>
                            <button id="printStudentRecordsBtn" style="background:#FFC107;color:#222;font-weight:600;padding:8px 18px;border:none;border-radius:8px;cursor:pointer;">
                                <i class="fas fa-print"></i> Print Records
                            </button>
                            <button id="backSyncStudentRecordsBtn" style="background:#8B0000;color:#fff;font-weight:600;padding:8px 18px;border:none;border-radius:8px;cursor:pointer;">
                                <i class="fas fa-sync-alt"></i> Back/Sync
                            </button>
                        </div>
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
                    <!-- Pagination Controls -->
                    <div id="studentRecordsPagination" class="pagination" style="margin-top: 1rem;"></div>
                </div>
                <!-- Custom Context Menu for Department Logo -->
                <div id="logoContextMenu">
                    <button id="uploadLogoBtn">Upload Department Logo</button>
                    <button id="updateLogoBtn">Update Department Logo</button>
                </div>
                <!-- Hidden file input for logo upload -->
                <input type="file" id="logoFileInput" accept="image/*" style="display:none;">
<!-- Add Student/Alumni Modal -->
<div id="addStudentModal" class="import-modal" style="display:none;z-index:2001;">
    <div class="import-modal-content" style="border-top:6px solid #8B0000;">
        <div class="import-modal-header">
            <h3 class="import-modal-title" style="color:#8B0000;" id="modalTitle">Add Record</h3>
            <button class="import-modal-close" onclick="closeAddStudentModal()">&times;</button>
        </div>
        <!-- Record Type Toggle -->
        <div style="margin-bottom:1rem;display:flex;gap:1rem;align-items:center;justify-content:center;padding:0.75rem;background:#f5f5f5;border-radius:8px;">
            <label style="font-weight:600;color:#8B0000;">Record Type:</label>
            <label style="display:flex;align-items:center;gap:0.5rem;cursor:pointer;">
                <input type="radio" name="record_type" id="record_type_student" value="student" checked onchange="changeRecordType('student')">
                <span>Student</span>
            </label>
            <label style="display:flex;align-items:center;gap:0.5rem;cursor:pointer;">
                <input type="radio" name="record_type" id="record_type_alumni" value="alumni" onchange="changeRecordType('alumni')">
                <span>Alumni</span>
            </label>
        </div>
        <form method="POST" id="addRecordForm" action="{{ route('students.store') }}">
            @csrf
            <div style="margin-bottom:1rem;">
                <label for="student_id_modal" style="font-weight:600;color:#8B0000;">Student ID</label>
                <input type="text" name="student_id" id="student_id_modal" class="form-control" required style="border:1.5px solid #8B0000;border-radius:8px;">
            </div>
            <div class="row" style="margin-bottom:1rem;display:flex;flex-wrap:wrap;gap:1rem;">
                <div class="col" style="flex:1;min-width:180px;display:flex;flex-direction:column;gap:0.5rem;">
                    <label for="first_name_modal" style="font-weight:600;color:#8B0000;">First Name</label>
                    <input type="text" name="first_name" id="first_name_modal" class="form-control" required style="border:1.5px solid #8B0000;border-radius:8px;padding:0.75rem;font-size:1rem;">
                </div>
                <div class="col" style="flex:1;min-width:180px;display:flex;flex-direction:column;gap:0.5rem;">
                    <label for="middle_name_modal" style="font-weight:600;color:#8B0000;">Middle Name</label>
                    <input type="text" name="middle_name" id="middle_name_modal" class="form-control" style="border:1.5px solid #8B0000;border-radius:8px;padding:0.75rem;font-size:1rem;">
                </div>
                <div class="col" style="flex:1;min-width:180px;display:flex;flex-direction:column;gap:0.5rem;">
                    <label for="last_name_modal" style="font-weight:600;color:#8B0000;">Last Name</label>
                    <input type="text" name="last_name" id="last_name_modal" class="form-control" required style="border:1.5px solid #8B0000;border-radius:8px;padding:0.75rem;font-size:1rem;">
                </div>
            </div>
            <div style="margin-bottom:1rem;display:flex;flex-direction:column;gap:0.5rem;">
                <label for="program_modal" style="font-weight:600;color:#8B0000;">Program</label>
                <select name="program" id="program_modal" class="form-control" required style="border:1.5px solid #8B0000;border-radius:8px;padding:0.75rem;font-size:1rem;">
                    <option value="">Select Program</option>
                    <option value="BACHELOR OF SCIENCE IN INFORMATION TECHNOLOGY" {{ (isset($selectedDepartment) && $selectedDepartment == 'BACHELOR OF SCIENCE IN INFORMATION TECHNOLOGY') ? 'selected' : '' }}>BACHELOR OF SCIENCE IN INFORMATION TECHNOLOGY</option>
                    <option value="BACHELOR OF SCIENCE IN ENTREPRENEURSHIP" {{ (isset($selectedDepartment) && $selectedDepartment == 'BACHELOR OF SCIENCE IN ENTREPRENEURSHIP') ? 'selected' : '' }}>BACHELOR OF SCIENCE IN ENTREPRENEURSHIP</option>
                    <option value="BACHELOR OF SCIENCE IN CRIMINOLOGY" {{ (isset($selectedDepartment) && $selectedDepartment == 'BACHELOR OF SCIENCE IN CRIMINOLOGY') ? 'selected' : '' }}>BACHELOR OF SCIENCE IN CRIMINOLOGY</option>
                    <option value="BACHELOR OF ELEMENTARY EDUCATION" {{ (isset($selectedDepartment) && $selectedDepartment == 'BACHELOR OF ELEMENTARY EDUCATION') ? 'selected' : '' }}>BACHELOR OF ELEMENTARY EDUCATION</option>
                    <option value="BACHELOR OF EARLY CHILDHOOD EDUCATION" {{ (isset($selectedDepartment) && $selectedDepartment == 'BACHELOR OF EARLY CHILDHOOD EDUCATION') ? 'selected' : '' }}>BACHELOR OF EARLY CHILDHOOD EDUCATION</option>
                    <option value="BACHELOR OF SCIENCE IN HOSPITALITY MANAGEMENT" {{ (isset($selectedDepartment) && $selectedDepartment == 'BACHELOR OF SCIENCE IN HOSPITALITY MANAGEMENT') ? 'selected' : '' }}>BACHELOR OF SCIENCE IN HOSPITALITY MANAGEMENT</option>
                    <option value="BACHELOR OF SCIENCE IN PUBLIC ADMINISTRATION" {{ (isset($selectedDepartment) && $selectedDepartment == 'BACHELOR OF SCIENCE IN PUBLIC ADMINISTRATION') ? 'selected' : '' }}>BACHELOR OF SCIENCE IN PUBLIC ADMINISTRATION</option>
                </select>
            </div>
            <div style="margin-bottom:1rem;">
                <label for="year_level_modal" style="font-weight:600;color:#8B0000;">Year Level</label>
                <select name="year_level" id="year_level_modal" class="form-control" required style="border:1.5px solid #8B0000;border-radius:8px;">
                    <option value="">Select Year Level</option>
                    <option value="1st Year">1st Year</option>
                    <option value="2nd Year">2nd Year</option>
                    <option value="3rd Year">3rd Year</option>
                    <option value="4th Year">4th Year</option>
                    <option value="Alumni">Alumni</option>
                </select>
            </div>
            <div style="margin-bottom:1rem;">
                <label for="school_year_modal" style="font-weight:600;color:#8B0000;">School Year</label>
                <select name="school_year" id="school_year_modal" class="form-control" required style="border:1.5px solid #8B0000;border-radius:8px;padding:0.75rem;font-size:1rem;">
                    <option value="">Select School Year</option>
                    @php
                        $startYear = 2020;
                        $currentYear = date('Y');
                        for ($y = $currentYear; $y >= $startYear; $y--) {
                            $schoolYear = $y . '-' . ($y+1);
                            echo '<option value="' . $schoolYear . '">' . $schoolYear . '</option>';
                        }
                    @endphp
                </select>
            </div>
            <div style="margin-bottom:1.5rem;">
                <label for="status_modal" style="font-weight:600;color:#8B0000;">Status</label>
                <select name="status" id="status_modal" class="form-control" required style="border:1.5px solid #8B0000;border-radius:8px;">
                    <option value="active">Active</option>
                    <option value="on leave">On Leave</option>
                    <option value="graduated">Graduated</option>
                    <option value="dropped">Dropped</option>
                </select>
            </div>
            <!-- Alumni-specific fields (initially hidden) -->
            <div id="alumniFields" style="display:none;">
                <div style="margin-bottom:1.5rem;">
                    <label for="year_graduated_modal" style="font-weight:600;color:#8B0000;">Year Graduated</label>
                    <select name="year_graduated" id="year_graduated_modal" class="form-control" required style="border:1.5px solid #8B0000;border-radius:8px;padding:0.75rem;font-size:1rem;">
                        <option value="">Select Year Graduated</option>
                        @php
                            $startYear = 2020;
                            $currentYear = date('Y');
                        @endphp
                        @for ($y = $currentYear; $y >= $startYear; $y--)
                            <option value="{{ $y }}-{{ $y+1 }}">{{ $y }}-{{ $y+1 }}</option>
                        @endfor
                    </select>
                </div>
            </div>
            <button type="submit" class="btn btn-primary" id="submitButton" style="width:100%;padding:0.75rem 0;font-size:1rem;background:#8B0000;color:#fff;border:none;border-radius:8px;font-weight:600;letter-spacing:1px;box-shadow:0 2px 8px rgba(139,0,0,0.10);transition:background 0.2s;cursor:pointer;position:relative;z-index:10;">Add Record</button>
        </form>
    </div>
</div>


<!-- Add Alumni Modal -->
<div id="addAlumniModal" class="import-modal" style="display:none;z-index:2001;">
    <div class="import-modal-content" style="border-top:6px solid #8B0000;">
        <div class="import-modal-header">
            <h3 class="import-modal-title" style="color:#8B0000;">Add Alumni Record</h3>
            <button class="import-modal-close" onclick="closeAddAlumniModal()">&times;</button>
        </div>
        <form method="POST" action="{{ route('alumni.store') }}">
            @csrf
            <div style="margin-bottom:1rem;">
                <label for="alumni_student_id_modal" style="font-weight:600;color:#8B0000;">Student ID</label>
                <input type="text" name="student_id" id="alumni_student_id_modal" class="form-control" style="border:1.5px solid #8B0000;border-radius:8px;">
            </div>
            <div class="row" style="margin-bottom:1rem;display:flex;flex-wrap:wrap;gap:1rem;">
                <div class="col" style="flex:1;min-width:180px;display:flex;flex-direction:column;gap:0.5rem;">
                    <label for="alumni_first_name_modal" style="font-weight:600;color:#8B0000;">First Name</label>
                    <input type="text" name="first_name" id="alumni_first_name_modal" class="form-control" required style="border:1.5px solid #8B0000;border-radius:8px;padding:0.75rem;font-size:1rem;">
                </div>
                <div class="col" style="flex:1;min-width:180px;display:flex;flex-direction:column;gap:0.5rem;">
                    <label for="alumni_middle_name_modal" style="font-weight:600;color:#8B0000;">Middle Name</label>
                    <input type="text" name="middle_name" id="alumni_middle_name_modal" class="form-control" style="border:1.5px solid #8B0000;border-radius:8px;padding:0.75rem;font-size:1rem;">
                </div>
                <div class="col" style="flex:1;min-width:180px;display:flex;flex-direction:column;gap:0.5rem;">
                    <label for="alumni_last_name_modal" style="font-weight:600;color:#8B0000;">Last Name</label>
                    <input type="text" name="last_name" id="alumni_last_name_modal" class="form-control" required style="border:1.5px solid #8B0000;border-radius:8px;padding:0.75rem;font-size:1rem;">
                </div>
            </div>
            <div style="margin-bottom:1rem;display:flex;flex-direction:column;gap:0.5rem;">
                <label for="alumni_program_modal" style="font-weight:600;color:#8B0000;">Program</label>
                <select name="program" id="alumni_program_modal" class="form-control" required style="border:1.5px solid #8B0000;border-radius:8px;padding:0.75rem;font-size:1rem;">
                    <option value="">Select Program</option>
                    <option value="BACHELOR OF SCIENCE IN INFORMATION TECHNOLOGY">BACHELOR OF SCIENCE IN INFORMATION TECHNOLOGY</option>
                    <option value="BACHELOR OF SCIENCE IN ENTREPRENEURSHIP">BACHELOR OF SCIENCE IN ENTREPRENEURSHIP</option>
                    <option value="BACHELOR OF SCIENCE IN CRIMINOLOGY">BACHELOR OF SCIENCE IN CRIMINOLOGY</option>
                    <option value="BACHELOR OF ELEMENTARY EDUCATION">BACHELOR OF ELEMENTARY EDUCATION</option>
                    <option value="BACHELOR OF EARLY CHILDHOOD EDUCATION">BACHELOR OF EARLY CHILDHOOD EDUCATION</option>
                    <option value="BACHELOR OF SCIENCE IN HOSPITALITY MANAGEMENT">BACHELOR OF SCIENCE IN HOSPITALITY MANAGEMENT</option>
                    <option value="BACHELOR OF SCIENCE IN PUBLIC ADMINISTRATION">BACHELOR OF SCIENCE IN PUBLIC ADMINISTRATION</option>
                </select>
            </div>
            <div style="margin-bottom:1.5rem;">
                <label for="alumni_year_graduated_modal" style="font-weight:600;color:#8B0000;">Year Graduated</label>
                <select name="year_graduated" id="alumni_year_graduated_modal" class="form-control" required style="border:1.5px solid #8B0000;border-radius:8px;padding:0.75rem;font-size:1rem;">
                    <option value="">Select Year Graduated</option>
                    @php
                        $startYear = 2020;
                        $currentYear = date('Y');
                    @endphp
                    @for ($y = $currentYear; $y >= $startYear; $y--)
                        <option value="{{ $y }}-{{ $y+1 }}">{{ $y }}-{{ $y+1 }}</option>
                    @endfor
                </select>
            </div>
            <button type="submit" class="btn btn-primary" style="width:100%;padding:0.75rem 0;font-size:1rem;background:#8B0000;color:#fff;border:none;border-radius:8px;font-weight:600;letter-spacing:1px;box-shadow:0 2px 8px rgba(139,0,0,0.10);transition:background 0.2s;">Add Alumni</button>
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

@if(session('alumni_added'))
    <script>
        Swal.fire({
            icon: 'success',
            title: 'Alumni Added!',
            text: "{{ session('alumni_added') }}",
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
    // Debug: Uncomment below to see student data structure
    // console.log('=== STUDENT DATA LOADED ===');
    // console.log('All programs in database:', Object.keys(students));
    // console.log('Full student data structure:', students);
    // console.log('===========================');
    let selectedDepartment = null;
    let selectedSchoolYear = 'all';
    let selectedYearLevel = 'all';
    const tableSection = document.getElementById('studentRecordsTableSection');
    const tableBody = document.getElementById('studentRecordsTableBody');
    const departmentGrid = document.getElementById('departmentGrid');
    const schoolYearFilter = document.getElementById('schoolYearFilter');
    const yearLevelFilter = document.getElementById('yearLevelFilter');
    const backBtn = document.getElementById('backToGridBtn');
    const paginationContainer = document.getElementById('studentRecordsPagination');

    // Pagination state
    let studentCurrentPage = 1;
    const studentPageSize = 10; // items per page

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

    // Render table for selected department, filtered by school year and year level
    function renderTable() {
        if (!selectedDepartment) {
            tableSection.style.display = 'none';
            return;
        }
        tableSection.style.display = 'block';
        tableBody.innerHTML = '';
        let records = [];
        
        // Get all students for the selected department
        if (selectedSchoolYear !== 'all' && students[selectedDepartment] && students[selectedDepartment][selectedSchoolYear]) {
            records = students[selectedDepartment][selectedSchoolYear];
        } else {
            records = getAllStudentsForDepartment(selectedDepartment);
        }
        
        // Filter by year level if not 'all'
        if (selectedYearLevel !== 'all') {
            records = records.filter(function(student) {
                return student.year_level === selectedYearLevel;
            });
        }
        
        if (!records || records.length === 0) {
            tableBody.innerHTML = '<tr><td colspan="5" style="text-align:center;color:#888;">No records found.</td></tr>';
            if (paginationContainer) paginationContainer.innerHTML = '';
            return;
        }
        
        // Ensure current page within bounds when filters change
        const totalPages = Math.max(1, Math.ceil(records.length / studentPageSize));
        if (studentCurrentPage > totalPages) studentCurrentPage = totalPages;
        if (studentCurrentPage < 1) studentCurrentPage = 1;

        const startIdx = (studentCurrentPage - 1) * studentPageSize;
        const endIdx = Math.min(startIdx + studentPageSize, records.length);
        const pageRecords = records.slice(startIdx, endIdx);

        pageRecords.forEach(function(student) {
            let schoolYearDisplay = student.school_year || (student.school_years ? student.school_years.join(', ') : '');
            let dbId = student.id; // Use the database id for updates
            let studentIdValue = student.student_id || '';
            let row = document.createElement('tr');
            row.setAttribute('data-student-id', dbId);
            row.innerHTML = `
                <td class="editable-cell" data-field="student_id" data-student-id="${dbId}">
                    ${studentIdValue}
                    <span class="edit-icon"><i class="fas fa-edit"></i></span>
                </td>
                <td>
                    <div class="editable-cell" data-field="first_name" data-student-id="${dbId}">
                        ${student.first_name || ''} 
                        <span class="edit-icon"><i class="fas fa-edit"></i></span>
                    </div>
                    <div class="editable-cell" data-field="last_name" data-student-id="${dbId}">
                        ${student.last_name || ''}
                        <span class="edit-icon"><i class="fas fa-edit"></i></span>
                    </div>
                </td>
                <td class="editable-cell" data-field="program" data-student-id="${dbId}">
                    ${student.program || ''}
                    <span class="edit-icon"><i class="fas fa-edit"></i></span>
                </td>
                <td class="editable-cell" data-field="year_level" data-student-id="${dbId}">
                    ${student.year_level || ''}
                    <span class="edit-icon"><i class="fas fa-edit"></i></span>
                </td>
                <td class="editable-cell" data-field="school_year" data-student-id="${dbId}">
                    ${schoolYearDisplay}
                    <span class="edit-icon"><i class="fas fa-edit"></i></span>
                </td>
            `;
            tableBody.appendChild(row);
        });
        
        // Attach inline editing handlers after rendering
        attachInlineEditing();

        // Render pagination controls
        renderStudentPagination(totalPages);
    }

    function renderStudentPagination(totalPages) {
        if (!paginationContainer) return;
        paginationContainer.innerHTML = '';

        // Helper to create a button
        function createBtn(label, page, disabled = false, active = false) {
            const btn = document.createElement('button');
            btn.className = 'page-link' + (active ? ' active' : '');
            btn.textContent = label;
            btn.style.cursor = disabled ? 'not-allowed' : 'pointer';
            btn.disabled = disabled;
            if (!disabled && !active) {
                btn.addEventListener('click', function() {
                    studentCurrentPage = page;
                    renderTable();
                });
            }
            const wrapper = document.createElement('span');
            wrapper.className = 'page-item' + (active ? ' active' : '') + (disabled ? ' disabled' : '');
            wrapper.appendChild(btn);
            return wrapper;
        }

        // Prev
        paginationContainer.appendChild(createBtn('Prev', Math.max(1, studentCurrentPage - 1), studentCurrentPage === 1));

        // Page numbers (simple full list; adjust if too many)
        for (let p = 1; p <= totalPages; p++) {
            paginationContainer.appendChild(createBtn(String(p), p, false, p === studentCurrentPage));
        }

        // Next
        paginationContainer.appendChild(createBtn('Next', Math.min(totalPages, studentCurrentPage + 1), studentCurrentPage === totalPages));
    }

    // Inline editing functionality
    function attachInlineEditing() {
        const editableCells = document.querySelectorAll('#studentRecordsTableBody .editable-cell');
        
        editableCells.forEach(function(cell) {
            cell.addEventListener('click', function() {
                if (this.classList.contains('editing')) return;
                
                const field = this.getAttribute('data-field');
                const studentId = this.getAttribute('data-student-id');
                const currentValue = this.textContent.trim();
                
                // Add editing class
                this.classList.add('editing');
                
                // Determine input type based on field
                let inputElement;
                if (field === 'program') {
                    inputElement = createProgramSelect(currentValue);
                } else if (field === 'year_level') {
                    inputElement = createYearLevelSelect(currentValue);
                } else if (field === 'school_year') {
                    inputElement = createSchoolYearSelect(currentValue);
                } else {
                    inputElement = document.createElement('input');
                    inputElement.type = 'text';
                    inputElement.className = 'editable-input';
                    inputElement.value = currentValue;
                }
                
                // Save original value for comparison
                const originalValue = currentValue;
                const cellElement = this;
                
                // Replace cell content
                this.innerHTML = '';
                this.appendChild(inputElement);
                
                // Focus input
                inputElement.focus();
                
                // Save on blur or Enter
                function saveValue() {
                    const newValue = inputElement.value || inputElement.options[inputElement.selectedIndex].text;
                    
                    if (newValue !== originalValue) {
                        updateStudentRecord(studentId, field, newValue, cellElement);
                    } else {
                        // Cancel editing
                        cellElement.classList.remove('editing');
                        cellElement.textContent = originalValue;
                    }
                }
                
                inputElement.addEventListener('blur', saveValue);
                inputElement.addEventListener('keydown', function(e) {
                    if (e.key === 'Enter') {
                        saveValue();
                    } else if (e.key === 'Escape') {
                        cellElement.classList.remove('editing');
                        cellElement.textContent = originalValue;
                    }
                });
            });
        });
    }
    
    // Create program select dropdown
    function createProgramSelect(currentValue) {
        const select = document.createElement('select');
        select.className = 'editable-select';
        
        const programs = [
            'BACHELOR OF SCIENCE IN INFORMATION TECHNOLOGY',
            'BACHELOR OF SCIENCE IN ENTREPRENEURSHIP',
            'BACHELOR OF SCIENCE IN CRIMINOLOGY',
            'BACHELOR OF ELEMENTARY EDUCATION',
            'BACHELOR OF EARLY CHILDHOOD EDUCATION',
            'BACHELOR OF SCIENCE IN HOSPITALITY MANAGEMENT',
            'BACHELOR OF SCIENCE IN PUBLIC ADMINISTRATION'
        ];
        
        programs.forEach(program => {
            const option = document.createElement('option');
            option.value = program;
            option.textContent = program;
            if (program === currentValue) {
                option.selected = true;
            }
            select.appendChild(option);
        });
        
        return select;
    }
    
    // Create year level select dropdown
    function createYearLevelSelect(currentValue) {
        const select = document.createElement('select');
        select.className = 'editable-select';
        
        const yearLevels = ['1st Year', '2nd Year', '3rd Year', '4th Year', 'Alumni'];
        
        yearLevels.forEach(year => {
            const option = document.createElement('option');
            option.value = year;
            option.textContent = year;
            if (year === currentValue) {
                option.selected = true;
            }
            select.appendChild(option);
        });
        
        return select;
    }
    
    // Create school year select dropdown
    function createSchoolYearSelect(currentValue) {
        const select = document.createElement('select');
        select.className = 'editable-select';
        
        // Generate school years from 2020 to current (in reverse order, most recent first)
        const startYear = 2020;
        const currentYear = new Date().getFullYear();
        
        for (let year = currentYear; year >= startYear; year--) {
            const option = document.createElement('option');
            option.value = year + '-' + (year + 1);
            option.textContent = year + '-' + (year + 1);
            if (option.value === currentValue) {
                option.selected = true;
            }
            select.appendChild(option);
        }
        
        return select;
    }
    
    // Update student record via AJAX
    function updateStudentRecord(studentId, field, value, cellElement) {
        console.log('Updating student:', { studentId, field, value });
        
        // Show saving indicator
        cellElement.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Saving...';
        
        // Prepare data
        const data = {};
        data[field] = value;
        
        console.log('Sending data:', data);
        
        // Send AJAX request
        fetch('/students/' + studentId + '/update', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Accept': 'application/json'
            },
            body: JSON.stringify(data)
        })
        .then(response => {
            console.log('Response status:', response.status);
            console.log('Response headers:', response.headers);
            if (!response.ok) {
                return response.json().then(err => {
                    console.error('Error response:', err);
                    return Promise.reject(err);
                });
            }
            return response.json();
        })
        .then(data => {
            console.log('Success response:', data);
            if (data.success) {
                // Show success
                cellElement.classList.remove('editing');
                cellElement.innerHTML = value + '<span class="edit-icon"><i class="fas fa-edit"></i></span>';
                
                // Show success message temporarily
                showSaveIndicator(cellElement, 'saved');
                
                // Reattach event listener
                attachInlineEditing();
                
                // Show success notification
                Swal.fire({
                    icon: 'success',
                    title: 'Updated!',
                    text: 'Student record updated successfully',
                    timer: 2000,
                    showConfirmButton: false,
                    toast: true,
                    position: 'top-end'
                });
            } else {
                throw new Error('Update failed');
            }
        })
        .catch(error => {
            console.error('Error updating student record:', error);
            
            // Extract error message
            let errorMessage = 'Failed to update student record';
            if (error.message) {
                errorMessage = error.message;
            } else if (error.errors && Object.keys(error.errors).length > 0) {
                errorMessage = Object.values(error.errors)[0][0];
            }
            
            // Show error
            cellElement.classList.remove('editing');
            cellElement.innerHTML = value + '<span class="edit-icon"><i class="fas fa-edit"></i></span>';
            
            showSaveIndicator(cellElement, 'error');
            
            // Reattach event listener
            attachInlineEditing();
            
            // Show error notification
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: errorMessage,
                timer: 3000,
                showConfirmButton: false,
                toast: true,
                position: 'top-end'
            });
        });
    }
    
    // Show save indicator
    function showSaveIndicator(cellElement, status) {
        const indicator = document.createElement('span');
        indicator.className = 'save-indicator ' + status;
        
        if (status === 'saving') {
            indicator.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Saving...';
        } else if (status === 'saved') {
            indicator.innerHTML = '<i class="fas fa-check"></i> Saved';
        } else if (status === 'error') {
            indicator.innerHTML = '<i class="fas fa-times"></i> Error';
        }
        
        cellElement.appendChild(indicator);
        
        setTimeout(() => {
            indicator.remove();
        }, 2000);
    }

    // Department selection
    if (departmentGrid) {
        departmentGrid.querySelectorAll('.department-logo-card').forEach(function(card) {
            card.addEventListener('click', function() {
                selectedDepartment = card.getAttribute('data-department');
                studentCurrentPage = 1;
                renderTable(); // Show all students for department
            });
        });
    }

    // School year filter
    if (schoolYearFilter) {
        schoolYearFilter.addEventListener('change', function() {
            selectedSchoolYear = this.value;
            studentCurrentPage = 1;
            renderTable();
        });
    }

    // Year level filter
    if (yearLevelFilter) {
        yearLevelFilter.addEventListener('change', function() {
            selectedYearLevel = this.value;
            studentCurrentPage = 1;
            renderTable();
        });
    }

    // Back button logic
    if (backBtn) {
        backBtn.addEventListener('click', function() {
            selectedDepartment = null;
            selectedSchoolYear = 'all';
            selectedYearLevel = 'all';
            studentCurrentPage = 1;
            if (schoolYearFilter) schoolYearFilter.value = 'all';
            if (yearLevelFilter) yearLevelFilter.value = 'all';
            tableSection.style.display = 'none';
            if (paginationContainer) paginationContainer.innerHTML = '';
        });
    }

    // Initial state: table hidden
    tableSection.style.display = 'none';
});

// Modal logic for Add Student/Alumni
function openAddStudentModal() {
    console.log('openAddStudentModal called');
    var modal = document.getElementById('addStudentModal');
    if (modal) {
        console.log('Modal found, showing it');
        modal.style.display = 'flex';
    } else {
        console.error('Modal not found!');
    }
}
function closeAddStudentModal() {
    var modal = document.getElementById('addStudentModal');
    if (modal) modal.style.display = 'none';
    // Reset form
    document.getElementById('addRecordForm').reset();
    document.getElementById('record_type_student').checked = true;
    changeRecordType('student');
}
// Move event listener inside DOMContentLoaded to ensure element exists
document.addEventListener('DOMContentLoaded', function() {
    var addBtn = document.getElementById('openAddStudentModalBtn');
    if (addBtn) {
        console.log('Add Records button found, adding event listener');
        addBtn.addEventListener('click', function() {
            console.log('Add Records button clicked');
            openAddStudentModal();
        });
    } else {
        console.error('Add Records button not found!');
    }
    
    // Add direct click handler to submit button
    var submitButton = document.getElementById('submitButton');
    if (submitButton) {
        console.log('Submit button found, adding direct click handler');
        submitButton.addEventListener('click', function(e) {
            console.log('Submit button clicked directly');
            alert('Button clicked! Processing...');
            e.preventDefault();
            
            // Get the form
            var form = document.getElementById('addRecordForm');
            if (form) {
                console.log('Form found, processing submission');
                
                // Get form data
                var formData = new FormData(form);
                var originalText = submitButton.textContent;
                
                console.log('Form data:', Object.fromEntries(formData));
                
                // Check if all required fields have values based on record type
                const recordType = document.querySelector('input[name="record_type"]:checked').value;
                let requiredFields = [];
                
                if (recordType === 'alumni') {
                    // Alumni required fields
                    requiredFields = ['first_name', 'last_name', 'program', 'year_graduated'];
                } else {
                    // Student required fields
                    requiredFields = ['student_id', 'first_name', 'last_name', 'program', 'year_level', 'school_year', 'status'];
                }
                
                const missingFields = [];
                requiredFields.forEach(field => {
                    if (!formData.get(field) || formData.get(field).trim() === '') {
                        missingFields.push(field);
                    }
                });
                if (missingFields.length > 0) {
                    alert('Please fill in all required fields: ' + missingFields.join(', '));
                    return;
                }
                
                // Show loading state
                submitButton.disabled = true;
                submitButton.textContent = 'Adding...';
                
                console.log('Submitting form to:', form.action);
                
                // Submit form via AJAX
                fetch(form.action, {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'Accept': 'application/json',
                    }
                })
                .then(response => {
                    console.log('Response received:', response.status);
                    if (response.ok) {
                        return response.text();
                    }
                    // Try to get validation errors for 422
                    if (response.status === 422) {
                        return response.json().then(errors => {
                            throw new Error('Validation failed: ' + JSON.stringify(errors));
                        });
                    }
                    throw new Error('Network response was not ok: ' + response.status);
                })
                .then(data => {
                    console.log('Success response:', data);
                    // Show success message
                    alert('Record added successfully!');
                    closeAddStudentModal();
                    // Refresh the page to show the new record
                    location.reload();
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Error adding record: ' + error.message);
                    // Reset button state
                    submitButton.disabled = false;
                    submitButton.textContent = originalText;
                });
            } else {
                console.error('Form not found!');
            }
        });
    } else {
        console.error('Submit button not found!');
    }
    
    // Add alumni form submission handler
    var addAlumniForm = document.querySelector('#addAlumniModal form');
    if (addAlumniForm) {
        addAlumniForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            // Get form data
            var formData = new FormData(this);
            var submitButton = this.querySelector('button[type="submit"]');
            var originalText = submitButton.textContent;
            
            console.log('Alumni form data:', Object.fromEntries(formData));
            
            // Check if all required fields for alumni have values (student_id is optional)
            const alumniRequiredFields = ['first_name', 'last_name', 'program', 'year_graduated'];
            const missingFields = [];
            alumniRequiredFields.forEach(field => {
                if (!formData.get(field) || formData.get(field).trim() === '') {
                    missingFields.push(field);
                }
            });
            if (missingFields.length > 0) {
                alert('Please fill in all required fields: ' + missingFields.join(', '));
                return;
            }
            
            // Show loading state
            submitButton.disabled = true;
            submitButton.textContent = 'Adding...';
            
            console.log('Submitting alumni form to:', this.action);
            
            // Submit form via AJAX
            fetch(this.action, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Accept': 'application/json',
                }
            })
            .then(response => {
                console.log('Alumni response received:', response.status);
                if (response.ok) {
                    return response.text();
                }
                // Try to get validation errors for 422
                if (response.status === 422) {
                    return response.json().then(errors => {
                        throw new Error('Validation failed: ' + JSON.stringify(errors));
                    });
                }
                throw new Error('Network response was not ok: ' + response.status);
            })
            .then(data => {
                console.log('Alumni success response:', data);
                // Show success message
                alert('Alumni record added successfully!');
                closeAddAlumniModal();
                // Refresh the page to show the new record
                location.reload();
            })
            .catch(error => {
                console.error('Alumni error:', error);
                alert('Error adding alumni record: ' + error.message);
                // Reset button state
                submitButton.disabled = false;
                submitButton.textContent = originalText;
            });
        });
    } else {
        console.error('Alumni form not found!');
    }
});

// Change record type function
function changeRecordType(type) {
    const alumniFields = document.getElementById('alumniFields');
    const yearLevel = document.getElementById('year_level_modal');
    const schoolYear = document.getElementById('school_year_modal');
    const studentId = document.getElementById('student_id_modal');
    const modalTitle = document.getElementById('modalTitle');
    const form = document.getElementById('addRecordForm');
    const submitButton = document.getElementById('submitButton');
    
    if (type === 'alumni') {
        // Show alumni fields
        alumniFields.style.display = 'block';
        
        // Change form action to alumni
        form.action = '{{ route("alumni.store") }}';
        
        // Update title
        modalTitle.textContent = 'Add Alumni Record';
        submitButton.textContent = 'Add Alumni';
        
        // Make year level and school year inactive for alumni
        yearLevel.required = false;
        yearLevel.disabled = true;
        yearLevel.style.opacity = '0.5';
        
        schoolYear.required = false;
        schoolYear.disabled = true;
        schoolYear.style.opacity = '0.5';
        
        // Make student ID optional for alumni
        studentId.required = false;
    } else {
        // Hide alumni fields
        alumniFields.style.display = 'none';
        
        // Change form action to student
        form.action = '{{ route("students.store") }}';
        
        // Update title
        modalTitle.textContent = 'Add Student Record';
        submitButton.textContent = 'Add Student';
        
        // Make year level and school year required for students
        yearLevel.required = true;
        yearLevel.disabled = false;
        yearLevel.style.opacity = '1';
        
        schoolYear.required = true;
        schoolYear.disabled = false;
        schoolYear.style.opacity = '1';
        
        // Make student ID required for students
        studentId.required = true;
    }
}
// Modal logic for Import Student
var importBtn = document.getElementById('openImportStudentModalBtn');
if (importBtn) importBtn.addEventListener('click', function() {
    var importModal = document.getElementById('importModal');
    if (importModal) importModal.style.display = 'flex';
});
</script>

<script>
// Alumni Records Table Logic (similar to student records)
document.addEventListener('DOMContentLoaded', function() {
    let alumni = @json($alumni);
    let selectedAlumniDepartment = null;
    let selectedAlumniSchoolYear = 'all';
    let selectedAlumniYearGraduated = 'all';
    const alumniTableSection = document.getElementById('alumniRecordsTableSection');
    const alumniTableBody = document.getElementById('alumniRecordsTableBody');
    const alumniDepartmentGrid = document.getElementById('alumniDepartmentGrid');
    const alumniSchoolYearFilter = document.getElementById('alumniSchoolYearFilter');
    const alumniYearGraduatedFilter = document.getElementById('alumniYearGraduatedFilter');
    const alumniBackBtn = document.getElementById('backToAlumniGridBtn');

    // Helper: flatten all alumni for a department
    function getAllAlumniForDepartment(dept) {
        let all = [];
        if (alumni[dept]) {
            Object.values(alumni[dept]).forEach(function(records) {
                if (Array.isArray(records)) {
                    all = all.concat(records);
                }
            });
        }
        return all;
    }

    // Render alumni table for selected department, filtered by school year and year graduated
    function renderAlumniTable() {
        if (!selectedAlumniDepartment) {
            alumniTableSection.style.display = 'none';
            return;
        }
        alumniTableSection.style.display = 'block';
        alumniTableBody.innerHTML = '';
        let records = [];
        
        // Get all alumni for the selected department
        if (selectedAlumniSchoolYear !== 'all' && alumni[selectedAlumniDepartment] && alumni[selectedAlumniDepartment][selectedAlumniSchoolYear]) {
            records = alumni[selectedAlumniDepartment][selectedAlumniSchoolYear];
        } else {
            records = getAllAlumniForDepartment(selectedAlumniDepartment);
        }
        
        // Filter by year graduated if not 'all'
        if (selectedAlumniYearGraduated !== 'all') {
            records = records.filter(function(alumni) {
                return alumni.year_graduated == selectedAlumniYearGraduated;
            });
        }
        
        if (!records || records.length === 0) {
            alumniTableBody.innerHTML = '<tr><td colspan="4" style="text-align:center;color:#888;">No records found.</td></tr>';
            return;
        }
        
        records.forEach(function(alumni) {
            let dbId = alumni.id; // Use the database id for updates
            let studentIdValue = alumni.student_id || '';
            let row = document.createElement('tr');
            row.setAttribute('data-alumni-id', dbId);
            row.innerHTML = `
                <td class="editable-cell" data-field="student_id" data-alumni-id="${dbId}">
                    ${studentIdValue}
                    <span class="edit-icon"><i class="fas fa-edit"></i></span>
                </td>
                <td>
                    <div class="editable-cell" data-field="first_name" data-alumni-id="${dbId}">
                        ${alumni.first_name || ''} 
                        <span class="edit-icon"><i class="fas fa-edit"></i></span>
            </div>
                    <div class="editable-cell" data-field="last_name" data-alumni-id="${dbId}">
                        ${alumni.last_name || ''}
                        <span class="edit-icon"><i class="fas fa-edit"></i></span>
                    </div>
                </td>
                <td class="editable-cell" data-field="program" data-alumni-id="${dbId}">
                    ${alumni.program || ''}
                    <span class="edit-icon"><i class="fas fa-edit"></i></span>
                </td>
                <td class="editable-cell" data-field="year_graduated" data-alumni-id="${dbId}">
                    ${alumni.year_graduated || ''}
                    <span class="edit-icon"><i class="fas fa-edit"></i></span>
                </td>
            `;
            alumniTableBody.appendChild(row);
        });
        
        // Attach inline editing handlers after rendering
        attachAlumniInlineEditing();
    }

    // Alumni inline editing functionality
    function attachAlumniInlineEditing() {
        const editableCells = document.querySelectorAll('#alumniRecordsTableBody .editable-cell');
        
        editableCells.forEach(function(cell) {
            cell.addEventListener('click', function() {
                if (this.classList.contains('editing')) return;
                
                const field = this.getAttribute('data-field');
                const alumniId = this.getAttribute('data-alumni-id');
                const currentValue = this.textContent.trim();
                
                // Add editing class
                this.classList.add('editing');
                
                // Determine input type based on field
                let inputElement;
                if (field === 'program') {
                    inputElement = createProgramSelect(currentValue);
                } else if (field === 'year_graduated') {
                    // Create year graduated dropdown
                    inputElement = document.createElement('select');
                    inputElement.className = 'editable-select';
                    
                    const startYear = 2020;
                    const currentYear = new Date().getFullYear();
                    
                    for (let year = currentYear; year >= startYear; year--) {
                        const option = document.createElement('option');
                        option.value = year + '-' + (year + 1);
                        option.textContent = year + '-' + (year + 1);
                        if (option.value === currentValue) {
                            option.selected = true;
                        }
                        inputElement.appendChild(option);
                    }
                } else {
                    inputElement = document.createElement('input');
                    inputElement.type = 'text';
                    inputElement.className = 'editable-input';
                    inputElement.value = currentValue;
                }
                
                // Save original value for comparison
                const originalValue = currentValue;
                const cellElement = this;
                
                // Replace cell content
                this.innerHTML = '';
                this.appendChild(inputElement);
                
                // Focus input
                inputElement.focus();
                
                // Save on blur or Enter
                function saveValue() {
                    const newValue = inputElement.value || inputElement.options[inputElement.selectedIndex].text;
                    
                    if (newValue !== originalValue) {
                        updateAlumniRecord(alumniId, field, newValue, cellElement);
                    } else {
                        // Cancel editing
                        cellElement.classList.remove('editing');
                        cellElement.textContent = originalValue;
                    }
                }
                
                inputElement.addEventListener('blur', saveValue);
                inputElement.addEventListener('keydown', function(e) {
                    if (e.key === 'Enter') {
                        saveValue();
                    } else if (e.key === 'Escape') {
                        cellElement.classList.remove('editing');
                        cellElement.textContent = originalValue;
                    }
                });
            });
        });
    }
    
    // Update alumni record via AJAX
    function updateAlumniRecord(alumniId, field, value, cellElement) {
        console.log('Updating alumni:', { alumniId, field, value });
        
        // Show saving indicator
        cellElement.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Saving...';
        
        // Prepare data - map year_graduated to alumni_school_year
        const data = {};
        if (field === 'year_graduated') {
            data['alumni_school_year'] = value;
        } else {
            data[field] = value;
        }
        
        console.log('Sending alumni data:', data);
        
        // Send AJAX request
        fetch('/alumni/' + alumniId + '/update', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Accept': 'application/json'
            },
            body: JSON.stringify(data)
        })
        .then(response => {
            console.log('Alumni response status:', response.status);
            if (!response.ok) {
                return response.json().then(err => {
                    console.error('Alumni error response:', err);
                    return Promise.reject(err);
                });
            }
            return response.json();
        })
        .then(data => {
            console.log('Alumni success response:', data);
            if (data.success) {
                // Show success
                cellElement.classList.remove('editing');
                cellElement.innerHTML = value + '<span class="edit-icon"><i class="fas fa-edit"></i></span>';
                
                // Show success message temporarily
                showAlumniSaveIndicator(cellElement, 'saved');
                
                // Reattach event listener
                attachAlumniInlineEditing();
                
                // Show success notification
                Swal.fire({
                    icon: 'success',
                    title: 'Updated!',
                    text: 'Alumni record updated successfully',
                    timer: 2000,
                    showConfirmButton: false,
                    toast: true,
                    position: 'top-end'
                });
            } else {
                throw new Error('Update failed');
            }
        })
        .catch(error => {
            console.error('Error updating alumni record:', error);
            
            // Extract error message
            let errorMessage = 'Failed to update alumni record';
            if (error.message) {
                errorMessage = error.message;
            } else if (error.errors && Object.keys(error.errors).length > 0) {
                errorMessage = Object.values(error.errors)[0][0];
            }
            
            // Show error
            cellElement.classList.remove('editing');
            cellElement.innerHTML = value + '<span class="edit-icon"><i class="fas fa-edit"></i></span>';
            
            showAlumniSaveIndicator(cellElement, 'error');
            
            // Reattach event listener
            attachAlumniInlineEditing();
            
            // Show error notification
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: errorMessage,
                timer: 3000,
                showConfirmButton: false,
                toast: true,
                position: 'top-end'
            });
        });
    }
    
    // Show alumni save indicator
    function showAlumniSaveIndicator(cellElement, status) {
        const indicator = document.createElement('span');
        indicator.className = 'save-indicator ' + status;
        
        if (status === 'saving') {
            indicator.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Saving...';
        } else if (status === 'saved') {
            indicator.innerHTML = '<i class="fas fa-check"></i> Saved';
        } else if (status === 'error') {
            indicator.innerHTML = '<i class="fas fa-times"></i> Error';
        }
        
        cellElement.appendChild(indicator);
        
        setTimeout(() => {
            indicator.remove();
        }, 2000);
    }

    // Alumni department selection
    if (alumniDepartmentGrid) {
        alumniDepartmentGrid.querySelectorAll('.department-logo-card').forEach(function(card) {
            card.addEventListener('click', function() {
                selectedAlumniDepartment = card.getAttribute('data-department');
                renderAlumniTable(); // Show all alumni for department
            });
        });
    }

    // Alumni school year filter
    if (alumniSchoolYearFilter) {
        alumniSchoolYearFilter.addEventListener('change', function() {
            selectedAlumniSchoolYear = this.value;
            renderAlumniTable();
        });
    }

    // Alumni year graduated filter
    if (alumniYearGraduatedFilter) {
        alumniYearGraduatedFilter.addEventListener('change', function() {
            selectedAlumniYearGraduated = this.value;
            renderAlumniTable();
        });
    }

    // Alumni back button logic
    if (alumniBackBtn) {
        alumniBackBtn.addEventListener('click', function() {
            selectedAlumniDepartment = null;
            selectedAlumniSchoolYear = 'all';
            selectedAlumniYearGraduated = 'all';
            if (alumniSchoolYearFilter) alumniSchoolYearFilter.value = 'all';
            if (alumniYearGraduatedFilter) alumniYearGraduatedFilter.value = 'all';
            alumniTableSection.style.display = 'none';
        });
    }

    // Initial state: alumni table hidden
    alumniTableSection.style.display = 'none';
});

// Alumni Modal logic
function openAddAlumniModal() {
    var modal = document.getElementById('addAlumniModal');
    if (modal) modal.style.display = 'flex';
}
function closeAddAlumniModal() {
    var modal = document.getElementById('addAlumniModal');
    if (modal) modal.style.display = 'none';
}
// Move alumni button event listener inside DOMContentLoaded
document.addEventListener('DOMContentLoaded', function() {
    var addAlumniBtn = document.getElementById('openAddAlumniModalBtn');
    if (addAlumniBtn) addAlumniBtn.addEventListener('click', openAddAlumniModal);
});

// Alumni Import Modal logic
var importAlumniBtn = document.getElementById('openImportAlumniModalBtn');
if (importAlumniBtn) importAlumniBtn.addEventListener('click', function() {
    var importModal = document.getElementById('importModal');
    if (importModal) importModal.style.display = 'flex';
});
</script>
            </div>
        </div>

        <!-- Alumni Records UI -->
        <div id="alumniRecordsUI" class="feature-ui">
            <div class="recent-requests">
                <div class="section-header">
                    <h2 class="section-title">Alumni Records Management</h2>
                </div>
                <!-- Alumni Table and Filters (hidden by default) -->
                <div id="alumniTableSection" style="display: none;">
                    <div class="alumni-actions" style="display:flex;align-items:center;gap:10px;justify-content:flex-end;margin-bottom:1.5rem;">
                        <button class="action-btn primary" id="openAddAlumniModalBtn" style="background:#8B0000;color:#fff;font-weight:600;letter-spacing:1px;">
                            <i class="fas fa-user-plus"></i> Add Alumni Manually
                        </button>
                        <button class="import-btn" id="openImportAlumniModalBtn">
                            <i class="fas fa-file-import"></i> Import Alumni Records
                        </button>
                        <button class="action-btn secondary" id="backToAlumniGridBtn" style="margin-left: 10px;">
                            <i class="fas fa-arrow-left"></i> Back
                        </button>
                    </div>
                </div>
                <!-- Department Logo Grid for Alumni -->
                <div id="alumniDepartmentGrid" style="display: flex; flex-wrap: wrap; gap: 2rem; justify-content: center; margin-bottom: 2rem;">
                    @php
                        $departments = [
                            ["name" => "BACHELOR OF SCIENCE IN INFORMATION TECHNOLOGY", "img" => "/images/it.png"],
                            ["name" => "BACHELOR OF SCIENCE IN ENTREPRENEURSHIP", "img" => "/images/bse.png"],
                            ["name" => "BACHELOR OF SCIENCE IN CRIMINOLOGY", "img" => "/images/CRIM.png"],
                            ["name" => "BACHELOR OF ELEMENTARY EDUCATION", "img" => "/images/beed.png"],
                            ["name" => "BACHELOR OF EARLY CHILDHOOD EDUCATION", "img" => "/images/beced.png"],
                            ["name" => "BACHELOR OF SCIENCE IN HOSPITALITY MANAGEMENT", "img" => "/images/hm.png"],
                            ["name" => "BACHELOR OF SCIENCE IN PUBLIC ADMINISTRATION", "img" => "/images/BPA.jpg"],
                        ];
                    @endphp
                    @foreach($departments as $dept)
                    <div class="department-logo-card" data-department="{{ $dept['name'] }}" style="text-align: center; cursor: pointer; width: 180px; padding-bottom: 1rem;">
                        <img src="{{ $dept['img'] }}" alt="{{ $dept['name'] }}" class="department-logo-img">
                        <div style="margin-top: 0.5rem; font-weight: 600; font-size: 13px;">{{ $dept['name'] }}</div>
                    </div>
                    @endforeach
                </div>
                <!-- Alumni Records Table (always visible) -->
                <div id="alumniRecordsTableSection" style="display:none;">
                    <div style="display:flex;align-items:center;gap:1rem;margin-bottom:1rem;flex-wrap:wrap;">
                        <div style="display:flex;align-items:center;gap:0.5rem;">
                            <label for="alumniSchoolYearFilter" style="font-weight:600;color:#8B0000;">School Year:</label>
                            <select id="alumniSchoolYearFilter" name="alumniSchoolYearFilter" class="filter-select" style="min-width:140px; border:1.5px solid #8B0000; border-radius:8px; padding:6px 12px; font-size:1rem;">
                                <option value="all">All Years</option>
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
                        <div style="display:flex;align-items:center;gap:0.5rem;">
                            <label for="alumniYearGraduatedFilter" style="font-weight:600;color:#8B0000;">Year Graduated:</label>
                            <select id="alumniYearGraduatedFilter" name="alumniYearGraduatedFilter" class="filter-select" style="min-width:140px; border:1.5px solid #8B0000; border-radius:8px; padding:6px 12px; font-size:1rem;">
                                <option value="all">All Years</option>
                                @php
                                    $startYear = 2020;
                                    $currentYear = date('Y');
                                @endphp
                                @for ($y = $currentYear; $y >= $startYear; $y--)
                                    <option value="{{ $y }}">{{ $y }}</option>
                                @endfor
                            </select>
                        </div>
                        <div style="display:flex;gap:0.5rem;margin-left:auto;">
                            <button id="exportAlumniRecordsBtn" style="background:#4CAF50;color:#fff;font-weight:600;padding:8px 18px;border:none;border-radius:8px;cursor:pointer;">
                                <i class="fas fa-file-export"></i> Export Records
                            </button>
                            <button id="printAlumniRecordsBtn" style="background:#FFC107;color:#222;font-weight:600;padding:8px 18px;border:none;border-radius:8px;cursor:pointer;">
                                <i class="fas fa-print"></i> Print Records
                            </button>
                            <button id="backSyncAlumniRecordsBtn" style="background:#8B0000;color:#fff;font-weight:600;padding:8px 18px;border:none;border-radius:8px;cursor:pointer;">
                                <i class="fas fa-sync-alt"></i> Back/Sync
                            </button>
                        </div>
                    </div>
                    <div style="max-height:60vh;overflow-y:auto;">
                        <table style="width:100%;border-collapse:collapse;">
                            <thead>
                                <tr style="background:#f5f5f5;">
                                    <th>Student ID</th>
                                    <th>Name</th>
                                    <th>Program</th>
                                    <th>Year Graduated</th>
                                </tr>
                            </thead>
                            <tbody id="alumniRecordsTableBody">
                                <!-- Populated by JS -->
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Alumni Records Button Handlers -->
        <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Alumni Export Records Button
            const exportAlumniRecordsBtn = document.getElementById('exportAlumniRecordsBtn');
            if (exportAlumniRecordsBtn) {
                exportAlumniRecordsBtn.addEventListener('click', function() {
                    let rows = Array.from(document.querySelectorAll('#alumniRecordsTableSection table tbody tr'));
                    if (rows.length === 0) {
                        alert('No alumni records to export.');
                        return;
                    }
                    let csv = 'Student ID,Name,Program,Year Graduated\n';
                    rows.forEach(row => {
                        let cols = Array.from(row.querySelectorAll('td')).map(td => '"' + td.innerText.replace(/"/g, '""') + '"');
                        csv += cols.join(',') + '\n';
                    });
                    let blob = new Blob([csv], { type: 'text/csv' });
                    let link = document.createElement('a');
                    link.href = window.URL.createObjectURL(blob);
                    link.download = 'alumni_records_' + new Date().toISOString().split('T')[0] + '.csv';
                    link.click();
                });
            }

            // Alumni Print Records Button
            const printAlumniRecordsBtn = document.getElementById('printAlumniRecordsBtn');
            if (printAlumniRecordsBtn) {
                printAlumniRecordsBtn.addEventListener('click', function() {
                    const tableSection = document.getElementById('alumniRecordsTableSection');
                    const table = tableSection.querySelector('table');
                    if (!table) {
                        alert('No alumni records to print.');
                        return;
                    }
                    const printWindow = window.open('', '', 'width=900,height=700');
                    printWindow.document.write('<html><head><title>Print Alumni Records</title>');
                    printWindow.document.write('<style>table{width:100%;border-collapse:collapse;}th,td{border:1px solid #888;padding:8px;text-align:left;}th{background:#f5f5f5;}</style>');
                    printWindow.document.write('</head><body>');
                    printWindow.document.write('<h2>Alumni Records</h2>');
                    printWindow.document.write(table.outerHTML);
                    printWindow.document.write('</body></html>');
                    printWindow.document.close();
                    printWindow.focus();
                    printWindow.print();
                    printWindow.close();
                });
            }

            // Alumni Backup/Sync Button with SweetAlert
            const backSyncAlumniRecordsBtn = document.getElementById('backSyncAlumniRecordsBtn');
            if (backSyncAlumniRecordsBtn) {
                backSyncAlumniRecordsBtn.addEventListener('click', function() {
                    // Show loading SweetAlert
                    Swal.fire({
                        title: 'Processing...',
                        html: '<div style="display: flex; flex-direction: column; align-items: center; gap: 20px;"><div class="loading" style="width: 50px; height: 50px; border: 5px solid rgba(139, 0, 0, 0.3); border-radius: 50%; border-top-color: #8B0000; animation: spin 1s ease-in-out infinite;"></div><p style="margin: 0; font-size: 16px; color: #666;">Backing up alumni records to Google Drive...</p></div>',
                        allowOutsideClick: false,
                        allowEscapeKey: false,
                        showConfirmButton: false,
                        width: 400,
                        customClass: {
                            popup: 'swal-custom-popup'
                        }
                    });

                    // Simulate processing delay and show delayed message
                    setTimeout(() => {
                        Swal.fire({
                            title: 'Slow Internet Connection',
                            html: '<div style="display: flex; flex-direction: column; align-items: center; gap: 15px;"><i class="fas fa-exclamation-triangle" style="font-size: 48px; color: #ffc107;"></i><p style="margin: 0; font-size: 16px; color: #666;">The backup process is taking longer than expected due to slow internet connection. Please try again later.</p></div>',
                            icon: 'warning',
                            confirmButtonText: 'Try Again Later',
                            confirmButtonColor: '#8B0000',
                            width: 450,
                            customClass: {
                                popup: 'swal-custom-popup'
                            }
                        });
                    }, 3000); // 3 second delay
                });
            }
        });
        </script>

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
        <script>
        document.addEventListener('DOMContentLoaded', function() {
            var exportReportBtn = document.getElementById('exportReportBtn');
            if (exportReportBtn) {
                exportReportBtn.addEventListener('click', function() {
                    exportReportBtn.disabled = true;
                    exportReportBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Exporting...';
                    // Determine active tab
                    var activeTab = document.querySelector('.report-tab.active');
                    var tabType = 'document_requests';
                    if (activeTab) {
                        if (activeTab.textContent.includes('Student Records')) tabType = 'student_records';
                        else if (activeTab.textContent.includes('User Activity')) tabType = 'user_activity';
                    }
                    fetch('/registrar/report/export?type=' + encodeURIComponent(tabType), {
                        method: 'GET',
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest',
                            'Accept': 'text/csv',
                        },
                    })
                    .then(response => {
                        if (!response.ok) throw new Error('Export failed');
                        return response.blob();
                    })
                    .then(blob => {
                        var link = document.createElement('a');
                        link.href = window.URL.createObjectURL(blob);
                        link.download = tabType + '_report.csv';
                        document.body.appendChild(link);
                        link.click();
                        document.body.removeChild(link);
                        exportReportBtn.disabled = false;
                        exportReportBtn.innerHTML = '<i class="fas fa-file-export"></i> Export Report';
                    })
                    .catch(error => {
                        exportReportBtn.disabled = false;
                        exportReportBtn.innerHTML = '<i class="fas fa-file-export"></i> Export Report';
                        alert('Failed to export report. Please try again.');
                    });
                });
            }
        });
        </script>
            <div class="recent-requests">
                <div class="section-header">
                    <h2 class="section-title">System Reports</h2>
                    <div class="report-actions">
                        <button class="action-btn primary" id="exportReportBtn">
                            <i class="fas fa-file-export"></i> Export Report
                        </button>
                        <!-- Date range picker removed as requested -->
                    </div>
                </div>
                <div class="report-tabs">
                    <button class="report-tab active">Document Requests</button>
                    <button class="report-tab">Student Records</button>
                    <button class="report-tab">User Activity</button>
                </div>
                <div class="report-content">
                   
                    <div class="report-charts">
                        <div class="chart-container">
                            <h4>Requests by Month</h4>
                            <canvas id="requestsByMonthChart" style="width: 100%; max-width: 600px; height: 300px; background: #f5f5f5; border-radius: 8px;"></canvas>
@php
    // Example: Get monthly request counts from the database
    $monthlyRequests = \App\Models\DocumentRequest::selectRaw('MONTH(created_at) as month, COUNT(*) as count')
        ->groupBy('month')
        ->orderBy('month')
        ->get();
    $months = [];
    $counts = [];
    foreach ($monthlyRequests as $row) {
        $months[] = date('M', mktime(0, 0, 0, $row->month, 1));
        $counts[] = $row->count;
    }
@endphp
<script>
document.addEventListener('DOMContentLoaded', function() {
    var ctx = document.getElementById('requestsByMonthChart').getContext('2d');
    new Chart(ctx, {
        type: 'line',
        data: {
            labels: @json($months),
            datasets: [{
                label: 'Requests by Month',
                data: @json($counts),
                borderColor: '#8B0000',
                backgroundColor: 'rgba(139,0,0,0.1)',
                fill: true,
                tension: 0.3,
                pointRadius: 4,
                pointBackgroundColor: '#8B0000',
                pointBorderColor: '#fff',
                borderWidth: 3
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: { display: true, labels: { color: '#8B0000', font: { size: 14, weight: 'bold' } } },
                title: { display: true, text: 'Requests by Month', color: '#8B0000', font: { size: 18, weight: 'bold' } }
            },
            scales: {
                x: { grid: { display: false } },
                y: {
                    beginAtZero: true,
                    grid: { color: '#eee' },
                    ticks: { stepSize: 5 }
                }
            }
        }
    });
});
</script>
                        </div>
                        <div class="chart-container">
                            <h4>Document Type Distribution</h4>
                            
                            <!-- Document Type Progress Bars -->
                            <div class="document-progress-section" style="margin: 1rem 0; padding: 1rem; background: #f8f9fa; border-radius: 8px; border: 1px solid #e9ecef;">
                                <div style="text-align: center; margin-bottom: 1rem; padding: 0.5rem; background: #e3f2fd; border-radius: 6px; border-left: 4px solid #2196F3;">
                                    <small style="color: #1976d2; font-weight: 500;">
                                        <i class="fas fa-info-circle"></i> 
                                        Bar heights are scaled relative to the highest count for better visual comparison. 
                                        Percentages show actual proportion of total requests.
                                    </small>
                                </div>
                                
                                <div class="progress-bars-container" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(80px, 1fr)); gap: 0.7rem; align-items: end; height: 220px; padding: 0 0.5rem;">
                                    @php
                                        // Get document type counts from the database
                                        $documentTypeCounts = \App\Models\RequestedDocument::selectRaw('document_type, COUNT(*) as count')
                                            ->groupBy('document_type')
                                            ->orderBy('count', 'desc')
                                            ->get();
                                        
                                        $totalDocuments = $documentTypeCounts->sum('count');
                                        $maxCount = $documentTypeCounts->max('count') ?? 1; // Get the highest count for relative scaling
                                    @endphp
                                    

                                    
                                    @foreach($documentTypeCounts as $docType)
                                        @php
                                            // Calculate relative percentage based on highest count, not total
                                            $relativePercentage = $maxCount > 0 ? ($docType->count / $maxCount) * 100 : 0;
                                            $barHeight = 180; // Fixed height for all bars

                                            // Keep explicit mapping for known document types but provide a rotating
                                            // palette fallback so each bar gets a distinct color when the mapping
                                            // doesn't include the type or when there are many types.
                                            $mapping = [
                                                'Transcript' => ['#8B0000', '#A52A2A', '#DC143C'],      // Dark Red variations
                                                'Diploma' => ['#d43773ff', '#5f0f23ff', '#500d1dff'],         // Gold variations
                                                'Certificate' => ['#2196F3', '#42A5F5', '#64B5F6'],     // Blue variations
                                                'TOR' => ['#4CAF50', '#66BB6A', '#81C784'],            // Green variations
                                                'Form 137' => ['#FF9800', '#FFB74D', '#FFCC02'],        // Orange variations
                                                'Form 138' => ['#9C27B0', '#BA68C8', '#CE93D8'],        // Purple variations
                                                'Good Moral' => ['#795548', '#8D6E63', '#A1887F'],      // Brown variations
                                                'Enrollment Certificate' => ['#607D8B', '#78909C', '#90A4AE'], // Blue Grey variations
                                                'Honorable Dismissal' => ['#E91E63', '#F06292', '#F8BBD9'], // Pink variations
                                                'Transfer Credential' => ['#00BCD4', '#4DD0E1', '#B2EBF2'], // Cyan variations
                                                'Authentication' => ['#FF5722', '#FF8A65', '#FFCCBC'],    // Deep Orange variations
                                                'Verification' => ['#673AB7', '#9575CD', '#D1C4E9']      // Deep Purple variations
                                            ];

                                            // A rotating palette of triads to ensure visually distinct bars
                                            $rotatingPalette = [
                                                ['#8B0000','#A52A2A','#DC143C'],
                                                ['#D4AF37','#FFD700','#FFA500'],
                                                ['#2196F3','#42A5F5','#64B5F6'],
                                                ['#4CAF50','#66BB6A','#81C784'],
                                                ['#FF9800','#FFB74D','#FFCC02'],
                                                ['#9C27B0','#BA68C8','#CE93D8'],
                                                ['#795548','#8D6E63','#A1887F'],
                                                ['#607D8B','#78909C','#90A4AE'],
                                                ['#E91E63','#F06292','#F8BBD9'],
                                                ['#00BCD4','#4DD0E1','#B2EBF2'],
                                                ['#FF5722','#FF8A65','#FFCCBC'],
                                                ['#673AB7','#9575CD','#D1C4E9']
                                            ];

                                            // Prefer explicit mapping, otherwise pick from rotating palette using loop index
                                            if (isset($mapping[$docType->document_type])) {
                                                $colorSet = $mapping[$docType->document_type];
                                            } else {
                                                // Use Blade's $loop index to pick a palette entry so adjacent bars are different
                                                $idx = isset($loop) ? $loop->index : 0;
                                                $colorSet = $rotatingPalette[$idx % count($rotatingPalette)];
                                            }
                                            $barColor = $colorSet[0]; // Main color for the bar
                                        @endphp
                                        
                                        <div class="progress-bar-item" style="text-align: center; display: flex; flex-direction: column; align-items: center; min-width: 80px;">
                                            <div class="progress-bar-container" style="position: relative; height: {{ $barHeight - 20 }}px; width: 28px; margin: 0 auto 0.3rem; background: #e9ecef; border-radius: 6px; overflow: hidden; box-shadow: inset 0 2px 4px rgba(0,0,0,0.1);">
                                                <div class="progress-bar-fill" 
                                                     data-percentage="{{ $relativePercentage }}" 
                                                     data-count="{{ $docType->count }}"
                                                     data-color="{{ $barColor }}"
                                                     style="position: absolute; bottom: 0; left: 0; width: 100%; height: {{ ($relativePercentage / 100) * 160 }}px; background: linear-gradient(180deg, {{ $colorSet[0] }} 0%, {{ $colorSet[1] }} 40%, {{ $colorSet[2] }} 100%); border-radius: 6px; transition: height 1.5s cubic-bezier(0.4, 0, 0.2, 1); box-shadow: 0 2px 8px rgba(0,0,0,0.2);">
                                                    
                                                    <!-- Internal color patterns -->
                                                    <div class="bar-pattern" style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; background: repeating-linear-gradient(45deg, transparent, transparent 3px, rgba(255,255,255,0.15) 3px, rgba(255,255,255,0.15) 6px);"></div>
                                                    
                                                    <!-- Top highlight -->
                                                    <div class="bar-highlight" style="position: absolute; top: 0; left: 0; width: 100%; height: 25%; background: linear-gradient(180deg, rgba(255,255,255,0.4) 0%, rgba(255,255,255,0.1) 50%, transparent 100%); border-radius: 6px 6px 0 0;"></div>
                                                    
                                                    <!-- Side highlight -->
                                                    <div class="bar-side-highlight" style="position: absolute; top: 0; left: 0; width: 35%; height: 100%; background: linear-gradient(90deg, rgba(255,255,255,0.25) 0%, rgba(255,255,255,0.05) 50%, transparent 100%); border-radius: 6px 0 0 6px;"></div>
                                                    
                                                    <!-- Bottom shadow -->
                                                    <div class="bar-bottom-shadow" style="position: absolute; bottom: 0; left: 0; width: 100%; height: 20%; background: linear-gradient(180deg, transparent 0%, rgba(0,0,0,0.1) 100%); border-radius: 0 0 6px 6px;"></div>
                                                </div>
                                            </div>
                                            
                                                                                            <div class="progress-stats" style="text-align: center;">
                                                    <div class="document-count" style="font-size: 1rem; font-weight: 700; color: {{ $barColor }}; margin-bottom: 0.15rem;">
                                                        {{ $docType->count }}
                                                    </div>
                                                    <div class="document-percentage" style="font-size: 0.7rem; color: var(--gray-dark); font-weight: 500; margin-bottom: 0.15rem;">
                                                        {{ number_format(($docType->count / $totalDocuments) * 100, 1) }}% of total
                                                    </div>
                                                    <div class="document-type-name" style="font-size: 0.6rem; color: {{ $barColor }}; font-weight: 600; text-transform: uppercase; letter-spacing: 0.5px; line-height: 1.2; max-width: 80px;">
                                                        {{ $docType->document_type }}
                                                    </div>
                                                </div>
                                        </div>
                                    @endforeach
                                </div>
                                
                                @if($documentTypeCounts->isEmpty())
                                    <div style="text-align: center; padding: 2rem; color: #666; font-style: italic;">
                                        No document requests found.
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                    
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

        <!-- Live Chat/Inquiries UI removed per request (UI-only). Placeholder kept to prevent JS errors if referenced elsewhere. -->
        <div id="liveChatUI" class="feature-ui" style="display:none;"></div>
    </main>
    <script>
        document.querySelector('.toggle-btn').addEventListener('click', function() {
            document.querySelector('.sidebar').classList.toggle('collapsed');
        });
        function setCircularProgress(selector, percent) {
            const circle = document.querySelector(selector);
            if (circle) {
                const radius = 50;
                const circumference = 2 * Math.PI * radius;
                const offset = circumference - (percent / 100) * circumference;
                circle.style.strokeDashoffset = offset;
            }
        }
        document.addEventListener('DOMContentLoaded', function() {
            const cards = document.querySelectorAll('.analytics-card, .progress-card');
            cards.forEach((card, index) => {
                setTimeout(() => {
                    card.style.opacity = '1';
                    card.style.transform = 'translateY(0)';
                }, index * 100);
            });
            // fetchData(); // Temporarily disabled
        });
        // Temporarily disabled due to API endpoint issues
        /*
        async function fetchData() {
            try {
                // Fetch analytics from our API endpoint
                const response = await fetch('/registrar/pending-count');
                const data = await response.json();
                
                // Update analytics with the fetched data
                const analytics = {
                    pending: data.pending || 0,
                    approved: data.approved_today || 0,
                    completed: data.completed_today || 0,
                    rejected: data.rejected_today || 0
                };
                
                updateAnalytics(analytics);
            } catch (error) {
                console.error('Error fetching data:', error);
            }
        }

        // Refresh analytics every 30 seconds
        setInterval(fetchData, 30000);
        */
        
        // Update unified progress card
        function updateUnifiedProgressCard(analytics) {
            const total = analytics.pending + analytics.approved + analytics.completed + analytics.rejected;
            
            // Update total requests
            document.getElementById('totalRequests').textContent = total;
            
            // Calculate percentages
            const pendingPercent = total > 0 ? (analytics.pending / total) * 100 : 0;
            const approvedPercent = total > 0 ? (analytics.approved / total) * 100 : 0;
            const completedPercent = total > 0 ? (analytics.completed / total) * 100 : 0;
            const rejectedPercent = total > 0 ? (analytics.rejected / total) * 100 : 0;
            
            // Update legend values and percentages
            document.getElementById('pendingLegendValue').textContent = analytics.pending;
            document.getElementById('approvedLegendValue').textContent = analytics.approved;
            document.getElementById('completedLegendValue').textContent = analytics.completed;
            document.getElementById('rejectedLegendValue').textContent = analytics.rejected;
            
            document.getElementById('pendingLegendPercent').textContent = pendingPercent.toFixed(1) + '%';
            document.getElementById('approvedLegendPercent').textContent = approvedPercent.toFixed(1) + '%';
            document.getElementById('completedLegendPercent').textContent = completedPercent.toFixed(1) + '%';
            document.getElementById('rejectedLegendPercent').textContent = rejectedPercent.toFixed(1) + '%';
            
            // Update progress segments with new donut structure
            if (total > 0) {
                const circumference = 2 * Math.PI * 80; // r=80
                
                // Calculate dash arrays
                const completedDash = (completedPercent / 100) * circumference;
                const approvedDash = (approvedPercent / 100) * circumference;
                const pendingDash = (pendingPercent / 100) * circumference;
                const rejectedDash = (rejectedPercent / 100) * circumference;
                
                // Update segments
                const completedSegment = document.querySelector('.completed-segment');
                const approvedSegment = document.querySelector('.approved-segment');
                const pendingSegment = document.querySelector('.pending-segment');
                const rejectedSegment = document.querySelector('.rejected-segment');
                
                if (completedSegment) {
                    completedSegment.style.strokeDasharray = `${completedDash} ${circumference - completedDash}`;
                }
                
                if (approvedSegment) {
                    approvedSegment.style.strokeDasharray = `${approvedDash} ${circumference - approvedDash}`;
                    approvedSegment.style.strokeDashoffset = -completedDash;
                }
                
                if (pendingSegment) {
                    pendingSegment.style.strokeDasharray = `${pendingDash} ${circumference - pendingDash}`;
                    pendingSegment.style.strokeDashoffset = -(completedDash + approvedDash);
                }
                
                if (rejectedSegment) {
                    rejectedSegment.style.strokeDasharray = `${rejectedDash} ${circumference - rejectedDash}`;
                    rejectedSegment.style.strokeDashoffset = -(completedDash + approvedDash + pendingDash);
                }
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
            
            // Update unified progress card
            updateUnifiedProgressCard(analytics);
            
            // Update individual progress cards if they exist
            const pendingProgress = document.getElementById('pendingRequestsProgress');
            const approvedProgress = document.getElementById('approvedRequestsProgress');
            const completedProgress = document.getElementById('completedRequestsProgress');
            const rejectedProgress = document.getElementById('rejectedRequestsProgress');
            
            if (pendingProgress) pendingProgress.textContent = analytics.pending;
            if (approvedProgress) approvedProgress.textContent = analytics.approved;
            if (completedProgress) completedProgress.textContent = analytics.completed;
            if (rejectedProgress) rejectedProgress.textContent = analytics.rejected;
            
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
        const fileInput = document.getElementById('fileInput');
        if (fileInput) {
            fileInput.addEventListener('change', function(e) {
                if (this.files.length > 0) {
                    const fileName = this.files[0].name;
                    const fileNameElement = document.getElementById('fileName');
                    const fileInfo = document.getElementById('fileInfo');
                    if (fileNameElement) fileNameElement.textContent = fileName;
                    if (fileInfo) fileInfo.style.display = 'block';
                } else {
                    const fileInfo = document.getElementById('fileInfo');
                    if (fileInfo) fileInfo.style.display = 'none';
                }
            });
        }

        // Handle drag and drop
        const fileUpload = document.querySelector('.file-upload');
        if (fileUpload) {
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
                    const fileInput = document.getElementById('fileInput');
                    if (fileInput) {
                        fileInput.files = e.dataTransfer.files;
                        const fileName = e.dataTransfer.files[0].name;
                        const fileNameElement = document.getElementById('fileName');
                        const fileInfo = document.getElementById('fileInfo');
                        if (fileNameElement) fileNameElement.textContent = fileName;
                        if (fileInfo) fileInfo.style.display = 'block';
                    }
                }
            });
        }



        // Navigation between sections
        document.addEventListener('DOMContentLoaded', function() {
            const menuLinks = document.querySelectorAll('.menu-link');
            
            menuLinks.forEach(link => {
                link.addEventListener('click', function(e) {
                    e.preventDefault();
                    const section = this.getAttribute('data-section');
                    // Hide ALL sections first
                    document.querySelectorAll('.feature-ui').forEach(ui => {
                        ui.classList.remove('active');
                    });
                    document.getElementById('dashboardSections').style.display = 'none';
                    // Update page title
                    const pageTitle = document.getElementById('pageTitle');
                    // Show only the selected section
                    if (section === 'dashboard') {
                        document.getElementById('dashboardSections').style.display = 'block';
                        pageTitle.textContent = 'Document Request Dashboard';
                    } else if (section === 'documentRequests') {
                        document.getElementById('documentRequestsUI').classList.add('active');
                        pageTitle.textContent = 'Document Requests Management';
                    } else if (section === 'studentRecords') {
                        document.getElementById('studentRecordsUI').classList.add('active');
                        pageTitle.textContent = 'Student Records Management';
                    } else if (section === 'reports') {
                        document.getElementById('reportsUI').classList.add('active');
                        pageTitle.textContent = 'System Reports';
                    } else if (section === 'systemLog') {
                        document.getElementById('systemLogUI').classList.add('active');
                        pageTitle.textContent = 'System Log';
                    } else if (section === 'liveChat') {
                        document.getElementById('liveChatUI').classList.add('active');
                        pageTitle.textContent = 'Live Chat Inquiries';
                        // Initialize live chat when section is activated
                        if (typeof fetchConversations === 'function') {
                            fetchConversations();
                            const chatHeaderTitle = document.getElementById('chat-header-title');
                            const chatInput = document.getElementById('chat-input');
                            const sendBtn = document.querySelector('#chat-form button');
                            const chatMessages = document.getElementById('chat-messages');
                            if (chatHeaderTitle) chatHeaderTitle.textContent = 'Select a conversation';
                            if (chatInput) {
                                chatInput.value = '';
                                chatInput.disabled = true;
                            }
                            if (sendBtn) sendBtn.disabled = true;
                            if (chatMessages) chatMessages.innerHTML = '';
                        }
                    }
                    // Update active menu item
                    document.querySelectorAll('.menu-link').forEach(l => l.classList.remove('active'));
                    this.classList.add('active');
                });
            });
        });
        

        

        // Report tab switching with AJAX data loading
        document.addEventListener('DOMContentLoaded', function() {
            const tabs = document.querySelectorAll('.report-tab');
            const reportContent = document.querySelector('.report-content');
            const charts = document.querySelector('.report-charts');
            const filters = document.querySelector('.report-filters');
            let tableSection = null;
            let logsSection = null;

            function clearReportContent() {
                if (charts) charts.style.display = 'none';
                if (filters) filters.style.display = 'none';
                if (tableSection) tableSection.remove();
                if (logsSection) logsSection.remove();
            }

            function showDocumentRequests() {
                if (charts) charts.style.display = '';
                if (filters) filters.style.display = '';
            }

            function showStudentRecords(page = 1, sort = 'created_at', dir = 'desc', program = 'all', year = 'all', status = 'all') {
                clearReportContent();
                // Controls
                tableSection = document.createElement('div');
                tableSection.innerHTML = `
                    <h4 style=\"margin:1rem 0;color:#8B0000;\">Student Records</h4>
                    <div style=\"margin-bottom:1rem;display:flex;gap:1rem;\">
                        <select id=\"studentProgramFilter\"><option value='all'>All Programs</option><option value='BACHELOR OF SCIENCE IN INFORMATION TECHNOLOGY'>BSIT</option><option value='BACHELOR OF SCIENCE IN ENTREPRENEURSHIP'>BSE</option><option value='BACHELOR OF SCIENCE IN CRIMINOLOGY'>Criminology</option></select>
                        <select id=\"studentYearFilter\"><option value='all'>All Years</option><option value='1'>1</option><option value='2'>2</option><option value='3'>3</option><option value='4'>4</option></select>
                        <select id=\"studentStatusFilter\"><option value='all'>All Statuses</option><option value='active'>Active</option><option value='inactive'>Inactive</option></select>
                    </div>
                    <table style=\"width:100%;border-collapse:collapse;\">
                        <thead>
                            <tr>
                                <th data-sort='student_id'>ID</th>
                                <th data-sort='name'>Name</th>
                                <th data-sort='program'>Program</th>
                                <th data-sort='year_level'>Year Level</th>
                                <th data-sort='status'>Status</th>
                            </tr>
                        </thead>
                        <tbody id=\"studentTableBody\"></tbody>
                    </table>
                    <div id=\"studentPagination\" style=\"margin-top:1rem;text-align:right;\"></div>
                `;
                reportContent.appendChild(tableSection);
                function load(page, sort, dir, program, year, status) {
                    fetch(`/registrar/report/student-records?page=${page}&sort=${sort}&dir=${dir}&program=${program}&year_level=${year}&status=${status}`)
                        .then(res => res.json())
                        .then(data => {
                            const tbody = document.getElementById('studentTableBody');
                            tbody.innerHTML = data.data.map(s => `
                                <tr>
                                    <td>${s.student_id}</td>
                                    <td>${s.first_name} ${s.middle_name ? s.middle_name + ' ' : ''}${s.last_name}</td>
                                    <td>${s.program}</td>
                                    <td>${s.year_level}</td>
                                    <td>${s.status}</td>
                                </tr>
                            `).join('');
                            // Pagination
                            const pag = document.getElementById('studentPagination');
                            let pagHtml = '';
                            for (let i = 1; i <= data.last_page; i++) {
                                pagHtml += `<button class='action-btn' data-page='${i}' style='${i===data.current_page?'background:#8B0000;color:#fff':'background:#eee;'}'>${i}</button>`;
                            }
                            pag.innerHTML = pagHtml;
                            Array.from(pag.querySelectorAll('button')).forEach(btn => {
                                btn.onclick = () => load(parseInt(btn.dataset.page), sort, dir, program, year, status);
                            });
                        });
                }
                // Initial load
                load(page, sort, dir, program, year, status);
                // Filter events
                tableSection.querySelector('#studentProgramFilter').onchange = e => load(1, sort, dir, e.target.value, year, status);
                tableSection.querySelector('#studentYearFilter').onchange = e => load(1, sort, dir, program, e.target.value, status);
                tableSection.querySelector('#studentStatusFilter').onchange = e => load(1, sort, dir, program, year, e.target.value);
                // Sorting events
                Array.from(tableSection.querySelectorAll('th[data-sort]')).forEach(th => {
                    th.style.cursor = 'pointer';
                    th.onclick = () => {
                        let newDir = dir === 'asc' ? 'desc' : 'asc';
                        load(1, th.dataset.sort, newDir, program, year, status);
                    };
                });
            }

            function showUserActivity(page = 1, sort = 'created_at', dir = 'desc', type = 'all', user_id = 'all') {
                clearReportContent();
                logsSection = document.createElement('div');
                logsSection.innerHTML = `
                    <h4 style=\"margin:1rem 0;color:#8B0000;\">User Activity (System Logs)</h4>
                    <div style=\"margin-bottom:1rem;display:flex;gap:1rem;\">
                        <select id=\"logTypeFilter\"><option value='all'>All Types</option><option value='login'>Login</option><option value='request_received'>Request Received</option><option value='approved'>Approved</option><option value='rejected'>Rejected</option><option value='student_added'>Student Added</option></select>
                        <input id=\"logUserFilter\" type=\"text\" placeholder=\"User ID\" style=\"padding:4px 8px;border-radius:4px;border:1px solid #ccc;\" />
                    </div>
                    <table style=\"width:100%;border-collapse:collapse;\">
                        <thead>
                            <tr>
                                <th data-sort='type'>Type</th>
                                <th data-sort='message'>Message</th>
                                <th data-sort='user_id'>User</th>
                                <th data-sort='created_at'>Date</th>
                            </tr>
                        </thead>
                        <tbody id=\"logsTableBody\"></tbody>
                    </table>
                    <div id=\"logsPagination\" style=\"margin-top:1rem;text-align:right;\"></div>
                `;
                reportContent.appendChild(logsSection);
                function load(page, sort, dir, type, user_id) {
                    fetch(`/registrar/report/user-activity?page=${page}&sort=${sort}&dir=${dir}&type=${type}&user_id=${user_id}`)
                        .then(res => res.json())
                        .then(data => {
                            const tbody = document.getElementById('logsTableBody');
                            tbody.innerHTML = data.data.map(l => `
                                <tr>
                                    <td>${l.type}</td>
                                    <td>${l.message}</td>
                                    <td>${l.user_id ?? ''}</td>
                                    <td>${l.created_at}</td>
                                </tr>
                            `).join('');
                            // Pagination
                            const pag = document.getElementById('logsPagination');
                            let pagHtml = '';
                            for (let i = 1; i <= data.last_page; i++) {
                                pagHtml += `<button class='action-btn' data-page='${i}' style='${i===data.current_page?'background:#8B0000;color:#fff':'background:#eee;'}'>${i}</button>`;
                            }
                            pag.innerHTML = pagHtml;
                            Array.from(pag.querySelectorAll('button')).forEach(btn => {
                                btn.onclick = () => load(parseInt(btn.dataset.page), sort, dir, type, user_id);
                            });
                        });
                }
                // Initial load
                load(page, sort, dir, type, user_id);
                // Filter events
                logsSection.querySelector('#logTypeFilter').onchange = e => load(1, sort, dir, e.target.value, user_id);
                logsSection.querySelector('#logUserFilter').oninput = e => load(1, sort, dir, type, e.target.value);
                // Sorting events
                Array.from(logsSection.querySelectorAll('th[data-sort]')).forEach(th => {
                    th.style.cursor = 'pointer';
                    th.onclick = () => {
                        let newDir = dir === 'asc' ? 'desc' : 'asc';
                        load(1, th.dataset.sort, newDir, type, user_id);
                    };
                });
            }

            tabs.forEach(tab => {
                tab.addEventListener('click', function() {
                    tabs.forEach(t => t.classList.remove('active'));
                    this.classList.add('active');
                    clearReportContent();
                    if (this.textContent.includes('Document Requests')) {
                        showDocumentRequests();
                    } else if (this.textContent.includes('Student Records')) {
                        showStudentRecords();
                    } else if (this.textContent.includes('User Activity')) {
                        showUserActivity();
                    }
                });
            });
            // Show Document Requests by default
            showDocumentRequests();
        });

        // Student Records Department Logo Grid Logic

        // Document Request Filter Tabs, Document Type, Date, and Search Logic with Suggestions
        document.addEventListener('DOMContentLoaded', function() {
            const filterTabs = document.querySelectorAll('.filter-tab');
            const requestRows = document.querySelectorAll('#documentRequestsTable tbody tr');
            const docTypeSelect = document.querySelector('.filter-select');
            const dateInput = document.querySelector('.filter-date');
            const searchInput = document.getElementById('docSearchInput');
            const suggestionsBox = document.getElementById('docSearchSuggestions');

            function applyFilters(searchOverride) {
                const activeTab = document.querySelector('.filter-tab.active');
                const statusFilter = activeTab ? activeTab.textContent.trim().toLowerCase() : 'all requests';
                const docType = docTypeSelect ? docTypeSelect.value.trim().toLowerCase() : 'all document types';
                const dateVal = dateInput ? dateInput.value : '';
                const searchVal = typeof searchOverride === 'string' ? searchOverride : (searchInput ? searchInput.value.trim().toLowerCase() : '');

                requestRows.forEach(row => {
                    const status = row.getAttribute('data-status') || '';
                    const documents = row.getAttribute('data-documents') || '';
                    const date = row.getAttribute('data-date') || '';
                    const rowText = row.textContent.toLowerCase();

                    let statusMatch = (statusFilter === 'all requests') || (status && status.includes(statusFilter));
                    let docTypeMatch = (docType === 'all document types') || (documents && documents.includes(docType));
                    let dateMatch = (!dateVal) || (date === dateVal);
                    let searchMatch = (!searchVal) || rowText.includes(searchVal);

                    if (statusMatch && docTypeMatch && dateMatch && searchMatch) {
                        row.style.display = '';
                    } else {
                        row.style.display = 'none';
                    }
                });
            }

            // Suggestion logic
            function getSuggestions(query) {
                if (!query) return [];
                const suggestions = new Set();
                requestRows.forEach(row => {
                    if (row.style.display === 'none') return; // Only suggest from visible rows
                    const cells = row.querySelectorAll('td');
                    cells.forEach(cell => {
                        const val = cell.textContent.trim();
                        if (val && val.toLowerCase().includes(query)) {
                            suggestions.add(val);
                        }
                    });
                });
                return Array.from(suggestions).slice(0, 8); // Limit to 8 suggestions
            }

            function showSuggestions(list) {
                suggestionsBox.innerHTML = '';
                if (!list.length) {
                    suggestionsBox.style.display = 'none';
                    return;
                }
                list.forEach(item => {
                    const li = document.createElement('li');
                    li.textContent = item;
                    li.style.padding = '8px 12px';
                    li.style.cursor = 'pointer';
                    li.addEventListener('mousedown', function(e) {
                        e.preventDefault();
                        searchInput.value = item;
                        suggestionsBox.style.display = 'none';
                        applyFilters(item.toLowerCase());
                    });
                    li.addEventListener('mouseover', function() {
                        li.style.background = '#f5f5f5';
                    });
                    li.addEventListener('mouseout', function() {
                        li.style.background = '#fff';
                    });
                    suggestionsBox.appendChild(li);
                });
                suggestionsBox.style.display = 'block';
            }

            if (searchInput) {
                searchInput.addEventListener('input', function(e) {
                    const val = searchInput.value.trim().toLowerCase();
                    if (val) {
                        const suggestions = getSuggestions(val);
                        showSuggestions(suggestions);
                    } else {
                        suggestionsBox.style.display = 'none';
                        applyFilters();
                    }
                });
                searchInput.addEventListener('keydown', function(e) {
                    if (e.key === 'Enter') {
                        e.preventDefault();
                        suggestionsBox.style.display = 'none';
                        applyFilters();
                    }
                });
                // Hide suggestions on blur
                searchInput.addEventListener('blur', function() {
                    setTimeout(() => { suggestionsBox.style.display = 'none'; }, 120);
                });
            }

            filterTabs.forEach(tab => {
                tab.addEventListener('click', function() {
                    filterTabs.forEach(t => t.classList.remove('active'));
                    this.classList.add('active');
                    applyFilters();
                });
            });
            if (docTypeSelect) {
                docTypeSelect.addEventListener('change', applyFilters);
            }
            if (dateInput) {
                dateInput.addEventListener('change', applyFilters);
            }
        });
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

        // Document Request Filters - Enhanced and Fixed
        function setupDocumentRequestFilters() {
            console.log('Setting up document request filters...');
            
            // Enhanced filter function
            function filterTable() {
                const table = document.getElementById('documentRequestsTable');
                if (!table) {
                    console.log('Document requests table not found');
                    return;
                }
                
                const rows = Array.from(table.querySelectorAll('tbody tr'));
                const activeTab = document.querySelector('#documentRequestsUI .filter-tab.active');
                const activeStatus = activeTab ? activeTab.textContent.trim().toLowerCase() : 'all requests';
                const documentTypeFilter = document.querySelector('#documentRequestsUI .filter-select').value;
                const dateFilter = document.querySelector('#documentRequestsUI .filter-date').value;
                const searchQuery = document.querySelector('#documentRequestsUI .search-bar input').value.toLowerCase();
                const releaseSortSelect = document.getElementById('releaseSortSelect');
                const releaseSort = releaseSortSelect ? releaseSortSelect.value : '';
                
                console.log('Filtering by:', { status: activeStatus, documentType: documentTypeFilter, date: dateFilter, search: searchQuery, releaseSort });
                console.log('Found rows:', rows.length);
                
                // Sort rows if needed
                if (releaseSort === 'urgent') {
                    rows.sort((a, b) => {
                        const aDays = parseInt(a.getAttribute('data-days-until-release') || '999');
                        const bDays = parseInt(b.getAttribute('data-days-until-release') || '999');
                        return aDays - bDays; // Ascending order (most urgent first - lower days until release)
                    });
                } else if (releaseSort === 'latest') {
                    rows.sort((a, b) => {
                        const aDays = parseInt(a.getAttribute('data-days-until-release') || '0');
                        const bDays = parseInt(b.getAttribute('data-days-until-release') || '0');
                        return bDays - aDays; // Descending order (latest release first)
                    });
                }
                
                let visibleCount = 0;
                rows.forEach(row => {
                    const status = row.getAttribute('data-status');
                    const documents = row.getAttribute('data-documents');
                    const date = row.getAttribute('data-date');
                    const daysUntilRelease = parseInt(row.getAttribute('data-days-until-release') || '0');
                    
                    let show = true;
                    
                    // Status filter
                    if (activeStatus === 'all requests') {
                        show = true;
                    } else if (activeStatus === 'pending') {
                        show = status === 'pending_registrar_approval';
                    } else if (activeStatus === 'approved') {
                        show = status === 'approved';
                    } else if (activeStatus === 'completed') {
                        show = status === 'completed';
                    } else if (activeStatus === 'rejected') {
                        show = status === 'rejected';
                    }
                    
                    // Document type filter
                    if (show && documentTypeFilter && documentTypeFilter !== 'All Document Types') {
                        show = documents && documents.toLowerCase().includes(documentTypeFilter.toLowerCase());
                    }
                    
                    // Date filter
                    if (show && dateFilter) {
                        show = date === dateFilter;
                    }
                    
                    // Search filter
                    if (show && searchQuery) {
                        const rowText = row.textContent.toLowerCase();
                        show = rowText.includes(searchQuery);
                    }
                    
                    row.style.display = show ? '' : 'none';
                    if (show) visibleCount++;
                });
                
                // Reorder table rows based on sort
                if (releaseSort) {
                    const tbody = table.querySelector('tbody');
                    rows.forEach(row => tbody.appendChild(row));
                }
                
                console.log('Visible rows after filtering:', visibleCount);
                
                // Show message if no results
                let noResultsRow = table.querySelector('.no-results-row');
                if (visibleCount === 0) {
                    if (!noResultsRow) {
                        noResultsRow = document.createElement('tr');
                        noResultsRow.className = 'no-results-row';
                        table.querySelector('tbody').appendChild(noResultsRow);
                    }
                    
                    noResultsRow.innerHTML = '<td colspan="7" style="text-align: center; padding: 2rem; color: #666; font-style: italic;">No requests found matching the selected filters.</td>';
                    noResultsRow.style.display = '';
                } else {
                    if (noResultsRow) {
                        noResultsRow.style.display = 'none';
                    }
                }
            }
            
            // Add click handlers to filter tabs
            const filterTabs = document.querySelectorAll('#documentRequestsUI .filter-tab');
            console.log('Found filter tabs:', filterTabs.length);
            
            filterTabs.forEach(tab => {
                console.log('Adding click handler to:', tab.textContent);
                tab.addEventListener('click', function() {
                    console.log('Tab clicked:', this.textContent);
                    
                    // Remove active class from all tabs
                    filterTabs.forEach(t => t.classList.remove('active'));
                    // Add active class to clicked tab
                    this.classList.add('active');
                    // Filter the table
                    filterTable();
                });
            });
            
            // Add change handlers for document type and date filters
            const documentTypeFilter = document.querySelector('#documentRequestsUI .filter-select');
            const dateFilter = document.querySelector('#documentRequestsUI .filter-date');
            
            if (documentTypeFilter) {
                documentTypeFilter.addEventListener('change', function() {
                    console.log('Document type filter changed:', this.value);
                    filterTable();
                });
            }
            
            if (dateFilter) {
                dateFilter.addEventListener('change', function() {
                    console.log('Date filter changed:', this.value);
                    filterTable();
                });
            }
            
            // Add change handler for release sort
            const releaseSortSelect = document.getElementById('releaseSortSelect');
            if (releaseSortSelect) {
                releaseSortSelect.addEventListener('change', function() {
                    console.log('Release sort changed:', this.value);
                    filterTable();
                });
            }
            
            // Add search functionality
            const searchInput = document.querySelector('#documentRequestsUI .search-bar input');
            if (searchInput) {
                searchInput.addEventListener('input', function() {
                    console.log('Search input changed:', this.value);
                    filterTable();
                });
            }
            
            // Add clear filters functionality
            const clearFiltersBtn = document.getElementById('clearFiltersBtn');
            if (clearFiltersBtn) {
                clearFiltersBtn.addEventListener('click', function() {
                    console.log('Clearing all filters');
                    // Reset filter tabs
                    filterTabs.forEach(tab => tab.classList.remove('active'));
                    if (filterTabs.length > 0) filterTabs[0].classList.add('active'); // Set "All Requests" as active
                    // Reset document type filter
                    if (documentTypeFilter) {
                        documentTypeFilter.value = 'All Document Types';
                    }
                    // Reset date filter
                    if (dateFilter) {
                        dateFilter.value = '';
                    }
                    // Reset search input
                    if (searchInput) {
                        searchInput.value = '';
                    }
                    // Re-filter the table
                    filterTable();
                });
            }
            
            console.log('Document request filters setup complete');
        }
        
        // Initialize filters when DOM is ready
        document.addEventListener('DOMContentLoaded', function() {
            console.log('DOM loaded, setting up filters...');
            
            // Setup filters immediately
            setupDocumentRequestFilters();
            
            // Also setup filters when document requests section is activated
            const documentRequestsLink = document.querySelector('.menu-link[data-section="documentRequests"]');
            if (documentRequestsLink) {
                console.log('Found document requests menu link');
                documentRequestsLink.addEventListener('click', function() {
                    console.log('Document requests section activated, setting up filters...');
                    // Small delay to ensure the section is visible
                    setTimeout(setupDocumentRequestFilters, 100);
                });
            } else {
                console.log('Document requests menu link not found');
            }
            
            // Debug: Check if elements exist
            setTimeout(() => {
                console.log('=== DEBUG: Checking filter elements ===');
                console.log('Filter tabs found:', document.querySelectorAll('#documentRequestsUI .filter-tab').length);
                console.log('Filter select found:', document.querySelector('#documentRequestsUI .filter-select'));
                console.log('Filter date found:', document.querySelector('#documentRequestsUI .filter-date'));
                console.log('Search input found:', document.querySelector('#documentRequestsUI .search-bar input'));
                console.log('Clear button found:', document.getElementById('clearFiltersBtn'));
                console.log('Table found:', document.getElementById('documentRequestsTable'));
                console.log('=====================================');
            }, 1000);
    }); // <-- closes document.addEventListener('DOMContentLoaded', ...)
        
        // Document Type Progress Bar Animations
        document.addEventListener('DOMContentLoaded', function() {
            console.log('Setting up progress bar animations...');
            
            function animateProgressBars() {
                const progressBars = document.querySelectorAll('.progress-bar-fill');
                console.log('Found progress bars:', progressBars.length);
                
                if (progressBars.length === 0) {
                    console.log('No progress bars found, checking if Reports section is visible...');
                    return;
                }
                
                progressBars.forEach((bar, index) => {
                    const relativePercentage = parseFloat(bar.getAttribute('data-percentage'));
                    const count = parseInt(bar.getAttribute('data-count'));
                    const color = bar.getAttribute('data-color');
                    
                    console.log(`Bar ${index + 1}: ${relativePercentage}% relative, ${count} requests, color: ${color}`);
                    
                    // Calculate the height based on relative percentage (0-100% of max height)
                    const barHeight = 180; // Match the PHP variable in Reports section
                    const fillHeight = Math.max((relativePercentage / 100) * barHeight, 4); // Minimum 4px height
                    
                    // Ensure the bar has the proper color styling
                    if (color) {
                        // Add a subtle glow effect based on the color
                        bar.style.boxShadow = `0 2px 8px rgba(0,0,0,0.2), 0 0 20px ${color}40`;
                    }
                    
                    // Animate with delay for staggered effect
                    setTimeout(() => {
                        bar.style.height = fillHeight + 'px';
                        console.log(`Animated bar ${index + 1}: ${relativePercentage}% relative (${count} requests) with color ${color}, final height: ${fillHeight}px`);
                    }, index * 200); // 200ms delay between each bar
                });
            }
            
            // Animate when the Reports section comes into view
            const reportsSection = document.getElementById('reportsUI');
            if (reportsSection) {
                console.log('Reports section found, setting up intersection observer...');
                // Use Intersection Observer for better performance
                const observer = new IntersectionObserver((entries) => {
                    entries.forEach(entry => {
                        if (entry.isIntersecting) {
                            console.log('Reports section is visible, starting progress bar animation...');
                            // Small delay to ensure DOM is ready
                            setTimeout(animateProgressBars, 100);
                            observer.unobserve(entry.target); // Only animate once
                        }
                    });
                }, { threshold: 0.3 });
                
                observer.observe(reportsSection);
            } else {
                // Fallback: animate immediately if section not found
                console.log('Reports section not found, animating immediately...');
                setTimeout(animateProgressBars, 500); // Small delay to ensure DOM is ready
            }
        });
    </script>
</body>
    <!-- Verify Modal Placeholder -->
    <div id="verifyModal" class="import-modal" style="display:none;"></div>
    <script src="/js/registrar-verify-modal.js"></script>
</html>
</html>