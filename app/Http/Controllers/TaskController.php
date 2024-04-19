<?php

namespace App\Http\Controllers;

use App\Services\TaskService;
use Illuminate\Http\Request;
use App\Models\Task;
use App\Services\AuthService;
use App\Services\ReportService;
use Carbon\Carbon;

use Exception;
use Illuminate\Support\Facades\Log;

class TaskController extends Controller
{
    protected $taskService;
    protected $authService;
    protected $reportService;

    public function __construct(TaskService $taskService, AuthService $authService, ReportService $reportService)
    {
        $this->taskService = $taskService;
        $this->authService = $authService;
        $this->reportService = $reportService;
    }

    /**
     * Get all tasks.
     *
     * @return JsonResponse
     */
    public function index()
    {
        try {
            $tasks = $this->taskService->getAllTasks();

            return response()->json(['tasks' => $tasks]);
        } catch (Exception $e) {
            return response()->json(['error' => 'Failed to fetch tasks: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Create a new task.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function create(Request $request)
    {
        $validatedData = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'status' => 'nullable|string|in:Pendiente,En proceso,Bloqueado,Completado',
            'assigned_to' => 'required|exists:users,id',
        ]);

        try {
            $task = $this->taskService->createTask($validatedData);
            return response()->json(['message' => 'Task created successfully', 'task' => $task]);
        } catch (Exception $e) {
            return response()->json(['error' => 'Failed to create task: ' . $e->getMessage()], 500);
        }
    }

    /**
     * update a task.
     *
     * @param Task $task
     * @return JsonResponse
     */
    public function update(Request $request, Task $task)
    {
        try {
            $validatedData = $request->validate([
                'title' => 'string|max:255',
                'description' => 'string',
                'status' => 'string|in:Pendiente,En proceso,Bloqueado,Completado'
            ]);

            if ($request->has('status')) {
                $updatedTask = $this->taskService->updateTaskStatus($task, $validatedData['status'], auth()->user());
            } else {
                $updatedTask = $this->taskService->updateTask($validatedData, $task);
            }

            return response()->json([
                'message' => 'Task updated successfully',
                'task' => $updatedTask,
            ]);
        } catch (Exception $e) {
            return response()->json(['error' => 'Failed to update task: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Update the status of a task.
     *
     * @param Request $request
     * @param Task $task
     * @return JsonResponse
     */
    public function updateStatus(Request $request, Task $task)
    {
        try {
            $user = auth()->user();

            Log::info('User: ' . $user);

            if (!$user) {
                // Manejar el caso de no autenticación según tu lógica de negocio
                return response()->json(['error' => 'User not authenticated'], 401);
            }

            $validatedData = $request->validate([
                'status' => 'required|string|in:Pendiente,En proceso,Bloqueado,Completado',
            ]);

            $updatedTask = $this->taskService->updateTaskStatus($task, $validatedData['status'], $user);

            return response()->json([
                'message' => 'Task status updated successfully',
                'task' => $updatedTask,
            ]);
        } catch (Exception $e) {
            return response()->json(['error' => 'Failed to update task status: ' . $e->getMessage()], 500);
        }
    }


    /**
     * Delete a task.
     *
     * @param Task $task
     * @return JsonResponse
     */
    public function delete(Task $task)
    {
        try {
            $this->taskService->deleteTask($task);
            return response()->json(['message' => 'Task deleted successfully']);
        } catch (Exception $e) {
            return response()->json(['error' => 'Failed to delete task: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Generate a report of tasks.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function generateReport(Request $request) //!hay que  revisar el tema de las fechas
    {
        try {
            // // Validar los datos de entrada, como las fechas de inicio y fin
            // $request->validate([
            //     'start_date' => 'required|date_format:d/m/Y',
            //     'end_date' => 'required|date_format:d/m/Y|after_or_equal:start_date',
            // ]);
            $requestData = $request->json()->all();
            // Convertir las fechas al formato adecuado para su uso en la aplicación
            $startDate = Carbon::createFromFormat('d/m/Y', $requestData['start_date'])->startOfDay();
            $endDate = Carbon::createFromFormat('d/m/Y', $requestData['end_date'])->endOfDay();


            // Generar el informe utilizando el servicio
            $pdfContent = $this->reportService->generateTaskReport(
                $startDate,
                $endDate
            );

            // Devolver el PDF como una descarga
            return response($pdfContent)
                ->header('Content-Type', 'application/pdf')
                ->header('Content-Disposition', 'attachment; filename="task_report.pdf"');
        } catch (Exception $e) {
            return response()->json(['error' => 'Failed to generate report: ' . $e->getMessage()], 500);
        }
    }
}
