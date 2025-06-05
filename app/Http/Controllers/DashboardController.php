<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\Task;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller; // <- ini yang benar

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        if ($user->hasRole('Admin')) {
            $totalProjects = Project::count();
            $totalTasks = Task::count();
            $doneTasks = Task::where('status', 'Done')->count();
        } elseif ($user->hasRole('Project Manager')) {
            $totalProjects = Project::where('created_by', $user->id)->count();
            $projectIds = Project::where('created_by', $user->id)->pluck('id');
            $totalTasks = Task::whereIn('project_id', $projectIds)->count();
            $doneTasks = Task::whereIn('project_id', $projectIds)->where('status', 'Done')->count();
        } else {
            $totalProjects = Project::count();
            $totalTasks = Task::where('assigned_to', $user->id)->count();
            $doneTasks = Task::where('assigned_to', $user->id)->where('status', 'Done')->count();
        }

        $progressPercentage = $totalTasks > 0 ? round(($doneTasks / $totalTasks) * 100, 2) : 0;


        return response()->json([
            'total_projects' => $totalProjects,
            'total_tasks' => $totalTasks,
            'completed_tasks' => $doneTasks,
            'progress_percentage' => $progressPercentage,
        ]);
    }
}