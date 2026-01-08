<?php
require_once '../helpers/session_helper.php';
require_once '../helpers/audit_helper.php';

// A simple router
class Core {
    protected $currentController = 'Pages';
    protected $currentMethod = 'index';
    protected $params = [];

    public function __construct() {
        $url = $this->getUrl();

        // Look in controllers for first value
        if (isset($url[0]) && file_exists('../controllers/' . ucwords($url[0]) . '.php')) {
            // If exists, set as controller
            $this->currentController = ucwords($url[0]);
            // Unset 0 Index
            unset($url[0]);
        }

        // Require the controller
        require_once '../controllers/' . $this->currentController . '.php';

        // Instantiate controller class
        $this->currentController = new $this->currentController;

        // Check for second part of url
        if (isset($url[1])) {
            // Check to see if method exists in controller
            if (method_exists($this->currentController, $url[1])) {
                $this->currentMethod = $url[1];
                // Unset 1 index
                unset($url[1]);
            }
        }

        // Get params
        $this->params = $url ? array_values($url) : [];

        // Role-based access control
        $this->checkAccess();

        // Call a callback with array of params
        call_user_func_array([$this->currentController, $this->currentMethod], $this->params);
    }

    public function getUrl() {
        if (isset($_GET['url'])) {
            $url = rtrim($_GET['url'], '/');
            $url = filter_var($url, FILTER_SANITIZE_URL);
            $url = explode('/', $url);
            return $url;
        }
        return [];
    }

    public function checkAccess() {
        $publicRoutes = [
            'AuthController' => ['login']
        ];

        $controllerName = get_class($this->currentController);

        if (isset($publicRoutes[$controllerName]) && in_array($this->currentMethod, $publicRoutes[$controllerName])) {
            return;
        }

        if (!isLoggedIn()) {
            header('location: index.php?url=auth/login');
            exit();
        }
    }


}

$init = new Core();

// Helper functions
function view($view, $data = []) {
    if (file_exists('../views/' . $view . '.php')) {
        require_once '../views/' . $view . '.php';
    } else {
        die('View does not exist');
    }
}

function redirect($url) {
    header('location: ' . $url);
    exit();
}

function error_redirect($message = 'An unknown error occurred.') {
    redirect('index.php?url=pages/error/' . urlencode($message));
}
