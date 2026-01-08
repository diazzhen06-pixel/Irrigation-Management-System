<?php
require_once '../models/IrrigationTask.php';
require_once '../helpers/session_helper.php';

class IrrigationTasksController {
    private $taskModel;

    public function __construct() {
        if (!isLoggedIn()) {
            header('location:../public/index.php?url=auth/login');
            exit();
        }
        $this->taskModel = new IrrigationTask();
    }

    public function index() {
        $date = date('Y-m-d');
        if (isset($_GET['date'])) {
            $date = $_GET['date'];
        }

        $tasks = $this->taskModel->getTasksByDate($date);
        $data = [
            'tasks' => $tasks,
            'date' => $date
        ];
        $this->view('irrigation_tasks/index', $data);
    }

    public function log($id) {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Sanitize POST data
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_FULL_SPECIAL_CHARS);

            // Validate CSRF token
            if (!isset($_POST['csrf_token']) || !validate_csrf_token($_POST['csrf_token'])) {
                die('Invalid CSRF token');
            }

            $data = [
                'task_id' => $id,
                'user_id' => $_SESSION['user_id'],
                'actual_start_time' => trim($_POST['actual_start_time']),
                'actual_end_time' => trim($_POST['actual_end_time']),
                'area_served' => trim($_POST['area_served']),
                'issues_encountered' => trim($_POST['issues_encountered']),
                'remarks' => trim($_POST['remarks']),
                'cancellation_reason' => trim($_POST['cancellation_reason']),
                'status' => trim($_POST['status']),
                'status_err' => ''
            ];

            // Validate status
            if (empty($data['status'])) {
                $data['status_err'] = 'Please select a status';
            }

            if (empty($data['status_err'])) {
                // Update task status
                $this->taskModel->updateTaskStatus($id, $data['status']);

                // Log the activity
                if ($this->taskModel->logActivity($data)) {
                    log_audit('Logged Task Activity', 'irrigation_tasks', $id);
                    redirect('../public/index.php?url=irrigationtasks');
                } else {
                    error_redirect('Something went wrong');
                }
            } else {
                // Load view with errors
                $task = $this->taskModel->getTaskById($id);
                $data['task'] = $task;
                $this->view('irrigation_tasks/log', $data);
            }
        } else {
            $task = $this->taskModel->getTaskById($id);
            $data = [
                'task' => $task,
                'actual_start_time' => '',
                'actual_end_time' => '',
                'area_served' => '',
                'issues_encountered' => '',
                'remarks' => '',
                'cancellation_reason' => '',
                'status' => '',
                'status_err' => ''
            ];
            $this->view('irrigation_tasks/log', $data);
        }
    }

    public function view($view, $data = []) {
        if (file_exists('../views/' . $view . '.php')) {
            require_once '../views/' . $view . '.php';
        } else {
            error_redirect('View does not exist');
        }
    }
}
