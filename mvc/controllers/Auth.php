<?php
require_once __DIR__ . '/../../vendor/autoload.php';

use Google\Client;
use Google\Service\Oauth2;


class Auth extends Controller
{
    private $googleClient;

    private $googleClientId;
    private $googleSecret;
    private $googleRedirect;

    public function __construct()
    {
        $dotenv = Dotenv\Dotenv::createImmutable(dirname(__DIR__, 2));
        $dotenv->safeLoad();
        $this->googleClientId = $_ENV['GOOGLE_CLIENT_ID'] ?? '';
        $this->googleSecret = $_ENV['GOOGLE_CLIENT_SECRET'] ?? '';
        $this->googleRedirect = $_ENV['GOOGLE_REDIRECT_URI'] ?? '';

        if (!filter_var($this->googleRedirect, FILTER_VALIDATE_URL)) {
            die("GOOGLE_REDIRECT_URI is missing or not a valid absolute URL.");
        }
        

            isset($_SESSION["user"]) && header("Location: /course-work/Home");

        $this->googleClient = new Client();
        $this->googleClient->setClientId($this->googleClientId);
        $this->googleClient->setClientSecret($this->googleSecret);
        $this->googleClient->setRedirectUri($this->googleRedirect);
        $this->googleClient->addScope('email');
        $this->googleClient->addScope('profile');
    }

    public function signIn()
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
                $_SESSION['user'] = $result['user'];
                
                echo json_encode([
                    'success' => true,
                    'message' => $result['user']['permission_id'] === 1 ? $result['message'] :'Welcome back sir, King of the lord',
                    'user' => $result['user'] ?? [] // Return only non-sensitive user data
                ]);
            } else {
                // Failed login
                echo json_encode([
                    'success' => false,
                    'message' => $result['message']
                ]);
            }
        } else {
            // Render the sign-in page
       
            $googleLoginUrl = $this->googleClient->createAuthUrl();
            $this->view("main", [
                "Page" => "AuthPage",
                "Auth" => "signIn",
                "googleLoginUrl" => $googleLoginUrl
            ]);
        }
    }

    public function googleAuth()
    {
        if (isset($_GET['code'])) {
            $token = $this->googleClient->fetchAccessTokenWithAuthCode($_GET['code']);
            if (!isset($token['error'])) {
                $this->googleClient->setAccessToken($token['access_token']);
                $oauth2 = new Oauth2($this->googleClient);
                $userInfo = $oauth2->userinfo->get();

                // Extract user data
                $email = $userInfo->email;
                $name = $userInfo->name;
                $googleId = $userInfo->id;
                $avatar = $userInfo->picture;

                // Check or register user in your system
                $authModel = $this->model('AuthModel');
                $user = $authModel->googleLogin($email, $name, $avatar, $googleId);
                session_start();

                if ($user) {
                    $_SESSION['user'] = $user;
                    header("Location: /course-work/Home");
                exit;
                } else {
                    echo "<script>
                    sessionStorage.setItem('toastMessage', JSON.stringify({
                        message: 'Google login failed. Please try again.',
                        type: 'error'
                    }));
                    window.location.href = '/course-work/Auth/signIn';
                    </script>";
                }
            } else {
                echo "<script>
                sessionStorage.setItem('toastMessage', JSON.stringify({
                    message: 'Google login failed. Please try again.',
                    type: 'error'
                }));
                window.location.href = '/course-work/Auth/signIn';
                </script>";
                exit;
            }
        }
        else {
            // Redirect to sign-in page if no code is provided
            header("Location: /course-work/Auth/signUp");
            exit;
        }
    }

    public function signUp()

    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Validate input
            $email = trim($_POST['email'] ?? '');
            $username = trim($_POST['username'] ?? '');
            $password = trim($_POST['password'] ?? '');
            $role = trim($_POST['permission_id'] ?? 1);
            $status = trim($_POST['verify_status'] ?? 0);
    
    
    
            // Register user
            $authModel = $this->model("AuthModel");
            $result = $authModel->signUp($email, $password, $username, $role, $status);
    
            if ($result['status'] === 'success' ) {
                // If no user is logged in, send email
                if (empty($_SESSION["user"])) {
                    $emailResult = $this->model("EmailModel")->sendConfirmationEmail($result['email'], $result['token'] ?? '');
    
                    if ($emailResult === true) {
                        $message = 'Registration successful! Check your email to confirm.';
                        $_SESSION['isSignUp'] = true;
                        echo json_encode(['success' => true, 'message' => $message]);
                    } else {
                        echo json_encode(['success' => false, 'message' => $emailResult]);
                    }
                } else {
                    // Admin creating a new user
                    echo json_encode([
                        'success' => true,
                        'message' => 'User created successfully.'
                    ]);
                }
            }
            
            
            else {
                echo json_encode([
                    'success' => false,
                    'message' => $result['message']
                ]);
            }
        } else {
            // Render sign-up form
            $this->view("main", [
                "Page" => "AuthPage",
                "Auth" => "signUp",
            ]);
        }
    }


public function resendEmail() {
    $email = trim($_POST['email'] ?? '');
    $token = rand(100000, 999999);

    if (empty($_SESSION["user"])) {
        $emailResult = $this->model("EmailModel")->sendConfirmationEmail($email, $token);

        if ($emailResult === true) {
            $message = 'Registration successful! Check your email to confirm.';
            $_SESSION['isSignUp'] = true;
            echo json_encode(['success' => true, 'message' => $message]);
        } else {
            echo json_encode(['success' => false, 'message' => $emailResult]);
        }
    } 

}

    public function logOut()
    {
        // Unset all session variables
        $_SESSION = [];

        // Destroy the session cookie if exists
        if (ini_get("session.use_cookies")) {
            $params = session_get_cookie_params();
            setcookie(
                session_name(),
                '',
                time() - 42000,
                $params["path"],
                $params["domain"],
                $params["secure"],
                $params["httponly"]
            );
        }

        // Destroy the session
        session_destroy();

        // Redirect to login or home page
        header("Location: /course-work/Auth/signIn");
        exit;
    }

    public function confirm()
    {
        if (isset($_POST['token'])) {
            $token = $_POST['token'];
            $result = $this->model("AuthModel")->confirm($token);
            if ($result['success']) {
                echo json_encode([
                  "success" => $result['success'],
                  "message" => $result['message']
                ]);
            } else {
                echo json_encode([
                    "success" => $result['success'],
                    "message" => $result['message']
                  ]);
            }
        }
    }

    public function verifyMail()

    {
        $this->view("main", [
            "Page" => "AuthPage",
            "Auth" => isset($_SESSION["isSignUp"]) ? "verifyMail" : "signUp",
        ]);
    }
}
?>