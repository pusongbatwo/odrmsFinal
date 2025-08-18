<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Verify Your Document Request</title>
</head>
<body style="background: #f6f6f6; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; margin: 0; padding: 0;">
    <div style="max-width: 480px; margin: 40px auto; background: #fff; border-radius: 10px; box-shadow: 0 4px 24px rgba(0,0,0,0.07); overflow: hidden; border: 1px solid #eaeaea;">
        <div style="background: #8B0000; color: #FFD700; padding: 24px 32px 16px 32px; text-align: center;">
            <h1 style="color: rgb(255, 255, 255); margin: 0; font-size: 1.7rem; letter-spacing: 1px;">📧 Verify Your Request</h1>
        </div>
        <div style="padding: 32px; color: #333;">
            <div style="font-size: 1.1rem; margin-bottom: 16px;">
                Hello,
            </div>
            <div style="margin-bottom: 24px; font-size: 1rem; line-height: 1.6;">
                You have submitted a document request through the iRequest system. To complete your request, please verify your email address by clicking the button below.
            </div>
            
            <div style="background: #f9f9f9; border-left: 5px solid #8B0000; padding: 18px 20px; margin: 24px 0; font-size: 1rem; color: #333;">
                <strong>Request Details:</strong><br>
                @if(isset($requestData['first_name']))
                    <strong>Name:</strong> {{ $requestData['first_name'] }} {{ $requestData['middle_name'] ?? '' }} {{ $requestData['last_name'] }}<br>
                @endif
                @if(isset($requestData['course']))
                    <strong>Course:</strong> {{ $requestData['course'] }}<br>
                @endif
                @if(isset($requestData['document_types']))
                    <strong>Documents:</strong> 
                    @foreach($requestData['document_types'] as $doc)
                        {{ $doc['type'] }} ({{ $doc['quantity'] }}){{ !$loop->last ? ', ' : '' }}
                    @endforeach
                @endif
            </div>
            
            <div style="text-align: center; margin: 32px 0;">
                <a href="{{ url('/verify-document-request/' . $verificationToken) }}" 
                   style="display: inline-block; background: #8B0000; color: #FFD700; padding: 16px 32px; border-radius: 30px; text-decoration: none; font-weight: 600; font-size: 1.1rem; transition: all 0.3s ease;">
                    ✅ Verify Email Address
                </a>
            </div>
            
            <div style="background: #fff3cd; border: 1px solid #ffc107; border-radius: 8px; padding: 16px; margin: 24px 0; font-size: 0.9rem; color: #856404;">
                <strong>⚠️ Important:</strong> This verification link will expire on {{ \Carbon\Carbon::parse($expiresAt)->format('F d, Y \a\t g:i A') }}. If you don't verify within this time, you'll need to submit a new request.
            </div>
            
            <div style="font-size: 0.9rem; color: #666; line-height: 1.5;">
                If you didn't submit this document request, please ignore this email. This verification link is valid for 24 hours only.
            </div>
        </div>
        <div style="background: #f6f6f6; color: #888; text-align: center; padding: 18px 32px; font-size: 0.95rem;">
            <p style="margin: 0;">iRequest Document Management System</p>
            <p style="margin: 5px 0 0 0;">This is an automated message, please do not reply.</p>
        </div>
    </div>
</body>
</html>
