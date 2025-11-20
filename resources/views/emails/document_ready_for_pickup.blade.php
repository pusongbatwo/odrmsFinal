<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document Ready for Pickup</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            background-color: #f4f4f4;
        }
        .email-container {
            background: white;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            overflow: hidden;
        }
        .header {
            background: linear-gradient(135deg, #8B0000 0%, #A52A2A 100%);
            color: white;
            padding: 30px;
            text-align: center;
        }
        .header h1 {
            margin: 0;
            font-size: 24px;
            font-weight: 600;
        }
        .content {
            padding: 30px;
        }
        .greeting {
            font-size: 18px;
            margin-bottom: 20px;
            color: #8B0000;
        }
        .message {
            font-size: 16px;
            margin-bottom: 25px;
            line-height: 1.8;
        }
        .request-details {
            background: #f8f9fa;
            border-left: 4px solid #4CAF50;
            padding: 20px;
            margin: 25px 0;
            border-radius: 0 8px 8px 0;
        }
        .detail-row {
            display: flex;
            margin-bottom: 10px;
            align-items: center;
        }
        .detail-label {
            font-weight: 600;
            color: #555;
            min-width: 120px;
            margin-right: 10px;
        }
        .detail-value {
            color: #333;
            flex: 1;
        }
        .urgent-notice {
            background: #fff3cd;
            border: 1px solid #ffeaa7;
            border-radius: 8px;
            padding: 15px;
            margin: 20px 0;
            text-align: center;
        }
        .urgent-notice h3 {
            color: #f57c00;
            margin: 0 0 10px 0;
        }
        .pickup-info {
            background: #e8f5e8;
            border: 1px solid #c8e6c9;
            border-radius: 8px;
            padding: 20px;
            margin: 25px 0;
        }
        .pickup-info h3 {
            color: #2e7d32;
            margin: 0 0 15px 0;
        }
        .pickup-info ul {
            margin: 0;
            padding-left: 20px;
        }
        .pickup-info li {
            margin-bottom: 8px;
        }
        .footer {
            background: #f8f9fa;
            padding: 20px;
            text-align: center;
            color: #666;
            font-size: 14px;
        }
        .footer p {
            margin: 5px 0;
        }
        .highlight {
            color: #8B0000;
            font-weight: 600;
        }
        .days-remaining {
            background: #4CAF50;
            color: white;
            padding: 8px 16px;
            border-radius: 20px;
            font-weight: 600;
            display: inline-block;
            margin: 10px 0;
        }
        .days-remaining.urgent {
            background: #f57c00;
        }
        .days-remaining.critical {
            background: #f44336;
            animation: pulse 2s infinite;
        }
        @keyframes pulse {
            0%, 100% { opacity: 1; }
            50% { opacity: 0.7; }
        }
    </style>
</head>
<body>
    <div class="email-container">
        <div class="header">
            <h1>📄 Document Ready for Pickup</h1>
        </div>
        
        <div class="content">
            <div class="greeting">
                Hello {{ $documentRequest->first_name }} {{ $documentRequest->last_name }},
            </div>
            
            <div class="message">
                Great news! Your document request has been completed and is now ready for pickup. 
                Please visit the Registrar's Office to collect your documents.
            </div>
            
            <div class="request-details">
                <h3 style="margin-top: 0; color: #4CAF50;">📋 Request Details</h3>
                
                <div class="detail-row">
                    <span class="detail-label">Request ID:</span>
                    <span class="detail-value highlight">#{{ $documentRequest->id }}</span>
                </div>
                
                <div class="detail-row">
                    <span class="detail-label">Student Name:</span>
                    <span class="detail-value">{{ $documentRequest->first_name }} {{ $documentRequest->last_name }}</span>
                </div>
                
                <div class="detail-row">
                    <span class="detail-label">Course:</span>
                    <span class="detail-value">{{ $documentRequest->course }}</span>
                </div>
                
                <div class="detail-row">
                    <span class="detail-label">Request Date:</span>
                    <span class="detail-value">{{ $documentRequest->created_at ? $documentRequest->created_at->format('F j, Y') : 'N/A' }}</span>
                </div>
                
                <div class="detail-row">
                    <span class="detail-label">Status:</span>
                    <span class="detail-value" style="color: #4CAF50; font-weight: 600;">✅ Completed</span>
                </div>
                
                @if($documentRequest->requestedDocuments && count($documentRequest->requestedDocuments) > 0)
                <div class="detail-row">
                    <span class="detail-label">Documents:</span>
                    <span class="detail-value">
                        @foreach($documentRequest->requestedDocuments as $doc)
                            • {{ $doc->document_type }} ({{ $doc->quantity }})
                            @if(!$loop->last)<br>@endif
                        @endforeach
                    </span>
                </div>
                @endif
            </div>
            
            @if($daysUntilRelease <= 2)
            <div class="urgent-notice">
                <h3>⚠️ Urgent Notice</h3>
                <p>Your documents are ready for immediate pickup! Please collect them as soon as possible.</p>
            </div>
            @endif
            
            <div class="pickup-info">
                <h3>📍 Pickup Information</h3>
                <ul>
                    <li><strong>Location:</strong> Registrar's Office</li>
                    <li><strong>Office Hours:</strong> Monday to Friday, 8:00 AM - 5:00 PM</li>
                    <li><strong>Required:</strong> Valid ID and this email confirmation</li>
                    <li><strong>Contact:</strong> For questions, please contact the Registrar's Office</li>
                </ul>
            </div>
            
            <div style="text-align: center; margin: 30px 0;">
                <span class="days-remaining {{ $daysUntilRelease <= 2 ? ($daysUntilRelease <= 1 ? 'critical' : 'urgent') : '' }}">
                    @if($daysUntilRelease > 0)
                        {{ $daysUntilRelease }} {{ $daysUntilRelease == 1 ? 'day' : 'days' }} remaining for pickup
                    @else
                        Ready for immediate pickup!
                    @endif
                </span>
            </div>
            
            <div class="message">
                <strong>Important:</strong> Please bring a valid ID when picking up your documents. 
                If you have any questions or need to reschedule your pickup, please contact the Registrar's Office immediately.
            </div>
        </div>
        
        <div class="footer">
            <p><strong>iRequest Document Management System</strong></p>
            <p>This is an automated notification. Please do not reply to this email.</p>
            <p>For assistance, contact the Registrar's Office.</p>
        </div>
    </div>
</body>
</html>
