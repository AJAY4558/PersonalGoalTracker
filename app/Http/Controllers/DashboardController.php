<?php

namespace App\Http\Controllers;

use App\Models\Goal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

/**
 * DashboardController
 *
 * Displays the main dashboard with:
 *   - Total, completed, pending, in-progress goal counts
 *   - Average completion percentage
 *   - Upcoming deadlines
 *   - Chart.js data (JSON)
 *   - Recent activity
 *
 * Demonstrates: Eloquent aggregates, Query Builder, JSON responses
 */
class DashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Main dashboard view.
     * GET /dashboard
     */
    public function index()
    {
        $userId = Auth::id();

        // === STATISTICS using Eloquent aggregates ===
        $totalGoals     = Goal::where('user_id', $userId)->count();
        $completedGoals = Goal::where('user_id', $userId)->where('status', 'completed')->count();
        $pendingGoals   = Goal::where('user_id', $userId)->where('status', 'pending')->count();
        $inProgressGoals= Goal::where('user_id', $userId)->where('status', 'in_progress')->count();
        $cancelledGoals = Goal::where('user_id', $userId)->where('status', 'cancelled')->count();

        // Average progress across all goals
        $avgProgress = Goal::where('user_id', $userId)->avg('progress') ?? 0;

        // Completion percentage
        $completionRate = $totalGoals > 0
            ? round(($completedGoals / $totalGoals) * 100, 1)
            : 0;

        // === UPCOMING DEADLINES (next 7 days) ===
        $upcomingGoals = Goal::where('user_id', $userId)
            ->upcoming()
            ->orderBy('deadline')
            ->take(5)
            ->get();

        // === RECENT GOALS ===
        $recentGoals = Goal::where('user_id', $userId)
            ->with('category')
            ->orderByDesc('created_at')
            ->take(5)
            ->get();

        // === GOALS BY CATEGORY (for pie chart) using Query Builder ===
        $goalsByCategory = DB::table('goals')
            ->join('categories', 'goals.category_id', '=', 'categories.id')
            ->where('goals.user_id', $userId)
            ->select('categories.name', 'categories.color', DB::raw('count(*) as total'))
            ->groupBy('categories.id', 'categories.name', 'categories.color')
            ->get();

        // === GOALS BY MONTH (for line chart) ===
        $goalsByMonth = DB::table('goals')
            ->where('user_id', $userId)
            ->where('created_at', '>=', now()->subMonths(6))
            ->select(
                DB::raw("DATE_FORMAT(created_at, '%b %Y') as month"),
                DB::raw('count(*) as total')
            )
            ->groupBy('month')
            ->orderBy('created_at')
            ->get();

        return view('dashboard.index', compact(
            'totalGoals',
            'completedGoals',
            'pendingGoals',
            'inProgressGoals',
            'cancelledGoals',
            'avgProgress',
            'completionRate',
            'upcomingGoals',
            'recentGoals',
            'goalsByCategory',
            'goalsByMonth'
        ));
    }

    /**
     * Admin Dashboard
     * GET /admin/dashboard
     * Shows system-wide statistics (admin only)
     */
    public function adminIndex()
    {
        // System-wide stats (admin view)
        $stats = [
            'total_users'    => DB::table('users')->count(),
            'total_goals'    => DB::table('goals')->count(),
            'completed_goals'=> DB::table('goals')->where('status', 'completed')->count(),
            'goals_today'    => DB::table('goals')->whereDate('created_at', today())->count(),
        ];

        $recentUsers = DB::table('users')->orderByDesc('created_at')->take(10)->get();

        return view('dashboard.admin', compact('stats', 'recentUsers'));
    }
}
