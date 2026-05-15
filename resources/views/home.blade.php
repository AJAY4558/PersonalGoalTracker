{{-- Home / Landing Page --}}
<!DOCTYPE html>
<html class="dark" lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Altair — Built for Ambition. Set, track, and achieve your goals.">
    <title>Altair — Built for Ambition</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@24,100..700,0..1,0&family=Sora:wght@400;600;700;800&display=swap" rel="stylesheet">
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
</head>
<body class="landing-body">

    {{-- NAVBAR --}}
    <nav class="landing-nav">
        <div class="container-xl d-flex align-items-center justify-content-between">
            <a href="{{ route('home') }}" class="landing-brand">✦ Altair</a>
            <div class="landing-nav-links">
                <a href="{{ route('locale.switch', 'en') }}" class="btn btn-sm btn-outline-secondary me-1">EN</a>
                <a href="{{ route('locale.switch', 'hi') }}" class="btn btn-sm btn-outline-secondary me-2">HI</a>
                @auth
                    <a href="{{ route('dashboard') }}" class="btn btn-primary">Dashboard</a>
                @else
                    <a href="{{ route('login') }}" class="btn btn-outline-primary me-2">Login</a>
                    <a href="{{ route('register') }}" class="btn btn-primary">Get Started Free</a>
                @endauth
            </div>
        </div>
    </nav>

    {{-- HERO SECTION --}}
    <section class="hero-section tech-grid">
        <div class="container-xl">
            <div class="row align-items-center min-vh-100 py-5">
                <div class="col-lg-6">
                    <span class="hero-badge">✨ Built for Ambition</span>
                    <h1 class="hero-title shimmer-text">Master Your Ambition</h1>
                    <p class="hero-subtitle">
                        A powerful personal goal tracking system built with Laravel. Set deadlines,
                        track progress, stay motivated, and reach every milestone.
                    </p>
                    <div class="hero-actions">
                        @auth
                            <a href="{{ route('dashboard') }}" class="btn btn-primary btn-lg me-3 shimmer-btn inner-glow-primary">
                                <i class="bi bi-speedometer2 me-2"></i> Go to Dashboard <span class="material-symbols-outlined">arrow_forward</span>
                            </a>
                        @else
                            <a href="{{ route('register') }}" class="btn btn-primary btn-lg me-3 shimmer-btn inner-glow-primary">
                                <i class="bi bi-rocket-takeoff me-2"></i> Start Tracking Free <span class="material-symbols-outlined">arrow_forward</span>
                            </a>
                            <a href="{{ route('login') }}" class="btn btn-outline-light btn-lg obsidian-card">Login</a>
                        @endauth
                    </div>
                    <div class="hero-stats">
                        <div class="hero-stat"><span>🎯</span><div><strong>Goals</strong><small>Set & Track</small></div></div>
                        <div class="hero-stat"><span>📊</span><div><strong>Progress</strong><small>Visualized</small></div></div>
                        <div class="hero-stat"><span>🔔</span><div><strong>Reminders</strong><small>Automated</small></div></div>
                        <div class="hero-stat"><span>🌐</span><div><strong>Multilingual</strong><small>EN & HI</small></div></div>
                    </div>
                </div>
                <div class="col-lg-6 d-none d-lg-block">
                    <div class="hero-dashboard-preview">
                        <div class="preview-card">
                            <div class="preview-header">
                                <span class="preview-dot red"></span>
                                <span class="preview-dot yellow"></span>
                                <span class="preview-dot green"></span>
                                <span class="ms-2 small text-muted">Altair Dashboard</span>
                            </div>
                            <div class="preview-stats">
                                <div class="preview-stat-card blue">
                                    <div class="preview-stat-num">12</div>
                                    <div class="preview-stat-label">Total Goals</div>
                                </div>
                                <div class="preview-stat-card green">
                                    <div class="preview-stat-num">8</div>
                                    <div class="preview-stat-label">Completed</div>
                                </div>
                                <div class="preview-stat-card orange">
                                    <div class="preview-stat-num">3</div>
                                    <div class="preview-stat-label">In Progress</div>
                                </div>
                                <div class="preview-stat-card purple">
                                    <div class="preview-stat-num">67%</div>
                                    <div class="preview-stat-label">Success Rate</div>
                                </div>
                            </div>
                            <div class="preview-goal-list">
                                <div class="preview-goal completed">✅ Learn Laravel Framework</div>
                                <div class="preview-goal in-progress">🔄 Build Portfolio Website <span class="progress-mini">75%</span></div>
                                <div class="preview-goal pending">⏳ Get AWS Certification</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- BENTO COCKPIT --}}
    <section class="cockpit-section py-5">
        <div class="container-xl">
            <h2 class="section-title text-center mb-4">Mission Cockpit</h2>
            <div class="cockpit-grid">
                <div class="cockpit-card cockpit-large obsidian-card">
                    <div class="cockpit-kicker">Execution Fidelity</div>
                    <div class="cockpit-number">98.4%</div>
                    <div class="cockpit-bars">
                        @foreach([34,62,48,76,92,68,84,56,96,72,88,64] as $height)
                            <span style="height: {{ $height }}%"></span>
                        @endforeach
                    </div>
                </div>
                <div class="cockpit-card obsidian-card">
                    <div class="cockpit-kicker">Milestone Relay</div>
                    <h3>3 protocols active</h3>
                    <p>Priority streaks, deadline checks, and focus windows stay visible without noise.</p>
                    <div class="avatar-stack">
                        <span>A</span><span>L</span><span>X</span>
                    </div>
                </div>
                <div class="cockpit-card obsidian-card">
                    <span class="material-symbols-outlined ghost-icon">bolt</span>
                    <div class="cockpit-kicker">Velocity Engine</div>
                    <h3>Adaptive pacing</h3>
                    <p>Keep progress, priority, and category signals close to every objective.</p>
                </div>
                <div class="cockpit-card cockpit-wide obsidian-card cockpit-image">
                    <div>
                        <div class="cockpit-kicker">Strategic Surface</div>
                        <h3>Plan, track, complete, repeat.</h3>
                        <p>Altair Lux turns every goal into a compact operational dashboard.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- FEATURES SECTION --}}
    <section class="features-section py-5">
        <div class="container-xl">
            <h2 class="section-title text-center">Everything You Need to Succeed</h2>
            <p class="section-subtitle text-center">Built with Laravel MVC architecture, covering all syllabus concepts</p>
            <div class="row g-4 mt-4">
                @foreach([
                    ['icon' => 'bi-shield-lock', 'color' => 'blue', 'title' => 'Secure Authentication', 'desc' => 'Register, login with Remember Me, CSRF protection, session management'],
                    ['icon' => 'bi-bullseye', 'color' => 'green', 'title' => 'Goal CRUD', 'desc' => 'Create, read, update, delete goals with Eloquent ORM and Query Builder'],
                    ['icon' => 'bi-graph-up', 'color' => 'orange', 'title' => 'Progress Tracking', 'desc' => 'Track 0–100% progress with Chart.js visualizations on dashboard'],
                    ['icon' => 'bi-bell', 'color' => 'purple', 'title' => 'Email Notifications', 'desc' => 'Welcome, goal completion, and deadline reminder emails via Laravel Mail'],
                    ['icon' => 'bi-translate', 'color' => 'teal', 'title' => 'Multilingual', 'desc' => 'Full English and Hindi (हिन्दी) support with language switcher'],
                    ['icon' => 'bi-database', 'color' => 'red', 'title' => 'MySQL + MongoDB', 'desc' => 'MySQL for app data, MongoDB for activity logs and analytics'],
                ] as $feature)
                <div class="col-md-4">
                    <div class="feature-card">
                        <div class="feature-icon {{ $feature['color'] }}">
                            <i class="bi {{ $feature['icon'] }}"></i>
                        </div>
                        <h3 class="feature-title">{{ $feature['title'] }}</h3>
                        <p class="feature-desc">{{ $feature['desc'] }}</p>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </section>

    {{-- TECH STACK --}}
    <section class="tech-section py-5">
        <div class="container-xl text-center">
            <h2 class="section-title">Tech Stack</h2>
            <div class="tech-pills mt-4">
                @foreach(['Laravel 12', 'PHP 8.2', 'MySQL', 'MongoDB', 'Bootstrap 5', 'Chart.js', 'Blade Templates', 'Eloquent ORM'] as $tech)
                    <span class="tech-pill">{{ $tech }}</span>
                @endforeach
            </div>
        </div>
    </section>

    {{-- CTA --}}
    <section class="cta-section py-5">
        <div class="container-xl text-center">
            <h2 class="cta-title">Ambition deserves the right tool.</h2>
            <p class="cta-subtitle">Join Altair and start turning your goals into achievements today.</p>
            <a href="{{ route('register') }}" class="btn btn-primary btn-lg mt-3">
                <i class="bi bi-rocket-takeoff me-2"></i> Get Started — It's Free
            </a>
        </div>
    </section>

    <footer class="landing-footer">
        <div class="container-xl text-center">
            <p>© {{ date('Y') }} Altair — Built for Ambition.</p>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
