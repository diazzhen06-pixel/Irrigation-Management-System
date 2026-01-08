<?php
require_once '../models/IrrigationTask.php';
require_once '../helpers/session_helper.php';

class ReportController {
    private $taskModel;

    public function __construct() {
        if (!isLoggedIn()) {
            header('location:../public/index.php?url=auth/login');
            exit();
        }
        $this->taskModel = new IrrigationTask();
    }

    public function accomplishment() {
        $startDate = date('Y-m-d', strtotime('-30 days'));
        $endDate = date('Y-m-d');

        if (isset($_GET['start_date']) && isset($_GET['end_date'])) {
            $startDate = $_GET['start_date'];
            $endDate = $_GET['end_date'];
        }

        // A bit of a hack for the sake of simplicity, we'll get all tasks and filter in PHP
        // In a real app, this would be a dedicated model method
        $db = new Database();
        $db->query("
            SELECT t.*, a.area_name, u.full_name as staff_name, al.area_served, al.issues_encountered
            FROM irrigation_tasks t
            JOIN irrigation_areas a ON t.area_id = a.area_id
            JOIN users u ON t.assigned_staff_id = u.user_id
            LEFT JOIN activity_logs al ON t.task_id = al.task_id
            WHERE t.scheduled_date BETWEEN :start_date AND :end_date
            ORDER BY t.scheduled_date DESC
        ");
        $db->bind(':start_date', $startDate);
        $db->bind(':end_date', $endDate);
        $tasks = $db->resultSet();

        $data = [
            'tasks' => $tasks,
            'startDate' => $startDate,
            'endDate' => $endDate
        ];

        if (isset($_GET['export']) && $_GET['export'] == 'csv') {
            $this->exportAccomplishmentToCsv($data);
        } else {
            $this->view('reports/accomplishment', $data);
        }
    }

    private function exportAccomplishmentToCsv($data) {
        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="accomplishment_report.csv"');

        $output = fopen('php://output', 'w');
        fputcsv($output, ['Date', 'Area', 'Status', 'Staff', 'Area Served (ha)', 'Issues']);

        foreach($data['tasks'] as $task) {
            fputcsv($output, [
                $task->scheduled_date,
                $task->area_name,
                $task->status,
                $task->staff_name,
                $task->area_served,
                $task->issues_encountered
            ]);
        }
        fclose($output);
        exit();
    }

    public function view($view, $data = []) {
        if (file_exists('../views/' . $view . '.php')) {
            require_once '../views/' . $view . '.php';
        } else {
            error_redirect('View does not exist');
        }
    }
}
