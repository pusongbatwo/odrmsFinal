<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Request Approved</title>
</head>
<body style="background: #f6f6f6; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; margin: 0; padding: 0;">
    <div style="max-width: 480px; margin: 40px auto; background: #fff; border-radius: 10px; box-shadow: 0 4px 24px rgba(0,0,0,0.07); overflow: hidden; border: 1px solid #eaeaea;">
        <div style="background: #8B0000; color: #FFD700; padding: 24px 32px 16px 32px; text-align: center;">
            <h1 style="margin: 0; font-size: 1.7rem; letter-spacing: 1px;">🎉 Request Approved!</h1>
        </div>
        <div style="padding: 32px; color: #333;">
            <div style="font-size: 1.1rem; margin-bottom: 16px;">
                Hello {{ $documentRequest->first_name }} {{ $documentRequest->last_name }},
            </div>
            <div style="margin-bottom: 24px; font-size: 1rem; line-height: 1.6;">
                We are pleased to inform you that your document request has been <b>approved</b>.<br>
                Please use the reference number below to track your request status.
            </div>
            <div style="background: #f9f9f9; border-left: 5px solid #8B0000; padding: 18px 20px; margin: 24px 0; font-size: 1.2rem; color: #8B0000; font-weight: bold; border-radius: 6px; text-align: center; letter-spacing: 1px;">
                Reference Number:<br>
                {{ $reference }}
            </div>
            <div style="margin-bottom: 24px; font-size: 0.98rem;">
                <b>Requested Documents:</b><br>
                @foreach($documentRequest->requestedDocuments as $doc)
                    • {{ $doc->document_type }} ({{ $doc->quantity }})<br>
                @endforeach
                <br>
                <b>Course:</b> {{ $documentRequest->course }}<br>
                <b>Request Date:</b> {{ \Carbon\Carbon::parse($documentRequest->request_date)->format('F d, Y') }}
            </div>
            <a href="{{ url('/track') }}" style="display: inline-block; background: #FFD700; color: #8B0000; padding: 10px 28px; border-radius: 24px; text-decoration: none; font-weight: 600; margin-top: 18px; font-size: 1rem; transition: background 0.2s;">Track Your Request</a>
        </div>
        <div style="background: #f6f6f6; color: #888; text-align: center; padding: 18px 32px; font-size: 0.95rem;">
            If you have any questions, please contact the Registrar's Office.<br>
            Thank you for using our service!
        </div>
    </div>
</body>
</html>