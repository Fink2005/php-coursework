<?php
// ./mvc/models/AuthModel.php
class AuthModel extends DB
{
    public function signIn($email, $password)
    {
        // Sanitize input
        $email = htmlspecialchars($email, ENT_QUOTES, 'UTF-8');

        try {
            // Prepare SQL query to fetch user by email
            $query = "SELECT * FROM users WHERE email = :email";
            $stmt = $this->con->prepare($query); // Use consistent $this->conn
            $stmt->bindParam(':email', $email);
            $stmt->execute();

            // Fetch user data
            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($user) {
                if (!$user['verify_status']) {
                    return ['status' => 'error', 'message' => 'Please verify your email before signing in.'];
                }
                // Verify hashed password
                if (password_verify($password, $user['password'])) {
                    return [
                        'status' => 'success',
                        'message' => "Login successful, hello " . $user['username'],
                        'user' => [
                            'id' => $user['id'],
                            'permission_id' => $user['permission_id'],
                            'username' => $user['username'],    
                            'email' => $user['email'],    
                            'avatar' => $user['avatar'],

                        ]
                    ];
                } else {
                    return [
                        'status' => 'error',
                        'message' => 'Invalid password'
                    ];
                }
            } else {
                return [
                    'status' => 'error',
                    'message' => 'User not found'
                ];
            }
        } catch (PDOException $e) {
            error_log("SignIn DB error: " . $e->getMessage());
            return [
                'status' => 'error',
                'message' => 'Database error during login'
            ];
        }
    }

    public function googleLogin($email, $name,$avatar, $googleId) {
        // Check if user exists by google_id
        $query = "SELECT id, username, email, permission_id, verify_status, avatar FROM users WHERE google_id = :googleId LIMIT 1";
        $stmt = $this->con->prepare($query);
        $stmt->bindParam(':googleId', $googleId);
        $stmt->execute();
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user) {
            // User exists with this google_id, return user data
            if (!$user['verify_status']) {
                $updateQuery = "UPDATE users SET verify_status = TRUE WHERE google_id = :googleId";
                $updateStmt = $this->con->prepare($updateQuery);
                $updateStmt->bindParam(':googleId', $googleId);
                $updateStmt->execute();
            }
            
            return [
                'id' => $user['id'],
                'username' => $user['username'],
                'email' => $user['email'],
                'avatar' => $user['avatar'],
                'permission_id' => $user['permission_id']
            ];
        } else {
            // Check if email exists (for linking existing accounts or avoiding duplicates)
            $emailQuery = "SELECT id FROM users WHERE email = :email LIMIT 1";
            $emailStmt = $this->con->prepare($emailQuery);
            $emailStmt->bindParam(':email', $email);
            $emailStmt->execute();
            if ($emailStmt->fetch()) {
                // Email exists, link Google ID to existing account
                $updateQuery = "UPDATE users SET google_id = :googleId, verify_status = TRUE WHERE email = :email";
                $updateStmt = $this->con->prepare($updateQuery);
                $updateStmt->bindParam(':googleId', $googleId);
                $updateStmt->bindParam(':email', $email);
                $updateStmt->execute();

                $query = "SELECT id, username, email, permission_id, avatar FROM users WHERE email = :email LIMIT 1";
                $stmt = $this->con->prepare($query);
                $stmt->bindParam(':email', $email);
                $stmt->execute();
                $user = $stmt->fetch(PDO::FETCH_ASSOC);
                return [
                    'id' => $user['id'],
                    'username' => $user['username'],
                    'email' => $user['email'],
                    'avatar' => $user['avatar'],
                    'permission_id' => $user['permission_id']
                ];
            } else {
                // Register new user
                $query = "INSERT INTO users (username, email, avatar, google_id, permission_id, verify_status) 
                          VALUES (:username, :email, :avatar, :googleId, 1, TRUE)";
                $stmt = $this->con->prepare($query);
                $stmt->bindParam(':username', $name);
                $stmt->bindParam(':email', $email);
                $stmt->bindParam(':avatar', $avatar);
                $stmt->bindParam(':googleId', $googleId);
                if ($stmt->execute()) {
                    return [
                        'id' => $this->con->lastInsertId(),
                        'username' => $name,
                        'avatar' =>  $avatar,
                        'email' => $email,
                        'permission_id' => 1
                    ];
                }
                return false;
            }
        }
    }


    public function signUp($email, $password, $username, $permission_id, $status)
    {
        $token = rand(100000, 999999);
    
        try {
            // Check if email already exists
            $query = "SELECT 1 FROM users WHERE email = :email LIMIT 1";
            $stmt = $this->con->prepare($query);
            $stmt->bindParam(':email', $email);
            $stmt->execute();
    
            if ($stmt->rowCount() > 0) {
                return [
                    'status' => 'error',
                    'message' => 'Email is already registered.'
                ];
            }
    
            // Hash the password
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
    
            // Build query based on status
            if ($status != 0) {
                $query = "INSERT INTO users (email, username, password, permission_id, verify_status) 
                          VALUES (:email, :username, :password, :permission_id, :status)";
            } else {
                $query = "INSERT INTO users (email, username, password, permission_id, verify_token, verify_status) 
                          VALUES (:email, :username, :password, :permission_id, :token, :status)";
            }
    
            $stmt = $this->con->prepare($query);
            $stmt->bindParam(':email', $email);
            $stmt->bindParam(':username', $username);
            $stmt->bindParam(':password', $hashedPassword);
            $stmt->bindParam(':permission_id', $permission_id);
            $stmt->bindParam(':status', $status);
    
            if ($status == 0) {
                $stmt->bindParam(':token', $token);
            }
    
            if ($stmt->execute()) {
                return [
                    'email' => $email,
                    'token' => $status == 0 ? $token : null,
                    'status' => 'success',
                    'message' => 'User registered successfully.'
                ];
            } else {
                return [
                    'status' => 'error',
                    'message' => 'Failed to register user.'
                ];
            }
        } catch (PDOException $e) {
            error_log("SignUp DB error: " . $e->getMessage());
            return [
                'status' => 'error',
                'message' => $e->getMessage()
            ];
        }
    }
    

    public function confirm($token) {
        try {
            $query = "UPDATE users SET verify_status = TRUE, verify_token = NULL WHERE verify_token = :token";
            $stmt = $this->con->prepare($query);
            $stmt->bindParam(':token', $token);
    
            if ($stmt->execute()) {
                // Check if any rows were updated
                if ($stmt->rowCount() > 0) {
                    return [
                        "success" => true,
                        "message" => "Email confirmed successfully. You can now sign in."
                    ];
                } else {
                    return [
                        "success" => false,
                        "message" => "Invalid verification code."
                    ];
                }
            } else {
                return [
                    "success" => false,
                    "message" => "Failed to execute the query."
                ];
            }
        } catch (PDOException $e) {
            // Handle exception
            error_log("Database error: " . $e->getMessage());
            return [
                "success" => false,
                "message" => "An error occurred while processing your request."
            ];
        }
    }
}