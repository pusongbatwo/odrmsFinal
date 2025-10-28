<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Document Request Dashboard</title>
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --dark-red: #8B0000;
            --gold: #D4AF37;
            --light-gold: #F5E7C1;
            --white: #FFFFFF;
            --light-gray: #F5F5F5;
            --dark-gray: #333333;
            --medium-gray: #666666;
            --card-shadow: 0 8px 24px rgba(139, 0, 0, 0.08);
            --transition: all 0.3s ease;
        }
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        
        body {
            background-color: #f9f9f9;
            color: var(--dark-gray);
            line-height: 1.6;
            min-height: 100vh;
            margin: 0;
            padding: 20px;
            display: flex;
            justify-content: center;
            align-items: flex-start;
        }
        
        .dashboard-container {
            width: 100%;
            max-width: 1200px;
            display: flex;
            flex-direction: column;
            align-items: center;
        }
        
        .dashboard-header {
            background: linear-gradient(135deg, var(--dark-red), #6B0000);
            color: var(--white);
            padding: 25px;
            border-radius: 10px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            margin-bottom: 20px;
            width: 100%;
        }
        
        .dashboard-header h2 {
            font-size: 24px;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        
        .reference-badge {
            background-color: var(--gold);
            color: var(--dark-gray);
            padding: 8px 15px;
            border-radius: 20px;
            font-weight: 600;
            font-size: 14px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }
        
        .header-right {
            display: flex;
            align-items: center;
            gap: 15px;
        }
        
        .logout-button {
            background-color: #d9534f;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 7px;
            font-size: 15px;
            cursor: pointer;
            font-weight: 600;
            box-shadow: 0 2px 8px rgba(217,83,79,0.10);
            transition: var(--transition);
        }
        
        .logout-button:hover {
            background-color: #c9302c;
            box-shadow: 0 6px 18px rgba(217,83,79,0.18);
            transform: translateY(-2px);
        }
        
        /* Main content layout */
        .dashboard-content {
            display: flex;
            gap: 24px;
            width: 100%;
        }
        
        /* Status Card Styles */
        .status-card {
            background-color: var(--white);
            border-radius: 14px;
            box-shadow: var(--card-shadow);
            padding: 28px;
            border-left: 6px solid var(--gold);
            transition: var(--transition);
            flex: 1;
            max-width: 400px;
        }
        
        .status-card:hover {
            box-shadow: 0 12px 32px rgba(139,0,0,0.10);
            transform: translateY(-2px);
        }
        
        .status-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 25px;
        }
        
        .status-card h3 {
            color: var(--dark-red);
            font-size: 20px;
            display: flex;
            align-items: center;
            gap: 10px;
            font-weight: 600;
        }
        
        .status-badge {
            padding: 8px 15px;
            border-radius: 20px;
            font-weight: 600;
            font-size: 14px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        
        .status-pending {
            background-color: #FFF3CD;
            color: #856404;
        }
        
        .status-processing {
            background-color: #CCE5FF;
            color: #004085;
        }
        
        .status-ready-for-payment {
            background-color: #FFF3CD;
            color: #856404;
        }
        
        .status-ready-for-pickup {
            background-color: #D4EDDA;
            color: #155724;
        }
        
        .status-completed {
            background-color: #D4EDDA;
            color: #155724;
        }
        
        .progress-steps {
            display: flex;
            justify-content: space-between;
            position: relative;
            margin-top: 30px;
        }
        
        .progress-steps::before {
            content: '';
            position: absolute;
            top: 20px;
            left: 0;
            right: 0;
            height: 4px;
            background-color: #E0E0E0;
            z-index: 1;
        }
        
        .step {
            display: flex;
            flex-direction: column;
            align-items: center;
            position: relative;
            z-index: 2;
            flex: 1;
        }
        
        .step-icon {
            width: 44px;
            height: 44px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 10px;
            background-color: #E0E0E0;
            color: var(--medium-gray);
            font-size: 18px;
            z-index: 2;
            position: relative;
        }
        
        .step-label {
            font-size: 14px;
            font-weight: 500;
            color: var(--medium-gray);
            text-align: center;
        }
        
        .step.completed .step-icon {
            background-color: var(--gold);
            color: var(--dark-red);
            box-shadow: 0 4px 8px rgba(212, 175, 55, 0.3);
        }
        
        .step.completed::before {
            content: '';
            position: absolute;
            top: 20px;
            left: 0;
            width: 100%;
            height: 4px;
            background-color: var(--gold);
            z-index: 1;
        }
        
        .step.current .step-icon {
            background-color: var(--dark-red);
            color: var(--white);
            box-shadow: 0 4px 12px rgba(139, 0, 0, 0.3);
        }
        
        .step.halfway .step-icon {
            background: linear-gradient(90deg, var(--gold) 50%, var(--dark-red) 50%);
            color: var(--white);
            box-shadow: 0 4px 12px rgba(139, 0, 0, 0.3);
        }
        
        .step:first-child::before {
            display: none;
        }
        
        /* Tab Container */
        .tab-container {
            background: var(--white);
            border-radius: 14px;
            box-shadow: var(--card-shadow);
            overflow: hidden;
            flex: 2;
            min-width: 0;
        }
        
        .tab-header {
            display: flex;
            background: var(--light-gray);
            border-bottom: 1px solid #e0e0e0;
        }
        
        .tab-button {
            padding: 16px 8px;
            flex: 1;
            background: none;
            border: none;
            cursor: pointer;
            font-weight: 600;
            color: var(--medium-gray);
            transition: var(--transition);
            position: relative;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            min-width: 0;
            flex-direction: column;
            text-align: center;
        }
        
        .tab-content-wrapper {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 2px;
        }
        
        .tab-title {
            font-size: 14px;
            font-weight: 600;
        }
        
        .tab-subtitle {
            font-size: 11px;
            font-weight: 500;
            color: var(--dark-gray);
            opacity: 0.9;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            max-width: 120px;
            line-height: 1.2;
        }
        
        .tab-button:hover {
            color: var(--dark-red);
            background: rgba(139, 0, 0, 0.05);
        }
        
        .tab-button:hover .tab-subtitle {
            color: var(--dark-red);
            opacity: 1;
        }
        
        .tab-button.active {
            color: var(--dark-red);
            background: var(--white);
        }
        
        .tab-button.active .tab-subtitle {
            color: var(--dark-red);
            opacity: 1;
        }
        
        .tab-button.active .tab-title {
            color: var(--dark-red);
        }
        
        .tab-button.active::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            width: 100%;
            height: 3px;
            background: var(--dark-red);
        }
        
        .tab-content {
            padding: 0;
        }
        
        .tab-pane {
            display: none;
            padding: 28px;
            animation: fadeIn 0.3s ease;
        }
        
        .tab-pane.active {
            display: block;
        }
        
        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }
        
        /* Card Styles for Tab Content */
        .tab-card {
            margin-bottom: 24px;
            padding-bottom: 24px;
            border-bottom: 1px solid #eee;
        }
        
        .tab-card:last-child {
            margin-bottom: 0;
            padding-bottom: 0;
            border-bottom: none;
        }
        
        .tab-card h3 {
            color: var(--dark-red);
            font-size: 18px;
            margin-bottom: 16px;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        
        .payment-details, .details-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 16px;
        }
        
        .payment-row, .detail-item {
            display: flex;
            justify-content: space-between;
            padding: 10px 0;
            border-bottom: 1px solid #f0f0f0;
        }
        
        .payment-label, .detail-label {
            font-weight: 500;
            color: var(--medium-gray);
        }
        
        .payment-value, .detail-value {
            font-weight: 600;
            text-align: right;
            max-width: 60%;
        }
        
        .payment-pending {
            color: #856404;
            background-color: #FFF3CD;
            padding: 3px 10px;
            border-radius: 12px;
            font-size: 14px;
        }
        
        .payment-paid {
            color: #155724;
            background-color: #D4EDDA;
            padding: 3px 10px;
            border-radius: 12px;
            font-size: 14px;
        }
        
        .payment-actions {
            grid-column: span 2;
            display: flex;
            gap: 15px;
            margin-top: 15px;
        }
        
        .pay-button {
            flex: 1;
            padding: 14px;
            border: none;
            border-radius: 10px;
            font-weight: 600;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            transition: var(--transition);
            box-shadow: 0 2px 8px rgba(0,0,0,0.07);
        }
        
        .online-pay {
            background-color: var(--dark-red);
            color: var(--white);
        }
        
        .walkin-pay {
            background-color: var(--gold);
            color: var(--dark-gray);
        }
        
        .pay-button:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 16px rgba(139,0,0,0.12);
        }
        
        /* Chat Widget */
        #chat-widget {
            position: fixed;
            bottom: 30px;
            right: 30px;
            z-index: 9999;
            width: 350px;
            max-width: 95vw;
        }
        
        #chat-header {
            background: linear-gradient(135deg, #8B0000, #6B0000);
            color: #fff;
            padding: 12px 18px;
            border-radius: 15px 15px 0 0;
            display: flex;
            align-items: center;
            gap: 10px;
            cursor: pointer;
            box-shadow: 0 2px 8px rgba(0,0,0,0.12);
        }
        
        #chat-body {
            background: #fff;
            border-radius: 0 0 15px 15px;
            box-shadow: 0 8px 24px rgba(0,0,0,0.10);
            padding: 0 0 10px 0;
            display: none;
            flex-direction: column;
            height: 400px;
            max-height: 60vh;
        }
        
        #chat-messages {
            flex: 1;
            overflow-y: auto;
            padding: 18px 12px 0 12px;
            display: flex;
            flex-direction: column;
            gap: 10px;
        }
        
        #chat-form {
            display: flex;
            gap: 8px;
            padding: 10px 12px 0 12px;
        }
        
        #chat-input {
            flex: 1;
            padding: 10px 14px;
            border-radius: 20px;
            border: 1px solid #eee;
            background: #f9f9f9;
            outline: none;
        }
        
        /* Loading indicator */
        .loading {
            display: inline-block;
            width: 20px;
            height: 20px;
            border: 3px solid rgba(139, 0, 0, 0.3);
            border-radius: 50%;
            border-top-color: var(--dark-red);
            animation: spin 1s ease-in-out infinite;
        }
        
        @keyframes spin {
            to { transform: rotate(360deg); }
        }
        
        /* Responsive Design */
        @media (max-width: 968px) {
            .dashboard-content {
                flex-direction: column;
            }
            
            .status-card {
                max-width: 100%;
            }
        }
        
        @media (max-width: 768px) {
            .payment-details, .details-grid {
                grid-template-columns: 1fr;
            }
            
            .payment-actions {
                grid-column: span 1;
                flex-direction: column;
            }
            
            .dashboard-header {
                flex-direction: column;
                align-items: flex-start;
                gap: 15px;
            }
            
            .dashboard-header > div > div {
                flex-direction: column;
                gap: 8px;
                align-items: flex-start;
            }
            
            .header-right {
                width: 100%;
                justify-content: space-between;
            }
            
            .progress-steps {
                flex-wrap: wrap;
                gap: 15px;
            }
            
            .step {
                flex: none;
                width: calc(50% - 15px);
            }
            
            .tab-header {
                flex-wrap: wrap;
            }
            
            .tab-button {
                flex: 1;
                min-width: 120px;
                justify-content: center;
                padding: 12px 8px;
            }
            
            .tab-subtitle {
                font-size: 11px;
                max-width: 100px;
            }
        }
    </style>
