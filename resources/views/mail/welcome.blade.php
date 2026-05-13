<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<style>
  body { font-family: 'Inter', Arial, sans-serif; background:#f8fafc; margin:0; padding:20px; color:#1e293b; }
  .email-wrapper { max-width:600px; margin:0 auto; background:#fff; border-radius:12px; overflow:hidden; box-shadow:0 4px 20px rgba(0,0,0,0.08); }
  .email-header { background:linear-gradient(135deg,#6366f1,#8b5cf6); padding:40px 30px; text-align:center; }
  .email-logo { font-size:48px; margin-bottom:10px; }
  .email-header h1 { color:#fff; margin:0; font-size:28px; }
  .email-header p { color:rgba(255,255,255,0.85); margin:5px 0 0; }
  .email-body { padding:40px 30px; }
  .email-body h2 { color:#6366f1; font-size:22px; }
  .email-body p { line-height:1.7; color:#475569; }
  .btn-cta { display:inline-block; background:#6366f1; color:#fff; text-decoration:none; padding:14px 32px; border-radius:8px; font-weight:600; margin:20px 0; }
  .feature-list { background:#f1f5f9; border-radius:8px; padding:20px 30px; margin:20px 0; }
  .feature-list li { margin:8px 0; color:#475569; }
  .email-footer { background:#f8fafc; padding:20px 30px; text-align:center; border-top:1px solid #e2e8f0; }
  .email-footer p { color:#94a3b8; font-size:13px; margin:0; }
</style>
</head>
<body>
<div class="email-wrapper">
  <div class="email-header">
    <div class="email-logo">🎯</div>
    <h1>Welcome to GoalTracker!</h1>
    <p>Your journey to success starts now</p>
  </div>
  <div class="email-body">
    <h2>Hello, {{ $user->name }}! 👋</h2>
    <p>We're thrilled to have you on board. Your account has been created successfully and you're ready to start setting and tracking your personal goals.</p>

    <ul class="feature-list">
      <li>✅ <strong>Create unlimited goals</strong> with deadlines</li>
      <li>📊 <strong>Track progress</strong> from 0–100%</li>
      <li>🔔 <strong>Get deadline reminders</strong> via email</li>
      <li>📁 <strong>Upload files</strong> related to your goals</li>
      <li>🌐 <strong>Switch language</strong> between English & Hindi</li>
    </ul>

    <p>Click below to log in and create your first goal:</p>
    <a href="{{ url('/dashboard') }}" class="btn-cta">🚀 Go to Dashboard</a>

    <p style="color:#94a3b8;font-size:13px">If you did not create this account, you can safely ignore this email.</p>
  </div>
  <div class="email-footer">
    <p>© {{ date('Y') }} GoalTracker — Personal Goal Management System</p>
    <p>This email was sent to {{ $user->email }}</p>
  </div>
</div>
</body>
</html>
