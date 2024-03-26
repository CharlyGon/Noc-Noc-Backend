<?php

namespace App\Services;

use App\Models\Task;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Exception;

class TaskService
{
    /**
     * Create a new task.
     *
     * @param array $data
     * @return Task
     */
    public function createTask(array $data): Task
    {
        try {
            return Task::create([
                'title' => $data['title'],
                'description' => $data['description'],
                'created_by' => Auth::id(),
            ]);
        } catch (Exception $e) {
            throw new Exception("Error al crear la tarea: " . $e->getMessage());
        }
    }

    /**
     * Update a task.
     *
     * @param array $data
     * @param Task $task
     * @return Task
     */
    public function updateTask(array $data, Task $task): Task
    {
        try {
            $task->update([
                'title' => $data['title'] ?? $task->title,
                'description' => $data['description'] ?? $task->description,
            ]);

            return $task;
        } catch (Exception $e) {
            throw new Exception("Error al actualizar la tarea: " . $e->getMessage());
        }
    }

    /**
     * Update the status of a task.
     *
     * @param Task $task
     * @param string $newStatus
     * @param User $user
     * @return void
     */
    public function updateTaskStatus(Task $task, string $newStatus, User $user)
    {
        if ($this->userCanUpdateTaskStatus($task, $user)) {
            $task->status = $newStatus;
            $task->save();
        } else {
            throw new \Exception('Unauthorized to update task status');
        }
    }

    /**
     * Check if the user can update the status of a task.
     *
     * @param Task $task
     * @param User $user
     * @return bool
     */
    protected function userCanUpdateTaskStatus(Task $task, User $user)
    {
        return $user->id === $task->assigned_to || $user->isAdmin();
    }

    /**
     * Get all tasks.
     *
     * @return Task[]
     */
    public function getAllTasks()
    {
        try {
            return Task::all();
        } catch (Exception $e) {
            throw new Exception("Error al obtener todas las tareas: " . $e->getMessage());
        }
    }

    /**
     * Get a task for a user.
     *
     * @param $id
     * @return Task
     */
    public function getTasksForUser(User $user)
    {
        try {
            return $user->tasks;
        } catch (Exception $e) {
            throw new Exception("Error al obtener las tareas del usuario: " . $e->getMessage());
        }
    }

    /**
     * Delete a task.
     *
     * @param $id
     * @return Task
     */
    public function deleteTask(Task $task): void
    {
        try {
            $task->delete();
        } catch (Exception $e) {
            throw new Exception("Error al eliminar la tarea: " . $e->getMessage());
        }
    }

    /**
     * Get a task by date range.
     *
     * @param $id
     * @return Task
     */
    public function getTasksByDateRange($startDate, $endDate)
    {
        try {
            return Task::get();
            //return Task::whereBetween('created_at', [$startDate, $endDate])->get();
            // return Task::where('created_at', '>=', $startDate)
            //     ->where('created_at', '<=', $endDate)
            //     ->get();
        } catch (Exception $e) {
            throw new Exception("Error al obtener las tareas por rango de fecha: " . $e->getMessage());
        }
    }
}
