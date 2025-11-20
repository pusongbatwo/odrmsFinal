<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Document Request Rejected</title>
</head>
<body style="background: #f6f6f6; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; margin: 0; padding: 0;">
    <div style="max-width: 480px; margin: 40px auto; background: #fff; border-radius: 10px; box-shadow: 0 4px 24px rgba(0,0,0,0.07); overflow: hidden; border: 1px solid #eaeaea;">
        <div style="background: #dc3545; color: #fff; padding: 24px 32px 16px 32px; text-align: center;">
            <h1 style="color: #fff; margin: 0; font-size: 1.7rem; letter-spacing: 1px;">❌ Request Rejected</h1>
        </div>
        <div style="padding: 32px; color: #333;">
            <div style="font-size: 1.1rem; margin-bottom: 16px;">
                Hello {{ $documentRequest->first_name }},
            </div>
            <div style="margin-bottom: 24px; font-size: 1rem; line-height: 1.6;">
                We regret to inform you that your document request has been rejected by the Registrar. Please review the details below and take the necessary action.
            </div>
            
            <div style="background: #f8d7da; border: 2px solid #dc3545; border-radius: 10px; padding: 20px; margin: 24px 0;">
                <div style="font-size: 1.2rem; font-weight: 600; color: #dc3545; margin-bottom: 15px;">
                    📋 Rejection Reason
                </div>
                <div style="font-size: 1rem; color: #721c24; line-height: 1.6;">
                    @if(!empty(trim($rejectionReason ?? '')))
                        {!! nl2br(e($rejectionReason)) !!}
                    @else
                        The registrar did not provide additional details for this rejection. Please contact the office if you need clarification.
                    @endif
                </div>
            </div>
            
            <div style="background: #f9f9f9; border-left: 5px solid #dc3545; padding: 18px 20px; margin: 24px 0; font-size: 1rem; color: #333;">
                <strong>Request Details:</strong><br>
                <strong>Name:</strong> {{ $documentRequest->first_name }} {{ $documentRequest->middle_name ?? '' }} {{ $documentRequest->last_name }}<br>
                <strong>Course:</strong> {{ $documentRequest->course }}<br>
                <strong>Documents:</strong> 
                @foreach($documentRequest->requestedDocuments as $doc)
                    {{ $doc->document_type }} ({{ $doc->quantity }}){{ !$loop->last ? ', ' : '' }}
                @endforeach
            </div>
            
            <div style="text-align: center; margin: 32px 0;">
                <a href="{{ url('/') }}" 
                   style="display: inline-block; background: #dc3545; color: #fff; padding: 16px 32px; border-radius: 30px; text-decoration: none; font-weight: 600; font-size: 1.1rem; transition: all 0.3s ease;">
                    📝 Submit New Request
                </a>
            </div>
            
            <div style="background: #fff3cd; border: 1px solid #ffeaa7; border-radius: 8px; padding: 16px; margin: 24px 0; font-size: 0.9rem; color: #856404;">
                <strong>💡 What You Can Do:</strong><br>
                • Review the rejection reason above<br>
                • Correct any issues with your information<br>
                • Submit a new request with corrected details<br>
                • Contact the Registrar's Office if you need clarification
            </div>
            
            <div style="background: #e3f2fd; border: 1px solid #bbdefb; border-radius: 8px; padding: 16px; margin: 24px 0; font-size: 0.9rem; color: #1565c0;">
                <strong>📞 Need Help?</strong><br>
                If you have questions about the rejection or need assistance, please contact the Registrar's Office during business hours.
            </div>
            
            <div style="font-size: 0.9rem; color: #666; line-height: 1.5;">
                Thank you for using our document request system. We're here to help you get the documents you need.
            </div>
        </div>
        <div style="background: #f6f6f6; color: #888; text-align: center; padding: 18px 32px; font-size: 0.95rem;">
            <p style="margin: 0;">iRequest Document Management System</p>
            <p style="margin: 5px 0 0 0;">This is an automated message, please do not reply.</p>
        </div>
    </div>
</body>
</html>
