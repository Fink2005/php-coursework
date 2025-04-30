<?php

class App {
    protected $controller = "Home";  // Default controller
    protected $action = "getPosts";  // Default action
    protected $params = [];  // Default parameters

    function __construct() {
        // Configure session cookie parameters
        session_set_cookie_params([
            'lifetime' => 0,
            'path' => '/',
            'domain' => '',
            'secure' => false, // Set to true for HTTPS in production
            'httponly' => true,
            'samesite' => 'Lax'
        ]);
        session_start();

        $arr = $this->UrlProcess();
        // Define protected routes and their role requirements
        $protectedRoutes = [
            'Home/getPosts' => ['admin', 'user'], // Homepage for both roles
            'home' => ['admin', 'user'], // Homepage for both roles
            'home/getPosts' => ['admin', 'user'], // Homepage for both roles
            '' => ['admin', 'user'], // Homepage for both roles
            'Home' => ['admin', 'user'], // Homepage for both roles
            'Home/CreatePosts' => ['admin', 'user'], // Homepage for both roles
            'Admin/getUsers' => ['admin'], // Admin-only route
            'Admin/Dashboard' => ['admin'], // Admin-only route
            'Admin/Users' => ['admin'] // Admin-only route
        ];

        // Extract controller and action for middleware check
        $controller = !empty($arr) && isset($arr[0]) ? $arr[0] : $this->controller;
        $action = !empty($arr) && isset($arr[1]) ? $arr[1] : $this->action;
        $routeKey = $controller . '/' . $action;

        // Handle root URL (/)
        if (empty($arr)) {
            $routeKey = 'Home/getPosts';
            $controller = $this->controller;
            $action = $this->action;
        }

        // Apply middleware for protected routes
        if (array_key_exists($routeKey, $protectedRoutes)) {
            Middleware::handle($protectedRoutes[$routeKey]);
        }

        // Check if controller exists
        if (!file_exists("./mvc/controllers/" . $controller . ".php")) {
            error_log("404 Error: Controller '$controller' not found for URL: " . ($_GET['url'] ?? '/'));
            $this->render404();
            return;
        }

        require_once "./mvc/controllers/" . $controller . ".php";
        if (!class_exists($controller)) {
            error_log("404 Error: Controller class '$controller' not found for URL: " . ($_GET['url'] ?? '/'));
            $this->render404();
            return;
        }
        $this->controller = new $controller;

        // Check if action exists
        if (!method_exists($this->controller, $action)) {
            error_log("404 Error: Action '$action' not found in controller '$controller' for URL: " . ($_GET['url'] ?? '/'));
            $this->render404();
            return;
        }

        // Set action and parameters    
        $this->action = $action;
        $this->params = !empty($arr) && count($arr) > 2 ? array_slice($arr, 2) : [];

        // Call the controller's method with the parameters
        call_user_func_array([$this->controller, $this->action], $this->params);
    }

    function UrlProcess() {
        if (isset($_GET["url"]) && !empty($_GET["url"])) {
            return explode("/", filter_var(trim($_GET["url"], "/"), FILTER_SANITIZE_URL));
        }
        return [];
    }

    protected function render404() {
        http_response_code(404);
        include "./mvc/views/notFound/notFound.php";
        exit;
    }
}
?>