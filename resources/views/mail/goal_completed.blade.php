<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<style>
  body { font-family: Arial, sans-serif; background:#f8fafc; margin:0; padding:20px; color:#1e293b; }
  .email-wrapper { max-width:600px; margin:0 auto; background:#fff; border-radius:12px; overflow:hidden; box-shadow:0 4px 20px rgba(0,0,0,0.08); }
  .email-header { background:linear-gradient(135deg,#22c55e,#16a34a); padding:40px 30px; text-align:center; }
  .email-logo { font-size:56px; }
  .email-header h1 { color:#fff; margin:10px 0 0; font-size:26px; }
  .email-body { padding:40px 30px; }
  .goal-box { background:#f0fdf4; border:2px solid #22c55e; border-radius:10px; padding:20px; margin:20px 0; }
  .goal-box h3 { margin:0 0 10px; color:#16a34a; font-size:20px; }
  .progress-bar-outer { background:#e2e8f0; border-radius:20px; height:16px; overflow:hidden; margin:10px 0; }
  .progress-bar-inner { background:#22c55e; height:100%; border-radius:20px; transition:width 1s; }
  .btn-cta { display:inline-block; background:#22c55e; color:#fff; text-decoration:none; padding:14px 32px; border-radius:8px; font-weight:600; margin:20px 0; }
  .email-footer { background:#f8fafc; padding:20px 30px; text-align:center; border-top:1px solid #e2e8f0; }
  .email-footer p { color:#94a3b8; font-size:13px; margin:0; }
</style>
</head>
<body>
<div class="email-wrapper">
  <div class="email-header">
    <div class="email-logo">🏆</div>
    <h1>Congratulations, {{ $user->name }}!</h1>
  </div>
  <div class="email-body">
    <p style="font-size:18px;color:#475569">You've completed a goal! Amazing work 🎉</p>

    <div class="goal-box">
      <h3>{{ $goal->title }}</h3>
      @if($goal->description)
        <p style="color:#475569;margin:5px 0">{{ $goal->description }}</p>
      @endif
      <div class="progress-bar-outer">
        <div class="progress-bar-inner" style="width:100%"></div>
      </div>
      <p style="color:#16a34a;font-weight:700;margin:5px 0">100% Complete ✓</p>
      @if($goal->deadline)
        <p style="color:#94a3b8;font-size:13px;margin:0">
          Deadline was: {{ $goal->deadline->format('d M Y') }}
          @if($goal->completed_at && $goal->completed_at->lessThanOrEqualTo($goal->deadline))
            <span style="color:#22c55e">— Completed on time! 🎯</span>
          @endif
        </p>
      @endif
    </div>

    <p style="color:#475569">Keep up the great work! Every completed goal brings you closer to your best self.</p>
    <a href="{{ url('/goals') }}" class="btn-cta">📊 View All Goals</a>
  </div>
  <div class="email-footer">
    <p>© {{ date('Y') }} GoalTracker — Sent to {{ $user->email }}</p>
  </div>
</div>
</body>
</html>
