<?php

namespace App\Http\Controllers;

use App\Mail\GoalCompletedMail;
use App\Models\AppNotification;
use App\Models\Category;
use App\Models\Goal;
use App\Http\Requests\StoreGoalRequest;
use App\Http\Requests\UpdateGoalRequest;
use App\Services\ActivityLogService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;

/**
 * GoalController — Resource Controller
 *
 * Handles all CRUD operations for goals.
 * Demonstrates:
 *   - Resource Controllers (index, create, store, show, edit, update, destroy)
 *   - Route Model Binding
 *   - Eloquent ORM + Query Builder
 *   - File Uploads
 *   - Search & Filter
 *   - JSON API responses
 */
class GoalController extends Controller
{
    /**
     * Apply auth middleware to all methods.
     * Only authenticated users can manage goals.
     */

    // ─────────────────────────────────────────────
    // INDEX — List all goals with search/filter
    // GET /goals
    // ─────────────────────────────────────────────

    public function index(Request $request)
    {
        $query = Auth::user()->goals()->with('category');

        // === SEARCH (Query Builder demonstration) ===
        if ($search = $request->get('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        // === FILTER BY STATUS ===
        if ($status = $request->get('status')) {
            $query->where('status', $status);
        }

        // === FILTER BY CATEGORY ===
        if ($category = $request->get('category')) {
            $query->where('category_id', $category);
        }

        // === FILTER BY PRIORITY ===
        if ($priority = $request->get('priority')) {
            $query->where('priority', $priority);
        }

        // === SORTING ===
        $sort  = $request->get('sort', 'created_at');
        $order = $request->get('order', 'desc');
        $allowedSorts = ['created_at', 'deadline', 'progress', 'priority', 'title'];
        if (in_array($sort, $allowedSorts)) {
            $query->orderBy($sort, $order === 'asc' ? 'asc' : 'desc');
        }

        // Paginate results (10 per page)
        $goals      = $query->paginate(10)->withQueryString();
        $categories = Category::all();

        // === JSON API response (for AJAX / API-like requests) ===
        if ($request->wantsJson()) {
            return response()->json([
                'status' => 'success',
                'data'   => $goals,
            ]);
        }

        return view('goals.index', compact('goals', 'categories'));
    }

    // ─────────────────────────────────────────────
    // CREATE — Show form
    // GET /goals/create
    // ─────────────────────────────────────────────

    public function create()
    {
        $categories = Category::all();
        return view('goals.create', compact('categories'));
    }

    // ─────────────────────────────────────────────
    // STORE — Save new goal
    // POST /goals
    // ─────────────────────────────────────────────

    public function store(StoreGoalRequest $request)
    {
        // Handle file upload
        $imagePath = null;
        if ($request->hasFile('image')) {
            // Store in storage/app/public/goals/
            $imagePath = $request->file('image')->store('goals', 'public');
        }

        // Create goal using Eloquent
        $goal = Auth::user()->goals()->create([
            'category_id' => $request->category_id,
            'title'       => $request->title,
            'description' => $request->description,
            'deadline'    => $request->deadline,
            'status'      => $request->status,
            'priority'    => $request->priority,
            'progress'    => $request->progress,
            'image'       => $imagePath,
        ]);

        // Log to MongoDB
        ActivityLogService::log('goal.created', "Goal created: {$goal->title}", [
            'goal_id'  => $goal->id,
            'title'    => $goal->title,
            'priority' => $goal->priority,
        ]);

        return redirect()
            ->route('goals.show', $goal)
            ->with('success', "Goal \"{$goal->title}\" created successfully!");
    }

    // ─────────────────────────────────────────────
    // SHOW — View single goal (Route Model Binding)
    // GET /goals/{goal}
    // ─────────────────────────────────────────────

    public function show(Goal $goal)
    {
        // Authorization: ensure this goal belongs to the authenticated user
        abort_if($goal->user_id !== Auth::id(), 403, 'Access denied.');

        return view('goals.show', compact('goal'));
    }

    // ─────────────────────────────────────────────
    // EDIT — Show edit form
    // GET /goals/{goal}/edit
    // ─────────────────────────────────────────────

    public function edit(Goal $goal)
    {
        abort_if($goal->user_id !== Auth::id(), 403, 'Access denied.');

        $categories = Category::all();
        return view('goals.edit', compact('goal', 'categories'));
    }

    // ─────────────────────────────────────────────
    // UPDATE — Save goal changes
    // PUT/PATCH /goals/{goal}
    // ─────────────────────────────────────────────

    public function update(UpdateGoalRequest $request, Goal $goal)
    {
        abort_if($goal->user_id !== Auth::id(), 403, 'Access denied.');

        $wasCompleted = $goal->status === 'completed';

        // Handle image upload (replace old image if new one provided)
        $imagePath = $goal->image;
        if ($request->hasFile('image')) {
            // Delete old image if it exists
            if ($imagePath) {
                Storage::disk('public')->delete($imagePath);
            }
            $imagePath = $request->file('image')->store('goals', 'public');
        }

        // Update using Query Builder (demonstration)
        DB::table('goals')->where('id', $goal->id)->update([
            'category_id'  => $request->category_id,
            'title'        => $request->title,
            'description'  => $request->description,
            'deadline'     => $request->deadline,
            'status'       => $request->status,
            'priority'     => $request->priority,
            'progress'     => $request->progress,
            'image'        => $imagePath,
            'completed_at' => $request->status === 'completed' ? now() : null,
            'updated_at'   => now(),
        ]);

        $goal->refresh();

        // Send completion email if status just changed to 'completed'
        if (!$wasCompleted && $goal->status === 'completed') {
            try {
                Mail::to(Auth::user()->email)->send(new GoalCompletedMail(Auth::user(), $goal));
            } catch (\Exception $e) {
                logger()->warning('Goal completion email failed: ' . $e->getMessage());
            }

            ActivityLogService::log('goal.completed', "Goal completed: {$goal->title}", [
                'goal_id' => $goal->id,
            ]);

            AppNotification::create([
                'user_id' => Auth::id(),
                'type' => 'goal_completed',
                'data' => [
                    'goal_id' => $goal->id,
                    'goal_title' => $goal->title,
                    'message' => "Goal completed: {$goal->title}",
                ],
            ]);
        } else {
            ActivityLogService::log('goal.updated', "Goal updated: {$goal->title}", [
                'goal_id' => $goal->id,
            ]);
        }

        return redirect()
            ->route('goals.show', $goal)
            ->with('success', "Goal \"{$goal->title}\" updated successfully!");
    }

    // ─────────────────────────────────────────────
    // DESTROY — Delete goal
    // DELETE /goals/{goal}
    // ─────────────────────────────────────────────

    public function destroy(Goal $goal)
    {
        abort_if($goal->user_id !== Auth::id(), 403, 'Access denied.');

        // Delete uploaded image from storage
        if ($goal->image) {
            Storage::disk('public')->delete($goal->image);
        }

        ActivityLogService::log('goal.deleted', "Goal deleted: {$goal->title}", [
            'goal_id' => $goal->id,
            'title'   => $goal->title,
        ]);

        $title = $goal->title;
        $goal->delete();

        return redirect()
            ->route('goals.index')
            ->with('success', "Goal \"{$title}\" deleted successfully.");
    }
}