</head>
<body>
    <div class="dashboard-container">
        <div class="dashboard-header">
            <h2><i class="fas fa-file-alt"></i> Document Request Dashboard</h2>
            <div class="header-right">
                <div class="reference-badge" id="reference-number">
                    Reference: {{ $document->reference_number }}
                </div>
                <form method="POST" action="{{ route('logout') }}" style="margin:0;">
                    @csrf
                    <button type="submit" class="logout-button">
                        <i class="fas fa-sign-out-alt"></i> Logout
                    </button>
                </form>
            </div>
        </div>

        <div class="dashboard-content">
            <!-- Status Card (remains in its position) -->
            <div class="status-card">
                <div class="status-header">
                    <h3><i class="fas fa-tasks"></i> Request Status</h3>
                    <div class="status-badge" id="status-badge">
                        {{ $document->status }}
                    </div>
                </div>
                
                <div class="status-progress">
                    <div class="progress-steps">
                        @php
                        $steps = [
                            ['icon' => 'far fa-clock', 'label' => 'Submitted', 'status' => 'Pending'],
                            ['icon' => 'fas fa-cog', 'label' => 'Verification', 'status' => 'Processing'],
                            ['icon' => 'fas fa-money-bill-wave', 'label' => 'Payment', 'status' => 'Approved'],
                            ['icon' => 'fas fa-box-open', 'label' => 'Processing', 'status' => 'Ready for Pickup'],
                            ['icon' => 'fas fa-check-circle', 'label' => 'Completed', 'status' => 'Completed'],
                        ];
                        $statusOrder = array_column($steps, 'status');
                        $currentStep = array_search($document->status, $statusOrder);
                        @endphp
                        @foreach($steps as $i => $step)
                            <div class="step @if($i < $currentStep) completed @elseif($i == $currentStep) current @endif">
                                <div class="step-icon">
                                    <i class="{{ $step['icon'] }}"></i>
                                </div>
                                <div class="step-label">{{ $step['label'] }}</div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- Tab Container for other information -->
            <div class="tab-container">
                <div class="tab-header">
                    <button class="tab-button active" data-tab="requester">
                        <i class="fas fa-user"></i> 
                        <div class="tab-content-wrapper">
                            <div class="tab-title">Requester Info</div>
                            <div class="tab-subtitle">{{ $document->first_name }} {{ $document->last_name }}</div>
                        </div>
                    </button>
                    <button class="tab-button" data-tab="details">
                        <i class="fas fa-info-circle"></i> 
                        <div class="tab-content-wrapper">
                            <div class="tab-title">Details</div>
                            <div class="tab-subtitle">{{ $document->document_type }}</div>
                        </div>
                    </button>
                    <button class="tab-button" data-tab="payment">
                        <i class="fas fa-credit-card"></i> 
                        <div class="tab-content-wrapper">
                            <div class="tab-title">Payment</div>
                            <div class="tab-subtitle">₱{{ number_format($document->amount ?? 0, 2) }}</div>
                        </div>
                    </button>
                </div>
                
                <div class="tab-content">
                    <!-- Requester Tab -->
                    <div class="tab-pane active" id="requester-tab">
                        <div class="tab-card">
                            <h3><i class="fas fa-user"></i> Requester Information</h3>
                            <div class="details-grid" id="requester-info">
                                <div class="detail-item">
                                    <span class="detail-label">Student ID:</span>
                                    <span class="detail-value"><span class="loading"></span></span>
                                </div>
                                <div class="detail-item">
                                    <span class="detail-label">Full Name:</span>
                                    <span class="detail-value"><span class="loading"></span></span>
                                </div>
                                <div class="detail-item">
                                    <span class="detail-label">Course:</span>
                                    <span class="detail-value"><span class="loading"></span></span>
                                </div>
                                <div class="detail-item">
                                    <span class="detail-label">Email:</span>
                                    <span class="detail-value"><span class="loading"></span></span>
                                </div>
                                <div class="detail-item">
                                    <span class="detail-label">Phone:</span>
                                    <span class="detail-value"><span class="loading"></span></span>
                                </div>
                                <div class="detail-item">
                                    <span class="detail-label">Address:</span>
                                    <span class="detail-value"><span class="loading"></span></span>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Details Tab -->
                    <div class="tab-pane" id="details-tab">
                        <div class="tab-card">
                            <h3><i class="fas fa-info-circle"></i> Request Details</h3>
                            <div class="details-grid" id="request-details">
                                <div class="detail-item">
                                    <span class="detail-label">Document Type:</span>
                                    <span class="detail-value"><span class="loading"></span></span>
                                </div>
                                <div class="detail-item">
                                    <span class="detail-label">Request Date:</span>
                                    <span class="detail-value"><span class="loading"></span></span>
                                </div>
                                <div class="detail-item">
                                    <span class="detail-label">Purpose:</span>
                                    <span class="detail-value"><span class="loading"></span></span>
                                </div>
                                <div class="detail-item">
                                    <span class="detail-label">Special Instructions:</span>
                                    <span class="detail-value"><span class="loading"></span></span>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Payment Tab -->
                    <div class="tab-pane" id="payment-tab">
                        <div class="tab-card">
                            <h3><i class="fas fa-credit-card"></i> Payment Information</h3>
                            <div class="payment-details" id="payment-info">
                                <div class="payment-row">
                                    <span class="payment-label">Status:</span>
                                    <span class="payment-value"><span class="loading"></span></span>
                                </div>
                                <div class="payment-row">
                                    <span class="payment-label">Amount:</span>
                                    <span class="payment-value"><span class="loading"></span></span>
                                </div>
                                <div class="payment-row">
                                    <span class="payment-label">Method:</span>
                                    <span class="payment-value"><span class="loading"></span></span>
                                </div>
                                <div class="payment-actions" id="payment-actions">
                                    <!-- Payment buttons will be added here dynamically -->
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Live Chat Widget -->
    <div id="chat-widget">
        <div id="chat-header">
            <i class="fas fa-comments"></i>
            <span style="font-weight:600;flex:1;">Chat with Registrar</span>
            <i id="chat-toggle" class="fas fa-chevron-down"></i>
        </div>
        <div id="chat-body">
            <div id="chat-messages"></div>
            <form id="chat-form">
                <input id="chat-input" type="text" placeholder="Type your message..." autocomplete="off" />
                <button type="submit" style="background:#8B0000;color:#fff;border:none;border-radius:50%;width:40px;height:40px;display:flex;align-items:center;justify-content:center;cursor:pointer;transition:background 0.2s;">
                    <i class="fas fa-paper-plane"></i>
                </button>
            </form>
        </div>
    </div>

    <script>
        // Simulated database (in a real application, this would be server-side)
        const documentRequestsDB = {
            "{{ $document->reference_number }}": {
                reference_number: "{{ $document->reference_number }}",
                status: "{{ ucwords(str_replace('_',' ', (string)$document->status)) }}",
                student_id: "{{ $document->student_id }}",
                first_name: "{{ $document->first_name }}",
                middle_name: "{{ $document->middle_name }}",
                last_name: "{{ $document->last_name }}",
                course: "{{ $document->course }}",
                email: "{{ $document->email }}",
                mobile: "{{ $document->mobile_number }}",
                barangay: "{{ $document->barangay }}",
                city: "{{ $document->city }}",
                province: "{{ $document->province }}",
                document_type: "{{ $document->document_type }}",
                created_at: "{{ optional($document->created_at)->format('c') }}",
                purpose: "{{ $document->purpose }}",
                special_instructions: "{{ $document->special_instructions }}",
                payment_status: "{{ ucwords((string)$document->payment_status) }}",
                amount: {{ (float)($document->amount ?? $document->amount_paid ?? 0) }},
                payment_method: {{ json_encode($document->payment_method) }}
            },
            // demo entry remains optional
            "DR-2023-0872": {
                reference_number: "DR-2023-0872",
                status: "Approved",
                student_id: "2020-12345",
                first_name: "Maria",
                middle_name: "Santos",
                last_name: "Dela Cruz",
                course: "BS Computer Science",
                email: "maria.cruz@example.com",
                mobile: "+63 912 345 6789",
                barangay: "Barangay 123",
                city: "Manila",
                province: "Metro Manila",
                document_type: "Transcript of Records",
                created_at: "2023-10-15T10:30:00",
                purpose: "Graduate School Application",
                special_instructions: "Please include course descriptions",
                payment_status: "Pending",
                amount: 350.00,
                payment_method: null
            }
        };

        // Simulate API calls with delay
        function fetchDocumentData(documentId) {
            return new Promise((resolve) => {
                setTimeout(() => {
                    resolve(documentRequestsDB[documentId] || null);
                }, 1000); // Simulate network delay
            });
        }

        // Update payment status (simulating server interaction)
        function updatePaymentStatus(documentId, status, method = null) {
            return new Promise((resolve) => {
                setTimeout(() => {
                    if (documentRequestsDB[documentId]) {
                        documentRequestsDB[documentId].payment_status = status;
                        if (method) {
                            documentRequestsDB[documentId].payment_method = method;
                        }
                        resolve(true);
                    } else {
                        resolve(false);
                    }
                }, 800); // Simulate network delay
            });
        }

        // Main function to load and display document data
        async function loadDocumentData(documentId) {
            try {
                const documentData = await fetchDocumentData(documentId);
                
                console.log('Document data loaded:', documentData);
                
                if (!documentData) {
                    // If not found in simulated store, rely on Blade-rendered content and continue
                    console.log('No document data found, using Blade template data');
                    return;
                }

                // Update reference number
                document.getElementById('reference-number').innerHTML = `Reference: ${documentData.reference_number}`;
                
                // Update tab button content
                const requesterTab = document.querySelector('[data-tab="requester"] .tab-subtitle');
                const detailsTab = document.querySelector('[data-tab="details"] .tab-subtitle');
                const paymentTab = document.querySelector('[data-tab="payment"] .tab-subtitle');
                
                console.log('Updating tab content:', {
                    requesterTab: requesterTab,
                    detailsTab: detailsTab,
                    paymentTab: paymentTab
                });
                
                if (requesterTab) {
                    requesterTab.textContent = `${documentData.first_name} ${documentData.last_name}`;
                }
                if (detailsTab) {
                    detailsTab.textContent = documentData.document_type;
                }
                if (paymentTab) {
                    paymentTab.textContent = `₱${documentData.amount.toLocaleString('en-US', { 
                        minimumFractionDigits: 2, 
                        maximumFractionDigits: 2 
                    })}`;
                }
                
                // Update status badge
                const statusBadge = document.getElementById('status-badge');
                statusBadge.textContent = documentData.status;
                statusBadge.className = 'status-badge ';
                
                if (documentData.status === "Pending") {
                    statusBadge.classList.add('status-pending');
                } else if (documentData.status === "Processing") {
                    statusBadge.classList.add('status-processing');
                } else if (documentData.status === "Approved") {
                    statusBadge.classList.add('status-ready-for-payment');
                } else if (documentData.status === "Ready for Payment") {
                    statusBadge.classList.add('status-ready-for-payment');
                } else if (documentData.status === "Ready for Pickup") {
                    statusBadge.classList.add('status-ready-for-pickup');
                } else if (documentData.status === "Completed") {
                    statusBadge.classList.add('status-completed');
                }
                
                // Update progress steps based on status
                const steps = document.querySelectorAll('.step');
                
                // Reset all steps
                steps.forEach(step => {
                    step.classList.remove('completed', 'current', 'halfway');
                });
                
                // Set steps based on status
                if (documentData.status === "Pending") {
                    steps[0].classList.add('current');
                } else if (documentData.status === "Processing") {
                    steps[0].classList.add('completed');
                    steps[1].classList.add('current');
                } else if (documentData.status === "Approved") {
                    steps[0].classList.add('completed');
                    steps[1].classList.add('completed');
                    steps[2].classList.add('halfway'); // Halfway through payment step
                } else if (documentData.status === "Ready for Payment") {
                    steps[0].classList.add('completed');
                    steps[1].classList.add('completed');
                    steps[2].classList.add('current');
                } else if (documentData.status === "Ready for Pickup") {
                    steps[0].classList.add('completed');
                    steps[1].classList.add('completed');
                    steps[2].classList.add('completed');
                    steps[3].classList.add('current');
                } else if (documentData.status === "Completed") {
                    steps.forEach(step => {
                        step.classList.add('completed');
                    });
                }
                
                // Update requester information
                document.getElementById('requester-info').innerHTML = `
                    <div class="detail-item">
                        <span class="detail-label">Student ID:</span>
                        <span class="detail-value">{{ $document->student_id }}</span>
                    </div>
                    <div class="detail-item">
                        <span class="detail-label">Full Name:</span>
                        <span class="detail-value">{{ $document->first_name }} {{ $document->middle_name }} {{ $document->last_name }}</span>
                    </div>
                    <div class="detail-item">
                        <span class="detail-label">Course:</span>
                        <span class="detail-value">{{ $document->course }}</span>
                    </div>
                    <div class="detail-item">
                        <span class="detail-label">Email:</span>
                        <span class="detail-value">{{ $document->email }}</span>
                    </div>
                    <div class="detail-item">
                        <span class="detail-label">Phone:</span>
                        <span class="detail-value">{{ $document->mobile_number }}</span>
                    </div>
                    <div class="detail-item">
                        <span class="detail-label">Address:</span>
                        <span class="detail-value">{{ $document->barangay }}, {{ $document->city }}, {{ $document->province }}</span>
                    </div>
                `;
                
                // Update request details
                const requestDate = new Date(documentData.created_at);
                document.getElementById('request-details').innerHTML = `
                    <div class="detail-item">
                        <span class="detail-label">Document Type:</span>
                        <span class="detail-value">${documentData.document_type}</span>
                    </div>
                    <div class="detail-item">
                        <span class="detail-label">Request Date:</span>
                        <span class="detail-value">${requestDate.toLocaleDateString('en-US', { 
                            year: 'numeric', 
                            month: 'short', 
                            day: 'numeric',
                            hour: '2-digit',
                            minute: '2-digit'
                        })}</span>
                    </div>
                    <div class="detail-item">
                        <span class="detail-label">Purpose:</span>
                        <span class="detail-value">${documentData.purpose}</span>
                    </div>
                    <div class="detail-item">
                        <span class="detail-label">Special Instructions:</span>
                        <span class="detail-value">${documentData.special_instructions || 'None'}</span>
                    </div>
                `;
                
                // Update payment information
                const paymentStatusClass = documentData.payment_status === 'Paid' ? 'payment-paid' : 'payment-pending';
                document.getElementById('payment-info').innerHTML = `
                    <div class="payment-row">
                        <span class="payment-label">Status:</span>
                        <span class="payment-value ${paymentStatusClass}">${documentData.payment_status}</span>
                    </div>
                    <div class="payment-row">
                        <span class="payment-label">Total Amount:</span>
                        <span class="payment-value">₱${documentData.amount.toLocaleString('en-US', { 
                            minimumFractionDigits: 2, 
                            maximumFractionDigits: 2 
                        })}</span>
                    </div>
                    <div class="payment-row">
                        <span class="payment-label">Method:</span>
                        <span class="payment-value">${documentData.payment_method || 'Not selected'}</span>
                    </div>
                `;
                
                // Add payment actions if needed
                const paymentActions = document.getElementById('payment-actions');
                paymentActions.innerHTML = '';
                
                if (documentData.status === 'Ready for Payment' || documentData.status === 'Approved') {
                    paymentActions.innerHTML = `
                        <button class="pay-button online-pay" onclick="handleOnlinePayment('${documentData.reference_number}')">
                            <i class="fas fa-globe"></i> Pay Online
                        </button>
                        <button class="pay-button walkin-pay" onclick="handleWalkInPayment('${documentData.reference_number}')">
                            <i class="fas fa-store"></i> Pay In Person
                        </button>
                    `;
                }
                
            } catch (error) {
                console.error('Error loading document data:', error);
                // Removed JS error: Blade now handles all data rendering
            }
        }

        // Realtime polling to update status/payment from server
        async function pollStatus(reference) {
            try {
                const res = await fetch(`{{ url('/requester/status') }}/${reference}`, { headers: { 'X-Requested-With': 'XMLHttpRequest' } });
                if (!res.ok) return;
                const data = await res.json();
                if (!data.success) return;
                // Update status badge
                const statusBadge = document.getElementById('status-badge');
                statusBadge.textContent = data.status;
                statusBadge.className = 'status-badge ';
                if (data.status === 'Pending') {
                    statusBadge.classList.add('status-pending');
                } else if (data.status === 'Processing') {
                    statusBadge.classList.add('status-processing');
                } else if (data.status === 'Approved' || data.status === 'Ready for Payment') {
                    statusBadge.classList.add('status-ready-for-payment');
                } else if (data.status === 'Ready for Pickup') {
                    statusBadge.classList.add('status-ready-for-pickup');
                } else if (data.status === 'Completed') {
                    statusBadge.classList.add('status-completed');
                }
                // Update payment info
                const paymentInfo = document.getElementById('payment-info');
                if (paymentInfo) {
                    paymentInfo.querySelector('.payment-value.payment-pending')?.classList.remove('payment-pending', 'payment-paid');
                    const statusEl = paymentInfo.querySelector('.payment-row .payment-value');
                    if (statusEl) statusEl.textContent = data.payment_status || 'Unpaid';
                    const amountEl = paymentInfo.querySelectorAll('.payment-row .payment-value')[1];
                    if (amountEl) amountEl.textContent = '₱' + Number(data.amount || 0).toLocaleString(undefined, { minimumFractionDigits: 2, maximumFractionDigits: 2 });
                    const methodEl = paymentInfo.querySelectorAll('.payment-row .payment-value')[2];
                    if (methodEl) methodEl.textContent = data.payment_method || 'Not selected';
                }
            } catch (_) {}
        }

        // Payment functions
        async function handleOnlinePayment(documentId) {
            try {
                const success = await updatePaymentStatus(documentId, 'Processing', 'Online');
                if (success) {
                    alert('Redirecting to secure payment gateway...');
                    // In a real implementation, this would redirect to your payment processor
                    // window.location.href = '/payment/gateway';
                    
                    // Reload data to reflect changes
                    loadDocumentData(documentId);
                } else {
                    alert('Failed to process payment. Please try again.');
                }
            } catch (error) {
                console.error('Payment error:', error);
                alert('An error occurred during payment processing.');
            }
        }
        
        async function handleWalkInPayment(documentId) {
            try {
                const success = await updatePaymentStatus(documentId, 'Pending', 'In Person');
                if (success) {
                    alert('Please visit the registrar\'s office during business hours to complete your payment.\n\nOffice Hours: Mon-Fri, 8:00 AM - 5:00 PM');
                    // Reload data to reflect changes
                    loadDocumentData(documentId);
                } else {
                    alert('Failed to process payment request. Please try again.');
                }
            } catch (error) {
                console.error('Payment error:', error);
                alert('An error occurred during payment processing.');
            }
        }

        // Tab functionality
        function setupTabs() {
            const tabButtons = document.querySelectorAll('.tab-button');
            const tabPanes = document.querySelectorAll('.tab-pane');
            
            tabButtons.forEach(button => {
                button.addEventListener('click', () => {
                    const tabId = button.getAttribute('data-tab');
                    
                    // Update active button
                    tabButtons.forEach(btn => btn.classList.remove('active'));
                    button.classList.add('active');
                    
                    // Show active tab pane
                    tabPanes.forEach(pane => pane.classList.remove('active'));
                    document.getElementById(`${tabId}-tab`).classList.add('active');
                });
            });
        }

        // Chat Widget Logic
        function setupChatWidget() {
            const chatHeader = document.getElementById('chat-header');
            const chatBody = document.getElementById('chat-body');
            const chatToggle = document.getElementById('chat-toggle');
            let chatOpen = false;
            
            chatHeader.addEventListener('click', function() {
                chatOpen = !chatOpen;
                chatBody.style.display = chatOpen ? 'flex' : 'none';
                chatToggle.className = chatOpen ? 'fas fa-chevron-up' : 'fas fa-chevron-down';
            });

            // Chat send
            const chatForm = document.getElementById('chat-form');
            const chatInput = document.getElementById('chat-input');
            const chatMessages = document.getElementById('chat-messages');
            const reference = "{{ $document->reference_number }}";

            async function fetchMessages() {
                try {
                    const res = await fetch(`{{ route('chat.fetch') }}?reference_number=${encodeURIComponent(reference)}&mark_read=1`, { 
                        headers: { 'X-Requested-With':'XMLHttpRequest' },
                        credentials: 'same-origin'
                    });
                    if (!res.ok) return;
                    const data = await res.json();
                    if (!data.success) return;
                    chatMessages.innerHTML = data.messages.map(m => `
                        <div style="display:flex;${m.sender_type==='requester'?'justify-content:flex-end':'justify-content:flex-start'};">
                            <div style="max-width:75%;padding:8px 12px;border-radius:12px;margin:4px 0;${m.sender_type==='requester'?'background:#8B0000;color:#fff;':'background:#f3f4f6;color:#111827;'}">
                                ${m.message}
                                <div style="font-size:11px;opacity:.7;margin-top:4px;">${new Date(m.created_at).toLocaleString()}</div>
                            </div>
                        </div>
                    `).join('');
                    chatMessages.scrollTop = chatMessages.scrollHeight;
                } catch (_) {}
            }

            chatForm.addEventListener('submit', async function(e){
                e.preventDefault();
                const text = (chatInput.value || '').trim();
                if (!text) return;
                try {
                    // disable input/button while sending
                    const sendBtn = chatForm.querySelector('button');
                    sendBtn.disabled = true;
                    chatInput.disabled = true;

                    const res = await fetch(`{{ route('chat.send') }}`, {
                        method: 'POST',
                        credentials: 'same-origin',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                            'X-Requested-With': 'XMLHttpRequest',
                            'Accept': 'application/json'
                        },
                        body: JSON.stringify({ 
                            reference_number: reference, 
                            sender_type: 'requester', 
                            message: text,
                            sender_email: '{{ $document->email }}',
                            sender_mobile: '{{ $document->mobile_number }}'
                        })
                    });

                    let data;
                    try { data = await res.json(); } catch (err) { data = null }
                    if (!res.ok) {
                        console.error('Chat send failed', res.status, data);
                        alert((data && data.message) ? data.message : 'Failed to send message.');
                    } else if (data && data.success) {
                        chatInput.value = '';
                        fetchMessages();
                    } else {
                        console.error('Unexpected chat send response', data);
                        alert('Failed to send message.');
                    }
                    sendBtn.disabled = false;
                    chatInput.disabled = false;
                } catch (_) {}
            });

            // Poll for new messages every 6s
            setInterval(fetchMessages, 6000);
            // initial fetch
            fetchMessages();
        }

        // Initialize the dashboard
        document.addEventListener('DOMContentLoaded', function() {
            setupTabs();
            setupChatWidget();
            
            // Use server-provided reference number
            const documentId = "{{ $document->reference_number }}";
            
            // Load initial data
            loadDocumentData(documentId);
            // Poll server every 10 seconds for real-time status updates
            setInterval(() => {
                pollStatus(documentId);
            }, 10000);
        });
    </script>
</body>
</html>