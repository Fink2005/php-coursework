<?php

class Auth extends Controller
{
    // Must have SayHi()
    function signIn()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Validate input
            $email = isset($_POST['email']) ? trim($_POST['email']) : '';
            $password = isset($_POST['password']) ? trim($_POST['password']) : '';

            if (empty($email) || empty($password)) {
                echo json_encode(['success' => false, 'message' => 'Email and password are required.']);
                return;
            }

            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                echo json_encode(['success' => false, 'message' => 'Invalid email format.']);
                return;
            }

            // Authenticate user
            $authModel = $this->model("AuthModel");
            $result = $authModel->signIn($email, $password);

            if ($result['status'] === 'success') {
                // Successful login
             
                echo json_encode([
                    'success' => true,
                    'message' => $result['message'],
                    'user' => $result['user'] // Return only non-sensitive user data
                ]);
            } else {
                // Failed login
                echo json_encode([
                    'success' => false,
                    'message' => $result['message']
                ]);
            }

        

        } else {
            // Initialize $result with a default value



            // Render the sign-in page
            $this->view("main", [
                "Page" => "AuthPage",
                "Auth" => "signIn",
            ]);
        }
    }

    function signUp() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Validate input
            $email = isset($_POST['email']) ? trim($_POST['email']) : '';
            $username = isset($_POST['username']) ? trim($_POST['username']) : '';
            $password = isset($_POST['password']) ? trim($_POST['password']) : '';

            if (empty($email) || empty($password)) {
                echo json_encode(['success' => false, 'message' => 'Email, name and password are required.']);
                return;
            }

            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                echo json_encode(['success' => false, 'message' => 'Invalid email format.']);
                return;
            }

            // Register user
            $authModel = $this->model("AuthModel");
            $result = $authModel->signUp($email, $password, $username);

            if ($result['status'] === 'success') {
                // Successful registration
                echo json_encode([
                    'success' => true,
                    'message' => $result['message'],
                ]);
            } else {
                // Failed registration
                echo json_encode([
                    'success' => false,
                    'message' => $result['message']
                ]);
            }
        } else {
            // Render the sign-up page
            $this->view("main", [
                "Page" => "AuthPage",
                "Auth" => "signUp",
            ]);
        }

    }
}
?>