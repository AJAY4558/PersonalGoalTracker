<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<style>
  body { font-family: Arial, sans-serif; background:#f8fafc; margin:0; padding:20px; color:#1e293b; }
  .email-wrapper { max-width:600px; margin:0 auto; background:#fff; border-radius:12px; overflow:hidden; box-shadow:0 4px 20px rgba(0,0,0,0.08); }
  .email-header { background:linear-gradient(135deg,#f59e0b,#d97706); padding:40px 30px; text-align:center; }
  .email-logo { font-size:56px; }
  .email-header h1 { color:#fff; margin:10px 0 0; font-size:24px; }
  .email-body { padding:40px 30px; }
  .goal-reminder-box { background:#fffbeb; border-left:4px solid #f59e0b; border-radius:8px; padding:20px; margin:20px 0; }
  .days-badge { display:inline-block; background:#f59e0b; color:#fff; padding:4px 12px; border-radius:20px; font-weight:700; font-size:14px; }
  .btn-cta { display:inline-block; background:#f59e0b; color:#fff; text-decoration:none; padding:14px 32px; border-radius:8px; font-weight:600; margin:20px 0; }
  .email-footer { background:#f8fafc; padding:20px 30px; text-align:center; border-top:1px solid #e2e8f0; }
  .email-footer p { color:#94a3b8; font-size:13px; margin:0; }
</style>
</head>
<body>
<div class="email-wrapper">
  <div class="email-header">
    <div class="email-logo">⏰</div>
    <h1>Deadline Reminder!</h1>
  </div>
  <div class="email-body">
    <p>Hi <strong>{{ $user->name }}</strong>,</p>
    <p>This is a friendly reminder that one of your goals is due soon. Don't forget to take action!</p>

    <div class="goal-reminder-box">
      <h3 style="margin:0 0 8px;color:#92400e">{{ $goal->title }}</h3>
      @if($goal->description)
        <p style="color:#78350f;margin:0 0 12px">{{ $goal->description }}</p>
      @endif
      <p style="margin:0">
        📅 <strong>Deadline:</strong> {{ $goal->deadline->format('l, d F Y') }}
        <br>
        <span class="days-badge">
          {{ max(0, now()->diffInDays($goal->deadline)) }} day(s) remaining
        </span>
      </p>
      <p style="margin:8px 0 0">
        📊 <strong>Current Progress:</strong> {{ $goal->progress }}%
      </p>
    </div>

    <p style="color:#475569">Log in now and give your goal the attention it deserves before the deadline!</p>
    <a href="{{ url('/goals/' . $goal->id) }}" class="btn-cta">🎯 View & Update Goal</a>
  </div>
  <div class="email-footer">
    <p>© {{ date('Y') }} GoalTracker — Sent to {{ $user->email }}</p>
    <p>You're receiving this because you have a goal with an upcoming deadline.</p>
  </div>
</div>
</body>
</html>
