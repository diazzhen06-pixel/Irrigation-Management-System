<?php
require_once '../models/User.php';
require_once '../helpers/session_helper.php';

class AuthController {
    private $userModel;

    public function __construct() {
        $this->userModel = new User();
    }

    public function login() {
        // Check for POST
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Sanitize POST data
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_FULL_SPECIAL_CHARS);

            // Validate CSRF token
            if (!isset($_POST['csrf_token']) || !validate_csrf_token($_POST['csrf_token'])) {
                error_redirect('Invalid CSRF token');
            }

            $data = [
                'username' => trim($_POST['username']),
                'password' => trim($_POST['password']),
                'username_err' => '',
                'password_err' => '',
            ];

            // Validate username
            if (empty($data['username'])) {
                $data['username_err'] = 'Please enter username';
            }

            // Validate Password
            if (empty($data['password'])) {
                $data['password_err'] = 'Please enter password';
            }

            // Check for user/username
            if ($this->userModel->findUserByUsername($data['username'])) {
                // User found
            } else {
                // User not found
                $data['username_err'] = 'No user found';
            }

            // Make sure errors are empty
            if (empty($data['username_err']) && empty($data['password_err'])) {
                // Validated
                // Check and set logged in user
                $loggedInUser = $this->userModel->login($data['username'], $data['password']);

                if ($loggedInUser) {
                    // Create Session
                    $this->createUserSession($loggedInUser);
                } else {
                    $data['password_err'] = 'Password incorrect';
                    $this->view('auth/login', $data);
                }
            } else {
                // Load view with errors
                $this->view('auth/login', $data);
            }
        } else {
            // Init data
            $data = [
                'username' => '',
                'password' => '',
                'username_err' => '',
                'password_err' => '',
            ];
            // Load view
            $this->view('auth/login', $data);
        }
    }

    public function createUserSession($user) {
        $_SESSION['user_id'] = $user->user_id;
        $_SESSION['user_username'] = $user->username;
        $_SESSION['user_name'] = $user->full_name;
        $_SESSION['user_role'] = $user->role_name;
        redirect('../public/index.php?url=dashboard');
    }

    public function logout() {
        unset($_SESSION['user_id']);
        unset($_SESSION['user_username']);
        unset($_SESSION['user_name']);
        unset($_SESSION['user_role']);
        session_destroy();
        redirect('../public/index.php?url=auth/login');
    }

    public function view($view, $data = []) {
        if (file_exists('../views/' . $view . '.php')) {
            require_once '../views/' . $view . '.php';
        } else {
            error_redirect('View does not exist');
        }
    }
}
