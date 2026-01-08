<?php
require_once '../models/IrrigationSchedule.php';
require_once '../models/IrrigationArea.php';
require_once '../helpers/session_helper.php';

class IrrigationSchedulesController {
    private $scheduleModel;
    private $areaModel;

    public function __construct() {
        if (!isLoggedIn()) {
            header('location:../public/index.php?url=auth/login');
            exit();
        }
        $this->scheduleModel = new IrrigationSchedule();
        $this->areaModel = new IrrigationArea();
    }

    public function index() {
        $schedules = $this->scheduleModel->getAllSchedules();
        $data = [
            'schedules' => $schedules
        ];
        $this->view('irrigation_schedules/index', $data);
    }

    public function add() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Sanitize POST data
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_FULL_SPECIAL_CHARS);

            // Validate CSRF token
            if (!isset($_POST['csrf_token']) || !validate_csrf_token($_POST['csrf_token'])) {
                die('Invalid CSRF token');
            }

            $data = [
                'area_id' => trim($_POST['area_id']),
                'start_date' => trim($_POST['start_date']),
                'end_date' => trim($_POST['end_date']),
                'start_time' => trim($_POST['start_time']),
                'end_time' => trim($_POST['end_time']),
                'created_by' => $_SESSION['user_id'],
                'area_id_err' => '',
                'start_date_err' => '',
                'end_date_err' => '',
                'start_time_err' => '',
                'end_time_err' => ''
            ];

            // Validate data
            if (empty($data['area_id'])) {
                $data['area_id_err'] = 'Please select an area';
            }
            if (empty($data['start_date'])) {
                $data['start_date_err'] = 'Please enter a start date';
            }
            if (empty($data['end_date'])) {
                $data['end_date_err'] = 'Please enter an end date';
            }
            if (empty($data['start_time'])) {
                $data['start_time_err'] = 'Please enter a start time';
            }
            if (empty($data['end_time'])) {
                $data['end_time_err'] = 'Please enter an end time';
            }

            // Make sure no errors
            if (empty($data['area_id_err']) && empty($data['start_date_err']) && empty($data['end_date_err']) && empty($data['start_time_err']) && empty($data['end_time_err'])) {
                // Validated
                if ($this->scheduleModel->addSchedule($data)) {
                    log_audit('Created Irrigation Schedule', 'irrigation_schedules', $this->scheduleModel->getLastInsertId());
                    redirect('../public/index.php?url=irrigationschedules');
                } else {
                    error_redirect('Something went wrong');
                }
            } else {
                // Load view with errors
                $areas = $this->areaModel->getAllAreas();
                $data['areas'] = $areas;
                $this->view('irrigation_schedules/add', $data);
            }
        } else {
            $areas = $this->areaModel->getAllAreas();
            $data = [
                'areas' => $areas,
                'area_id' => '',
                'start_date' => '',
                'end_date' => '',
                'start_time' => '',
                'end_time' => '',
                'area_id_err' => '',
                'start_date_err' => '',
                'end_date_err' => '',
                'start_time_err' => '',
                'end_time_err' => ''
            ];
            $this->view('irrigation_schedules/add', $data);
        }
    }

    public function edit($id) {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Sanitize POST data
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_FULL_SPECIAL_CHARS);

            // Validate CSRF token
            if (!isset($_POST['csrf_token']) || !validate_csrf_token($_POST['csrf_token'])) {
                error_redirect('Invalid CSRF token');
            }

            $data = [
                'schedule_id' => $id,
                'area_id' => trim($_POST['area_id']),
                'start_date' => trim($_POST['start_date']),
                'end_date' => trim($_POST['end_date']),
                'start_time' => trim($_POST['start_time']),
                'end_time' => trim($_POST['end_time']),
                'area_id_err' => '',
                'start_date_err' => '',
                'end_date_err' => '',
                'start_time_err' => '',
                'end_time_err' => ''
            ];

            // Validate data
            if (empty($data['area_id'])) {
                $data['area_id_err'] = 'Please select an area';
            }
            if (empty($data['start_date'])) {
                $data['start_date_err'] = 'Please enter a start date';
            }
            if (empty($data['end_date'])) {
                $data['end_date_err'] = 'Please enter an end date';
            }
            if (empty($data['start_time'])) {
                $data['start_time_err'] = 'Please enter a start time';
            }
            if (empty($data['end_time'])) {
                $data['end_time_err'] = 'Please enter an end time';
            }

            // Make sure no errors
            if (empty($data['area_id_err']) && empty($data['start_date_err']) && empty($data['end_date_err']) && empty($data['start_time_err']) && empty($data['end_time_err'])) {
                // Validated
                if ($this->scheduleModel->updateSchedule($data)) {
                    log_audit('Updated Irrigation Schedule', 'irrigation_schedules', $id);
                    redirect('../public/index.php?url=irrigationschedules');
                } else {
                    error_redirect('Something went wrong');
                }
            } else {
                // Load view with errors
                $areas = $this->areaModel->getAllAreas();
                $data['areas'] = $areas;
                $this->view('irrigation_schedules/edit', $data);
            }
        } else {
            // Get existing schedule from model
            $schedule = $this->scheduleModel->getScheduleById($id);
            $areas = $this->areaModel->getAllAreas();

            $data = [
                'schedule_id' => $id,
                'areas' => $areas,
                'area_id' => $schedule->area_id,
                'start_date' => $schedule->start_date,
                'end_date' => $schedule->end_date,
                'start_time' => $schedule->start_time,
                'end_time' => $schedule->end_time,
                'area_id_err' => '',
                'start_date_err' => '',
                'end_date_err' => '',
                'start_time_err' => '',
                'end_time_err' => ''
            ];
            $this->view('irrigation_schedules/edit', $data);
        }
    }

    public function delete($id) {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Validate CSRF token
            if (!isset($_POST['csrf_token']) || !validate_csrf_token($_POST['csrf_token'])) {
                error_redirect('Invalid CSRF token');
            }

            if ($this->scheduleModel->deleteSchedule($id)) {
                log_audit('Deleted Irrigation Schedule', 'irrigation_schedules', $id);
                redirect('../public/index.php?url=irrigationschedules');
            } else {
                error_redirect('Something went wrong');
            }
        } else {
            redirect('../public/index.php?url=irrigationschedules');
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
