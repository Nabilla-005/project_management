<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Events\KomentarDitambahkan; // Tambahkan event

class CommentController extends Controller
{
    public function index(Task $task)
    {
        $comments = $task->comments()->with('user')->orderBy('created_at')->get();
        return response()->json($comments);
    }

    public function store(Request $request, Task $task)
    {
        $request->validate([
            'content' => 'required|string',
        ]);

        $comment = $task->comments()->create([
            'content' => $request->content,
            'user_id' => Auth::id(),
        ]);

        // Panggil event setelah komentar dibuat
        broadcast(new KomentarDitambahkan($comment->load('user')))->toOthers();

        return response()->json([
            'message' => 'Comment added successfully',
            'comment' => $comment,
        ]);
    }
}