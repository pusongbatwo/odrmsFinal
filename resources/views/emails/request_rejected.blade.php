<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Request Rejected</title>
</head>
<body>
    <h2>Document Request Rejected</h2>
    <p>Dear {{ $request->first_name }} {{ $request->last_name }},</p>
    <p>We regret to inform you that your document request (Reference #: <b>{{ $request->reference_number }}</b>) has been <b>rejected</b>.</p>
    <p>If you have questions, please contact the registrar's office.</p>
    <p>Thank you.</p>
</body>
</html>
