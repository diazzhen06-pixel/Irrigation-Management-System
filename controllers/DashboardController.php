<?php
require_once '../models/IrrigationTask.php';
require_once '../models/Alert.php';
require_once '../helpers/session_helper.php';

class DashboardController {
    private $taskModel;
    private $alertModel;

    public function __construct() {
        if (!isLoggedIn()) {
            header('location:../public/index.php?url=auth/login');
            exit();
        }
        $this->taskModel = new IrrigationTask();
        $this->alertModel = new Alert();
    }

    public function index() {
        $today = date('Y-m-d');
        $tasks = $this->taskModel->getTasksByDate($today);
        $alerts = $this->alertModel->getNewAlerts();

        $stats = [
            'total' => count($tasks),
            'completed' => 0,
            'missed' => 0,
            'due' => 0
        ];

        foreach($tasks as $task) {
            if ($task->status == 'COMPLETED') {
                $stats['completed']++;
            } elseif ($task->status == 'MISSED') {
                $stats['missed']++;
            } elseif ($task->status == 'DUE') {
                $stats['due']++;
            }
        }

        $data = [
            'today_tasks' => $tasks,
            'new_alerts' => $alerts,
            'stats' => $stats
        ];
        $this->view('dashboard/index', $data);
    }

    public function view($view, $data = []) {
        if (file_exists('../views/' . $view . '.php')) {
            require_once '../views/' . $view . '.php';
        } else {
            error_redirect('View does not exist');
        }
    }
}
