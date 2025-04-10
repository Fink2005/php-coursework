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
                // Verify hashed password
                if (password_verify($password, $user['password'])) {
                    return [
                        'status' => 'success',
                        'message' => 'Login successful',
                        'user' => $user['username'], 
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

    public function signUp($email,$password, $username, $permission_id = 1)
    {
        // Sanitize input
        $email = htmlspecialchars($email, ENT_QUOTES, 'UTF-8');
        $username = htmlspecialchars($username, ENT_QUOTES, 'UTF-8');

        try {
            // Check if email already exists
            $query = "SELECT * FROM users WHERE email = :email";
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

            // Insert new user into the database
            $query = "INSERT INTO users (email, username, password, permission_id) VALUES (:email, :username, :password, :permission_id)";
            $stmt = $this->con->prepare($query);
            $stmt->bindParam(':email', $email);
            $stmt->bindParam(':username', $username);
            $stmt->bindParam(':password', $hashedPassword);
            $stmt->bindParam(':permission_id', $permission_id);

            if ($stmt->execute()) {
                return [
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
                'message' => 'Database error during signup'
            ];
        }
    }
}