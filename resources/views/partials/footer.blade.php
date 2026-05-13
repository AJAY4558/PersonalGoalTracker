{{-- Footer Partial --}}
<footer class="app-footer">
    <div class="footer-inner">
        <span>© {{ date('Y') }} <strong>GoalTracker</strong> — Personal Goal Management</span>
        <span class="footer-right">
            Built with <span class="text-danger">❤</span> using Laravel {{ app()->version() }}
        </span>
    </div>
</footer>
