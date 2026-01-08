<?php
require_once '../models/IrrigationArea.php';
require_once '../helpers/session_helper.php';

class IrrigationAreasController {
    private $irrigationAreaModel;

    public function __construct() {
        if (!isLoggedIn() || $_SESSION['user_role'] !== 'Admin') {
            // Redirect to a different page or show an error
            header('location:../public/index.php?url=auth/login');
            exit();
        }
        $this->irrigationAreaModel = new IrrigationArea();
    }

    public function index() {
        $areas = $this->irrigationAreaModel->getAllAreas();
        $data = [
            'areas' => $areas
        ];
        $this->view('irrigation_areas/index', $data);
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
                'area_name' => trim($_POST['area_name']),
                'barangay_coverage' => trim($_POST['barangay_coverage']),
                'estimated_hectares' => trim($_POST['estimated_hectares']),
                'notes' => trim($_POST['notes']),
                'area_type' => trim($_POST['area_type']),
                'parent_id' => !empty($_POST['parent_id']) ? trim($_POST['parent_id']) : null,
                'area_name_err' => '',
                'estimated_hectares_err' => ''
            ];

            // Validate data
            if (empty($data['area_name'])) {
                $data['area_name_err'] = 'Please enter area name';
            }
            if (empty($data['estimated_hectares'])) {
                $data['estimated_hectares_err'] = 'Please enter estimated hectares';
            }

            // Make sure no errors
            if (empty($data['area_name_err']) && empty($data['estimated_hectares_err'])) {
                // Validated
                if ($this->irrigationAreaModel->addArea($data)) {
                    log_audit('Created Irrigation Area', 'irrigation_areas', $this->irrigationAreaModel->getLastInsertId());
                    redirect('../public/index.php?url=irrigationareas');
                } else {
                    error_redirect('Something went wrong');
                }
            } else {
                // Load view with errors
                $this->view('irrigation_areas/add', $data);
            }
        } else {
            $data = [
                'area_name' => '',
                'barangay_coverage' => '',
                'estimated_hectares' => '',
                'notes' => '',
                'area_type' => '',
                'parent_id' => null,
                'area_name_err' => '',
                'estimated_hectares_err' => ''
            ];
            $this->view('irrigation_areas/add', $data);
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
                'area_id' => $id,
                'area_name' => trim($_POST['area_name']),
                'barangay_coverage' => trim($_POST['barangay_coverage']),
                'estimated_hectares' => trim($_POST['estimated_hectares']),
                'notes' => trim($_POST['notes']),
                'area_type' => trim($_POST['area_type']),
                'parent_id' => !empty($_POST['parent_id']) ? trim($_POST['parent_id']) : null,
                'area_name_err' => '',
                'estimated_hectares_err' => ''
            ];

            // Validate data
            if (empty($data['area_name'])) {
                $data['area_name_err'] = 'Please enter area name';
            }
            if (empty($data['estimated_hectares'])) {
                $data['estimated_hectares_err'] = 'Please enter estimated hectares';
            }

            // Make sure no errors
            if (empty($data['area_name_err']) && empty($data['estimated_hectares_err'])) {
                // Validated
                if ($this->irrigationAreaModel->updateArea($data)) {
                    log_audit('Updated Irrigation Area', 'irrigation_areas', $id);
                    redirect('../public/index.php?url=irrigationareas');
                } else {
                    error_redirect('Something went wrong');
                }
            } else {
                // Load view with errors
                $this->view('irrigation_areas/edit', $data);
            }
        } else {
            // Get existing area from model
            $area = $this->irrigationAreaModel->getAreaById($id);

            $data = [
                'area_id' => $id,
                'area_name' => $area->area_name,
                'barangay_coverage' => $area->barangay_coverage,
                'estimated_hectares' => $area->estimated_hectares,
                'notes' => $area->notes,
                'area_type' => $area->area_type,
                'parent_id' => $area->parent_id,
                'area_name_err' => '',
                'estimated_hectares_err' => ''
            ];
            $this->view('irrigation_areas/edit', $data);
        }
    }

    public function delete($id) {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Validate CSRF token
            if (!isset($_POST['csrf_token']) || !validate_csrf_token($_POST['csrf_token'])) {
                error_redirect('Invalid CSRF token');
            }

            if ($this->irrigationAreaModel->deleteArea($id)) {
                log_audit('Deleted Irrigation Area', 'irrigation_areas', $id);
                redirect('../public/index.php?url=irrigationareas');
            } else {
                error_redirect('Something went wrong');
            }
        } else {
            redirect('../public/index.php?url=irrigationareas');
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
