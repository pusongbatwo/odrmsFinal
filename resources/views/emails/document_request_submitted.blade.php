<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document Request Submitted</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
        }
        .container {
            max-width: 600px;
            margin: 20px auto;
            padding: 20px;
            border: 1px solid #ddd;
            border-radius: 5px;
        }
        .header {
            background-color: #8B0000;
            color: #fff;
            padding: 10px;
            text-align: center;
            border-radius: 5px 5px 0 0;
        }
        .content {
            padding: 20px 0;
        }
        .footer {
            margin-top: 20px;
            font-size: 0.9em;
            text-align: center;
            color: #777;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h2>Document Request Submitted Successfully</h2>
        </div>
        <div class="content">
            <p>Dear {{ $docRequest->first_name }},</p>
            <p>Thank you for submitting your document request. We have received it and will begin processing it shortly.</p>
            <p>Your reference number is: <strong>{{ $docRequest->reference_number }}</strong></p>
            <p>You can use this reference number to track the status of your request on our website.</p>
            <p>Thank you for using our service.</p>
            <p>Sincerely,<br>The Registrar's Office</p>
        </div>
        <div class="footer">
            <p>&copy; {{ date('Y') }} iRequest. All rights reserved.</p>
        </div>
    </div>
</body>
</html> 