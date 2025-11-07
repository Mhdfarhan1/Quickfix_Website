<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <title>Incident Alert</title>
</head>
<body style="font-family: sans-serif;">
  <h2>🚨 Security Incident Detected</h2>
  <p><strong>Type:</strong> {{ $incident['type'] }}</p>
  <p><strong>Description:</strong> {{ $incident['description'] }}</p>
  <p><strong>IP:</strong> {{ $incident['ip'] }}</p>
  <p><strong>User ID:</strong> {{ $incident['user_id'] ?? 'N/A' }}</p>
  <p><strong>Detected At:</strong> {{ $incident['created_at'] }}</p>
  <hr>
  <p style="color: #888;">This is an automated message from QuickFix Incident Response System.</p>
</body>
</html>
