<?php

namespace App\Http\Controllers;

use App\Services\CommentService;
use Illuminate\Http\Request;
use App\Models\Comment;
use Illuminate\Support\Facades\Auth;
use Exception;

class CommentController extends Controller
{
    protected $commentService;

    public function __construct(CommentService $commentService)
    {
        $this->commentService = $commentService;
    }

    /**
     * Get all comments for a task.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function index(Request $request)
    {
        try {
            $taskId = $request->input('task_id');
            $comments = $this->commentService->getCommentsForTask($taskId);
            return response()->json(['comments' => $comments]);
        } catch (Exception $e) {
            return response()->json(['error' => 'Failed to fetch comments: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Create a new comment.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function create(Request $request)
    {
        $user = Auth::user();

        if (!$user) {
            return response()->json(['error' => 'Usuario no autenticado'], 401);
        }

        $validatedData = $request->validate([
            'task_id' => 'required|integer',
            'body' => 'required|string',
        ]);

        $validatedData['user_id'] = $user->id;

        try {
            $comment = $this->commentService->createComment($validatedData);
            return response()->json(['message' => 'Comment created successfully', 'comment' => $comment]);
        } catch (Exception $e) {
            return response()->json(['error' => 'Failed to create comment: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Update a comment.
     *
     * @param Request $request
     * @param Comment $comment
     * @return JsonResponse
     */
    public function update(Request $request, Comment $comment)
    {
        $validatedData = $request->validate([
            'content' => 'required|string',
        ]);

        try {
            $updatedComment = $this->commentService->updateComment($validatedData, $comment);
            return response()->json(['message' => 'Comment updated successfully', 'comment' => $updatedComment]);
        } catch (Exception $e) {
            return response()->json(['error' => 'Failed to update comment: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Delete a comment.
     *
     * @param Comment $comment
     * @return JsonResponse
     */
    public function delete(Comment $comment)
    {
        try {
            $this->commentService->deleteComment($comment);
            return response()->json(['message' => 'Comment deleted successfully']);
        } catch (Exception $e) {
            return response()->json(['error' => 'Failed to delete comment: ' . $e->getMessage()], 500);
        }
    }
}
