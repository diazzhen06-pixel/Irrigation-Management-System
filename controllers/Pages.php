<?php
class Pages {
    public function index() {
        // This is the default homepage
        $this->view('dashboard/index');
    }

    public function error($message = 'An unknown error occurred.') {
        $data = ['message' => $message];
        $this->view('error', $data);
    }

    public function view($view, $data = []) {
        if (file_exists('../views/' . $view . '.php')) {
            require_once '../views/' . $view . '.php';
        } else {
            die('View does not exist');
        }
    }
}
