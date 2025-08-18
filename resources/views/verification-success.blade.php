<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Email Verification Successful - iRequest</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --dark-red: #8B0000;
            --gold: #FFD700;
            --white: #FFFFFF;
            --light-gray: #F5F5F5;
            --dark-gray: #333333;
            --success-green: #28a745;
            --transition: all 0.3s ease;
        }
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        
        body {
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }
        
        .verification-container {
            background: var(--white);
            border-radius: 20px;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
            padding: 40px;
            max-width: 600px;
            width: 100%;
            text-align: center;
            position: relative;
            overflow: hidden;
        }
        
        .verification-container::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 5px;
            background: linear-gradient(90deg, var(--dark-red), var(--gold));
        }
        
        .success-icon {
            width: 80px;
            height: 80px;
            background: var(--success-green);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 30px;
            animation: bounceIn 0.8s ease;
        }
        
        .success-icon i {
            font-size: 40px;
            color: var(--white);
        }
        
        .verification-title {
            color: var(--dark-red);
            font-size: 2.5rem;
            margin-bottom: 20px;
            font-weight: 700;
        }
        
        .verification-message {
            color: var(--dark-gray);
            font-size: 1.2rem;
            margin-bottom: 30px;
            line-height: 1.6;
        }
        
        .reference-section {
            background: var(--light-gray);
            border-radius: 15px;
            padding: 25px;
            margin: 30px 0;
            border-left: 5px solid var(--success-green);
        }
        
        .reference-label {
            color: var(--dark-gray);
            font-size: 1rem;
            margin-bottom: 10px;
            font-weight: 600;
        }
        
        .reference-number {
            background: var(--white);
            border: 2px solid var(--success-green);
            border-radius: 10px;
            padding: 15px;
            font-size: 1.3rem;
            font-weight: 700;
            color: var(--dark-red);
            letter-spacing: 2px;
            font-family: 'Courier New', monospace;
        }
        
        .request-details {
            background: var(--light-gray);
            border-radius: 15px;
            padding: 25px;
            margin: 30px 0;
            text-align: left;
        }
        
        .request-details h3 {
            color: var(--dark-red);
            margin-bottom: 20px;
            text-align: center;
            font-size: 1.3rem;
        }
        
        .detail-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 15px;
            padding-bottom: 10px;
            border-bottom: 1px solid #ddd;
        }
        
        .detail-row:last-child {
            border-bottom: none;
            margin-bottom: 0;
        }
        
        .detail-label {
            font-weight: 600;
            color: var(--dark-gray);
        }
        
        .detail-value {
            color: var(--dark-gray);
            text-align: right;
            max-width: 60%;
        }
        
        .action-buttons {
            display: flex;
            gap: 20px;
            justify-content: center;
            margin-top: 40px;
            flex-wrap: wrap;
        }
        
        .btn {
            padding: 15px 30px;
            border-radius: 30px;
            text-decoration: none;
            font-weight: 600;
            font-size: 1.1rem;
            transition: var(--transition);
            display: inline-flex;
            align-items: center;
            gap: 10px;
            border: none;
            cursor: pointer;
        }
        
        .btn-primary {
            background: var(--dark-red);
            color: var(--white);
        }
        
        .btn-primary:hover {
            background: #6d0000;
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(139, 0, 0, 0.3);
        }
        
        .btn-secondary {
            background: var(--gold);
            color: var(--dark-red);
        }
        
        .btn-secondary:hover {
            background: #e6c200;
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(255, 215, 0, 0.3);
        }
        
        .status-badge {
            background: var(--success-green);
            color: var(--white);
            padding: 8px 20px;
            border-radius: 20px;
            font-size: 0.9rem;
            font-weight: 600;
            display: inline-block;
            margin-bottom: 20px;
        }
        
        @keyframes bounceIn {
            0% {
                transform: scale(0.3);
                opacity: 0;
            }
            50% {
                transform: scale(1.05);
            }
            70% {
                transform: scale(0.9);
            }
            100% {
                transform: scale(1);
                opacity: 1;
            }
        }
        
        @media (max-width: 768px) {
            .verification-container {
                padding: 30px 20px;
            }
            
            .verification-title {
                font-size: 2rem;
            }
            
            .action-buttons {
                flex-direction: column;
                align-items: center;
            }
            
            .btn {
                width: 100%;
                max-width: 300px;
                justify-content: center;
            }
        }
    </style>
</head>
<body>
    <div class="verification-container">
        <div class="success-icon">
            <i class="fas fa-check"></i>
        </div>
        
        <h1 class="verification-title">Email Verified Successfully!</h1>
        
        <div class="status-badge">
            <i class="fas fa-clock"></i> Request Status: Pending Registrar Approval
        </div>
        
        <p class="verification-message">
            Your email address has been verified successfully. Your document request has been submitted and is now awaiting approval from the Registrar.
        </p>
        
        <div class="reference-section" style="background: #fff3cd; border-left: 5px solid #ffc107;">
            <div class="reference-label" style="color: #856404;">Important Notice:</div>
            <div class="reference-number" style="border-color: #ffc107; color: #856404; font-size: 1rem;">
                Reference number will be generated after Registrar approval
            </div>
        </div>
        
        <div class="request-details">
            <h3><i class="fas fa-file-alt"></i> Request Summary</h3>
            
            <div class="detail-row">
                <span class="detail-label">Name:</span>
                <span class="detail-value">{{ $request->first_name }} {{ $request->middle_name ?? '' }} {{ $request->last_name }}</span>
            </div>
            
            <div class="detail-row">
                <span class="detail-label">Course:</span>
                <span class="detail-value">{{ $request->course }}</span>
            </div>
            
            <div class="detail-row">
                <span class="detail-label">Documents Requested:</span>
                <span class="detail-value">
                    @foreach($request->requestedDocuments as $doc)
                        {{ $doc->document_type }} ({{ $doc->quantity }}){{ !$loop->last ? ', ' : '' }}
                    @endforeach
                </span>
            </div>
            
            <div class="detail-row">
                <span class="detail-label">Request Date:</span>
                <span class="detail-value">{{ \Carbon\Carbon::parse($request->request_date)->format('F d, Y \a\t g:i A') }}</span>
            </div>
            
            <div class="detail-row">
                <span class="detail-label">Status:</span>
                <span class="detail-value">Pending Registrar Approval</span>
            </div>
        </div>
        
        <div class="action-buttons">
            <a href="{{ url('/track') }}" class="btn btn-primary">
                <i class="fas fa-search"></i> Track Your Request
            </a>
            <a href="{{ url('/') }}" class="btn btn-secondary">
                <i class="fas fa-home"></i> Back to Home
            </a>
        </div>
        
        <div style="margin-top: 30px; padding: 20px; background: #fff3cd; border-radius: 10px; border-left: 4px solid #ffc107;">
            <p style="color: #856404; margin: 0; font-size: 0.95rem;">
                <i class="fas fa-info-circle"></i> 
                <strong>Next Steps:</strong> Your request will be reviewed by the Registrar. You'll receive an email notification once it's approved or rejected. If approved, you'll get a reference number for tracking.
            </p>
        </div>
    </div>
</body>
</html>
