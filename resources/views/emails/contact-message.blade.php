<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>New contact message</title>
</head>
<body style="margin:0;padding:24px;background:#faf1ea;font-family:Arial,sans-serif;color:#3a2436;">
    <div style="max-width:640px;margin:0 auto;background:#fff;border:1px solid #e8d9d2;padding:24px;">
        <h1 style="margin:0 0 16px;font-size:24px;color:#3b1f3a;">New contact message</h1>
        <p style="margin:0 0 12px;"><strong>Name:</strong> {{ $data['name'] }}</p>
        <p style="margin:0 0 12px;"><strong>Email:</strong> {{ $data['email'] }}</p>
        <p style="margin:0 0 12px;"><strong>Message:</strong></p>
        <div style="white-space:pre-wrap;line-height:1.7;">{{ $data['message'] }}</div>
    </div>
</body>
</html>
