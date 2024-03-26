<?php

namespace App\Services;

use TCPDF;
use Exception;
use App\Services\TaskService;

class ReportService
{
    protected $taskService;

    public function __construct(TaskService $taskService)
    {
        $this->taskService = $taskService;
    }

    /**
     * Generate a report of tasks.
     *
     * @param string $startDate
     * @param string $endDate
     * @return string
     */
    public function generateTaskReport($startDate, $endDate)
    {
        try {
            $tasks = $this->taskService->getTasksByDateRange($startDate, $endDate);

            // Crear una nueva instancia de TCPDF
            $pdf = new TCPDF();

            // Establecer la información del documento PDF
            $pdf->SetCreator('Your Application Name');
            $pdf->SetTitle('Task Report');

            // Agregar una página al documento PDF
            $pdf->AddPage();

            // Definir el contenido del informe
            $html = '<h1>Task Report</h1>';
            $html .= '<table border="1">';
            $html .= '<tr><th>Title</th><th>Description</th><th>Status</th></tr>';
            foreach ($tasks as $task) {
                $html .= '<tr><td>' . $task->title . '</td>'
                    . '<td>' . $task->description . '</td>'
                    . '<td>' . $task->status . '</td></tr>';
            }
            $html .= '</table>';

            // Escribir el contenido en el documento PDF
            $pdf->writeHTML($html, true, false, true, false, '');

            // Devolver el contenido del PDF
            return $pdf->Output('task_report.pdf', 'S');
        } catch (Exception $e) {
            throw new Exception($e->getMessage());
        }
    }
}
