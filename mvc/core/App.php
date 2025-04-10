<?php
class App {

    protected $controller = "Home";  // Default controller
    protected $action = "getPosts";  // Default action
    protected $params = [];  // Default parameters

    function __construct() {
        $arr = $this->UrlProcess();

        // Controller
        if (!empty($arr) && file_exists("./mvc/controllers/" . $arr[0] . ".php")) {
            $this->controller = $arr[0];
            unset($arr[0]);
        }

        require_once "./mvc/controllers/" . $this->controller . ".php";
        $this->controller = new $this->controller;

        // Action
        if (!empty($arr) && isset($arr[1]) && method_exists($this->controller, $arr[1])) {
            $this->action = $arr[1];
            unset($arr[1]);
        }

        // Params
        $this->params = !empty($arr) ? array_values($arr) : [];

        // Call the controller's method with the parameters
        call_user_func_array([$this->controller, $this->action], $this->params);
    }

    function UrlProcess() {
        if (isset($_GET["url"]) && !empty($_GET["url"])) {
            return explode("/", filter_var(trim($_GET["url"], "/"), FILTER_SANITIZE_URL));
        }
        return []; // Return an empty array if `$_GET["url"]` is not set
    }
}