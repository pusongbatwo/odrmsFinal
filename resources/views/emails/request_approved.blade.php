<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Document Request Approved</title>
</head>
<body style="background: #f6f6f6; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; margin: 0; padding: 0;">
    <div style="max-width: 480px; margin: 40px auto; background: #fff; border-radius: 10px; box-shadow: 0 4px 24px rgba(0,0,0,0.07); overflow: hidden; border: 1px solid #eaeaea;">
        <div style="background: #28a745; color: #fff; padding: 24px 32px 16px 32px; text-align: center;">
            <h1 style="color: #fff; margin: 0; font-size: 1.7rem; letter-spacing: 1px;">✅ Request Approved!</h1>
        </div>
        <div style="padding: 32px; color: #333;">
            <div style="font-size: 1.1rem; margin-bottom: 16px;">
                Hello {{ $documentRequest->first_name }},
            </div>
            <div style="margin-bottom: 24px; font-size: 1rem; line-height: 1.6;">
                Great news! Your document request has been <strong>approved</strong> by the Registrar and is now <strong>available</strong> for processing. Your reference number has been generated and you can now track your request status.
            </div>
            
            <div style="background: #fff3cd; border: 2px solid #ffc107; border-radius: 10px; padding: 20px; margin: 24px 0; text-align: center;">
                <div style="font-size: 1.3rem; font-weight: 700; color: #856404; margin-bottom: 8px;">
                    ✅ Your Request is Available!
                </div>
                <div style="font-size: 1rem; color: #856404; line-height: 1.5;">
                    Your document request has been verified and approved. It is now available in our system and will be processed accordingly.
                </div>
            </div>
            
            <div style="background: #e8f5e8; border: 2px solid #28a745; border-radius: 10px; padding: 20px; margin: 24px 0; text-align: center;">
                <div style="font-size: 1.2rem; font-weight: 600; color: #28a745; margin-bottom: 10px;">
                    Your Reference Number
                </div>
                <div style="font-size: 1.5rem; font-weight: 700; color: #155724; font-family: 'Courier New', monospace; letter-spacing: 2px;">
                    {{ $referenceNumber }}
                </div>
            </div>
            
            <div style="background: #f9f9f9; border-left: 5px solid #28a745; padding: 18px 20px; margin: 24px 0; font-size: 1rem; color: #333;">
                <strong>Request Details:</strong><br>
                <strong>Name:</strong> {{ $documentRequest->first_name }} {{ $documentRequest->middle_name ?? '' }} {{ $documentRequest->last_name }}<br>
                <strong>Course:</strong> {{ $documentRequest->course }}<br>
                <strong>Documents:</strong> 
                @foreach($documentRequest->requestedDocuments as $doc)
                    {{ $doc->document_type }} ({{ $doc->quantity }}){{ !$loop->last ? ', ' : '' }}
                @endforeach
            </div>
            
            <div style="text-align: center; margin: 32px 0;">
                <a href="{{ url('/track') }}" 
                   style="display: inline-block; background: #28a745; color: #fff; padding: 16px 32px; border-radius: 30px; text-decoration: none; font-weight: 600; font-size: 1.1rem; transition: all 0.3s ease;">
                    🔍 Track Your Request
                </a>
            </div>
            
            <div style="background: #d1ecf1; border: 1px solid #bee5eb; border-radius: 8px; padding: 16px; margin: 24px 0; font-size: 0.9rem; color: #0c5460;">
                <strong>📋 Next Steps:</strong><br>
                • Your request is <strong>available</strong> and has been approved by the Registrar<br>
                • Your request is now being processed<br>
                • You'll receive updates via email<br>
                • Use your reference number to track progress<br>
                • Documents will be ready for pickup when completed
            </div>
            
            <div style="font-size: 0.9rem; color: #666; line-height: 1.5;">
                If you have any questions about your request, please contact our support team using your reference number.
            </div>
        </div>
        <div style="background: #f6f6f6; color: #888; text-align: center; padding: 18px 32px; font-size: 0.95rem;">
            <p style="margin: 0;">iRequest Document Management System</p>
            <p style="margin: 5px 0 0 0;">This is an automated message, please do not reply.</p>
        </div>
    </div>
</body>
</html>