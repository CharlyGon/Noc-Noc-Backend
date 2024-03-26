<?php

namespace App\Services;

use App\Models\Comment;

class CommentService
{
    /**
     * Create a new comment.
     *
     * @param array $data
     * @return Comment
     */
    public function createComment(array $data): Comment
    {
        try {
            return Comment::create($data);
        } catch (\Exception $e) {
            throw new \Exception("Error al crear el comentario: " . $e->getMessage());
        }
    }

    /**
     * Get all comments.
     *
     * @return Comment[]
     */
    public function updateComment(array $data, Comment $comment): Comment
    {
        try {
            $comment->update($data);
            return $comment;
        } catch (\Exception $e) {
            throw new \Exception("Error al actualizar el comentario: " . $e->getMessage());
        }
    }

    /**
     * Get a comment by ID.
     *
     * @return Comment
     */
    public function deleteComment(Comment $comment): void
    {
        try {
            $comment->delete();
        } catch (\Exception $e) {
            throw new \Exception("Error al eliminar el comentario: " . $e->getMessage());
        }
    }

    /**
     * Get all comments.
     *@param $taskId
     * @return Comment[]
     */
    public function getCommentsForTask($taskId)
    {
        try {
            return Comment::where('task_id', $taskId)->get();
        } catch (\Exception $e) {
            throw new \Exception("Error al obtener los comentarios de la tarea: " . $e->getMessage());
        }
    }
}
