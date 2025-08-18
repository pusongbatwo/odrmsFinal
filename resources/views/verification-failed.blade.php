<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verification Failed - iRequest</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --dark-red: #8B0000;
            --gold: #FFD700;
            --white: #FFFFFF;
            --light-gray: #F5F5F5;
            --dark-gray: #333333;
            --error-red: #dc3545;
            --warning-orange: #fd7e14;
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
            background: linear-gradient(90deg, var(--error-red), var(--warning-orange));
        }
        
        .error-icon {
            width: 80px;
            height: 80px;
            background: var(--error-red);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 30px;
            animation: shake 0.8s ease;
        }
        
        .error-icon i {
            font-size: 40px;
            color: var(--white);
        }
        
        .verification-title {
            color: var(--error-red);
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
        
        .error-details {
            background: #f8d7da;
            border: 1px solid #f5c6cb;
            border-radius: 15px;
            padding: 25px;
            margin: 30px 0;
            text-align: left;
        }
        
        .error-details h3 {
            color: var(--error-red);
            margin-bottom: 15px;
            text-align: center;
            font-size: 1.3rem;
        }
        
        .error-message {
            color: #721c24;
            font-size: 1.1rem;
            line-height: 1.6;
            text-align: center;
        }
        
        .possible-reasons {
            background: #fff3cd;
            border: 1px solid #ffeaa7;
            border-radius: 15px;
            padding: 25px;
            margin: 30px 0;
            text-align: left;
        }
        
        .possible-reasons h3 {
            color: var(--warning-orange);
            margin-bottom: 15px;
            text-align: center;
            font-size: 1.3rem;
        }
        
        .reason-list {
            list-style: none;
            padding: 0;
        }
        
        .reason-list li {
            color: #856404;
            margin-bottom: 10px;
            padding-left: 25px;
            position: relative;
        }
        
        .reason-list li::before {
            content: '•';
            color: var(--warning-orange);
            font-weight: bold;
            position: absolute;
            left: 0;
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
        
        .help-section {
            background: #e3f2fd;
            border: 1px solid #bbdefb;
            border-radius: 15px;
            padding: 25px;
            margin: 30px 0;
        }
        
        .help-section h3 {
            color: #1976d2;
            margin-bottom: 15px;
            text-align: center;
            font-size: 1.3rem;
        }
        
        .help-text {
            color: #1565c0;
            font-size: 1rem;
            line-height: 1.6;
            text-align: center;
        }
        
        @keyframes shake {
            0%, 100% { transform: translateX(0); }
            10%, 30%, 50%, 70%, 90% { transform: translateX(-5px); }
            20%, 40%, 60%, 80% { transform: translateX(5px); }
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
        <div class="error-icon">
            <i class="fas fa-exclamation-triangle"></i>
        </div>
        
        <h1 class="verification-title">Verification Failed</h1>
        
        <p class="verification-message">
            We're sorry, but we couldn't verify your document request. This could be due to several reasons.
        </p>
        
        <div class="error-details">
            <h3><i class="fas fa-exclamation-circle"></i> Error Details</h3>
            <p class="error-message">{{ $message }}</p>
        </div>
        
        <div class="possible-reasons">
            <h3><i class="fas fa-question-circle"></i> Possible Reasons</h3>
            <ul class="reason-list">
                <li>The verification link has expired (links are valid for 24 hours)</li>
                <li>The verification link was already used</li>
                <li>The verification link is invalid or corrupted</li>
                <li>There was a system error during verification</li>
            </ul>
        </div>
        
        <div class="help-section">
            <h3><i class="fas fa-lightbulb"></i> What You Can Do</h3>
            <p class="help-text">
                Don't worry! You can submit a new document request. The system will send you a fresh verification email with a new link that will be valid for 24 hours.
            </p>
        </div>
        
        <div class="action-buttons">
            <a href="{{ url('/') }}" class="btn btn-primary">
                <i class="fas fa-plus"></i> Submit New Request
            </a>
            <a href="{{ url('/track') }}" class="btn btn-secondary">
                <i class="fas fa-search"></i> Track Existing Request
            </a>
        </div>
        
        <div style="margin-top: 30px; padding: 20px; background: #f8f9fa; border-radius: 10px; border-left: 4px solid var(--dark-red);">
            <p style="color: var(--dark-gray); margin: 0; font-size: 0.95rem;">
                <i class="fas fa-info-circle"></i> 
                <strong>Need Help?</strong> If you continue to experience issues, please contact our support team or visit our help center for assistance.
            </p>
        </div>
    </div>
</body>
</html>
