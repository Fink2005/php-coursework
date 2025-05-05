<?php

class UserModel extends DB {
        public function getUsersPaginated($limit = 6, $offset = 0) {
        $query = "SELECT id, username , email, permission_id, verify_status, created_at FROM users 
                  ORDER BY id DESC LIMIT :limit OFFSET :offset";
        $stmt = $this->con->prepare($query);
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }


    public function getUserById($id) {
        $query = "SELECT id,avatar, username, email, permission_id, verify_status, created_at FROM users WHERE id = :id";
        $stmt = $this->con->prepare($query);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }


    // public function updateUser($id, $data = []) {
    //     if (empty($data)) {
    //         return ['status' => false, 'message' => 'No data provided'];
    //     }
    
    //     // Define allowed fields (excluding password, as it will be handled separately)
    //     $allowedFields = ['username', 'email', 'permission_id', 'verify_status'];
    //     $setParts = [];
    //     $params = [':id' => $id];
    
    //     // Build query parts and parameters for allowed fields
    //     foreach ($allowedFields as $field) {
    //         if (isset($data[$field]) && $data[$field] !== '') {
    //             $setParts[] = "$field = :$field";
    //             $params[":$field"] = $data[$field];
    //         }
    //     }
    
    //     // Handle password update if both password and newPassword are provided
    //     if (isset($data['password']) && isset($data['newPassword']) && $data['newPassword'] !== '') {
    //         try {
    //             // Verify current password
    //             $stmt = $this->con->prepare("SELECT password FROM users WHERE id = :id");
    //             $stmt->execute([':id' => $id]);
    //             $user = $stmt->fetch(PDO::FETCH_ASSOC);
    
    //             if (!$user || !password_verify($data['password'], $user['password'])) {
    //                 return ['success' => false, 'message' => 'Invalid current password'];
    //             }
    
    //             // If password is verified, add hashed newPassword to the update
    //             $setParts[] = "password = :newPassword";
    //             $params[':newPassword'] = password_hash($data['newPassword'], PASSWORD_DEFAULT);
    //         } catch (PDOException $e) {
    //             return ['success' => false, 'message' => 'Database error: ' . $e->getMessage()];
    //         }
    //     } elseif (isset($data['newPassword']) && !isset($data['password'])) {
    //         // If newPassword is provided without password, reject the update
    //         return ['success' => false, 'message' => 'Current password is required to set a new password'];
    //     }
    
    //     if (empty($setParts)) {
    //         return ['success' => false, 'message' => 'No valid fields to update'];
    //     }
    
    //     // Build and execute the update query
    //     try {
    //         $setClause = implode(', ', $setParts);
    //         $query = "UPDATE users SET $setClause WHERE id = :id";
    //         $stmt = $this->con->prepare($query);
    
    //         foreach ($params as $param => $value) {
    //             $stmt->bindValue($param, $value);
    //         }
    
    //         if ($stmt->execute()) {
    //             return ['success' => true, 'message' => 'User updated successfully', 'user_id' => $id];
    //         } else {
    //             return ['success' => false, 'message' => 'Failed to update user'];
    //         }
    //     } catch (PDOException $e) {
    //         return ['success' => false, 'message' => 'Database error: ' . $e->getMessage()];
    //     }
    // }



    public function updateUser($id, $data = []) {

        // Define allowed fields (excluding password and avatar)
        $allowedFields = ['username', 'email', 'permission_id', 'verify_status','avatar'];
        $setParts = [];
        $params = [':id' => $id];

        // Build query parts for allowed fields
        foreach ($allowedFields as $field) {
            if (array_key_exists($field, $data)) {
                // Allow null for permission_id and verify_status
                if (in_array($field, ['permission_id', 'verify_status']) && $data[$field] === null) {
                    $setParts[] = "$field = NULL";
                } elseif ($data[$field] !== '') {
                    $setParts[] = "$field = :$field";
                    $params[":$field"] = $data[$field];
                }
            }
        }

        // Handle avatar upload


        // Handle password update
        if (isset($data['password']) && isset($data['newPassword']) && $data['newPassword'] !== '') {
            try {
                $stmt = $this->con->prepare("SELECT password FROM users WHERE id = :id");
                $stmt->execute([':id' => $id]);
                $user = $stmt->fetch(PDO::FETCH_ASSOC);

                if (!$user || !password_verify($data['password'], $user['password'])) {
                    return ['success' => false, 'message' => 'Invalid current password'];
                }

                $setParts[] = "password = :newPassword";
                $params[':newPassword'] = password_hash($data['newPassword'], PASSWORD_DEFAULT);
            } catch (PDOException $e) {
                return ['success' => false, 'message' => 'Database error: ' . $e->getMessage()];
            }
        } elseif (isset($data['newPassword']) && !isset($data['password'])) {
            return ['success' => false, 'message' => 'Current password is required to set a new password'];
        }

        if (empty($setParts)) {
            return ['success' => false, 'message' => 'No valid fields to update'];
        }

        // Execute update query
        try {
            $setClause = implode(', ', $setParts);
            $query = "UPDATE users SET $setClause WHERE id = :id";
            $stmt = $this->con->prepare($query);

            foreach ($params as $param => $value) {
                $stmt->bindValue($param, $value);
            }

            if ($stmt->execute()) {
                // Fetch updated user data
                $stmt = $this->con->prepare("SELECT id, username, email, avatar, permission_id FROM users WHERE id = :id");
                $stmt->execute([':id' => $id]);
                $user = $stmt->fetch(PDO::FETCH_ASSOC);

                if ($user) {
                    // Update session
                    return [
                        'success' => true,
                        'message' => 'User updated successfully',
                        'user_id' => $id,
                        'data' => [
                            'username' => $user['username'],
                            'email' => $user['email'],
                            'avatar' => $user['avatar']
                        ]
                    ];
                }
                return ['success' => false, 'message' => 'Failed to fetch updated user data'];
            }
            return ['success' => false, 'message' => 'Failed to update user'];
        } catch (PDOException $e) {
            return ['success' => false, 'message' => 'Database error: ' . $e->getMessage()];
        }
    }




    public function deleteUser($id) {
        try {
            $stmt = $this->con->prepare('DELETE FROM users WHERE id = :id');
            $stmt->execute([':id' => $id]);
    
            if ($stmt->rowCount() > 0) {
                return ['status' => true, 'message' => 'User and related data deleted successfully'];
            } else {
                return ['status' => false, 'message' => 'User not found'];
            }
        } catch (PDOException $e) {
            return ['status' => 'error', 'message' => 'Database error: ' . $e->getMessage()];
        }
    }
    public function getTotalUsers() {
        $query = "SELECT COUNT(*) as total FROM users";
        $stmt = $this->con->prepare($query);
        $stmt->execute();
        return (int)$stmt->fetch(PDO::FETCH_ASSOC)['total'];
    }
    
}