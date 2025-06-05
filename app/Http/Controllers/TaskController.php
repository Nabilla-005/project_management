<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TaskController extends Controller
{
    public function index(Project $project)
    {
        return $project->tasks()->with('assigned', 'creator')->get();
    }

    public function store(Request $request, Project $project)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'status' => 'nullable|in:To Do,In Progress,Done',
            'assigned_to' => 'nullable|exists:users,id',
        ]);

        $task = $project->tasks()->create([
            'title' => $request->title,
            'description' => $request->description,
            'status' => $request->status ?? 'To Do',
            'assigned_to' => $request->assigned_to,
            'created_by' => Auth::id(),
        ]);

        return response()->json(['message' => 'Task created successfully', 'task' => $task]);
    }

    public function show($id)
    {
        $task = Task::with('project', 'assigned', 'creator')->findOrFail($id);
        return response()->json($task);
    }

    public function update(Request $request, $id)
    {
        $task = Task::findOrFail($id);

        // Optional: cek jika user adalah assigned_to
        if (
            Auth::id() !== $task->assigned_to &&
            !Auth::user()->hasRole(['Project Manager', 'Admin'])
        ) {
            return response()->json(['message' => 'Forbidden'], 403);
        }

        $task->update($request->only('title', 'description', 'status', 'assigned_to'));

        return response()->json(['message' => 'Task updated', 'task' => $task]);
    }

    public function destroy($id)
    {
        $task = Task::findOrFail($id);
        $task->delete();
        return response()->json(['message' => 'Task deleted']);
    }

}