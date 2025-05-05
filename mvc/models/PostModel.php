<?php
class PostModel extends DB {

    // public function getAllPosts() {
    //     $stmt = $this->con->prepare("
    //         SELECT 
    //             p.*, 
    //             u.username,
    //             u.avatar,
    //             GROUP_CONCAT(t.name) as tag_names
    //         FROM Posts p
    //         LEFT JOIN tags_posts pt ON pt.post_id = p.id
    //         LEFT JOIN tags t ON t.id = pt.tag_id
    //         JOIN users u ON p.user_id = u.id
    //         GROUP BY p.id
    //     ");
    //     $stmt->execute();
    //     $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

    //     // Process tag_names into an array
    //     foreach ($results as &$post) {
    //         $post['tags'] = !empty($post['tag_names']) ? explode(',', $post['tag_names']) : [];
    //         unset($post['tag_names']); // Remove temporary column
    //     }

    //     return $results;
    // }

    public function getAllPosts($user_id = null) {
        $query = "
            SELECT 
                p.*, 
                u.username,
                u.avatar,
                GROUP_CONCAT(t.name) as tag_names,
                COALESCE(SUM(CASE WHEN v.vote_type = 'upvote' THEN 1 ELSE 0 END), 0) as upvotes,
                COALESCE(SUM(CASE WHEN v.vote_type = 'downvote' THEN 1 ELSE 0 END), 0) as downvotes,
                v2.vote_type as user_vote_type
            FROM Posts p
            LEFT JOIN tags_posts pt ON pt.post_id = p.id
            LEFT JOIN tags t ON t.id = pt.tag_id
            JOIN users u ON p.user_id = u.id
            LEFT JOIN votes v ON v.post_id = p.id
            LEFT JOIN votes v2 ON v2.post_id = p.id AND v2.user_id = :user_id
            GROUP BY p.id
        ";
        $stmt = $this->con->prepare($query);
        $stmt->execute(['user_id' => $user_id ?: 0]); // Use 0 if no user_id to avoid null issues
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

        foreach ($results as &$post) {
            $post['tags'] = !empty($post['tag_names']) ? explode(',', $post['tag_names']) : [];
            unset($post['tag_names']);
            $post['upvotes'] = (int)$post['upvotes'];
            $post['downvotes'] = (int)$post['downvotes'];
        }

        return $results;
    }


    public function getPostsPaginated($limit = 6, $offset = 0) {
        $query = "SELECT 
                posts.*, 
                users.username,
                GROUP_CONCAT(t.name) as tag_names
            FROM posts
            LEFT JOIN tags_posts pt ON pt.post_id = posts.id
            LEFT JOIN tags t ON t.id = pt.tag_id
            JOIN users ON posts.user_id = users.id
            GROUP BY posts.id
            ORDER BY posts.created_at DESC 
            LIMIT :limit OFFSET :offset";
        $stmt = $this->con->prepare($query);
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Process tag_names into an array
        foreach ($results as &$post) {
            $post['tags'] = !empty($post['tag_names']) ? explode(',', $post['tag_names']) : [];
            unset($post['tag_names']); // Remove temporary column
        }

        return $results;
    }

    public function getTotalPosts() {
        $query = "SELECT COUNT(*) as total FROM posts";
        $stmt = $this->con->prepare($query);
        $stmt->execute();
        return (int)$stmt->fetch(PDO::FETCH_ASSOC)['total'];
    }


//here
    public function search($keyword) {
        $keyword = '%' . $keyword . '%'; // Wildcard for LIKE
        $query = "
            SELECT DISTINCT p.id, p.name, p.content, GROUP_CONCAT(t.name) as tags
            FROM posts p
            LEFT JOIN tags_post tp ON p.id = tp.post_id
            LEFT JOIN tags t ON tp.tag_id = t.id
            WHERE p.title LIKE :keyword
               OR p.content LIKE :keyword
               OR t.name LIKE :keyword
            GROUP BY p.id, p.title, p.content
            ORDER BY p.created_at DESC
        ";

        try {
            $stmt = $this->con->prepare($query);
            $stmt->bindParam(':keyword', $keyword, PDO::PARAM_STR);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            // Log error in production
            return [];
        }
    }

    public function getPostById($id, $user_id = null) {
        $query = "
            SELECT 
                p.*, 
                u.username, 
                GROUP_CONCAT(COALESCE(t.name, '')) as tag_names,
                COALESCE(SUM(CASE WHEN v.vote_type = 'upvote' THEN 1 ELSE 0 END), 0) as upvotes,
                COALESCE(SUM(CASE WHEN v.vote_type = 'downvote' THEN 1 ELSE 0 END), 0) as downvotes,
                v2.vote_type as user_vote_type
            FROM Posts p 
            LEFT JOIN tags_posts pt ON pt.post_id = p.id
            LEFT JOIN tags t ON t.id = pt.tag_id
            JOIN users u ON p.user_id = u.id
            LEFT JOIN votes v ON v.post_id = p.id
            LEFT JOIN votes v2 ON v2.post_id = p.id AND v2.user_id = :user_id
            WHERE p.id = :post_id
            GROUP BY p.id
        ";
        $stmt = $this->con->prepare($query);
        $stmt->execute(['post_id' => $id, 'user_id' => $user_id ?: 0]);
        $post = $stmt->fetch(PDO::FETCH_ASSOC);

        // Process tag_names into an array
        if ($post) {
            if (!empty($post['tag_names']) && is_string($post['tag_names'])) {
                // Split tag_names and filter out null/empty values
                $post['tags'] = array_filter(explode(',', $post['tag_names']), function($tag) {
                    return $tag !== null && $tag !== '' && is_string($tag);
                });
            } else {
                $post['tags'] = [];
            }
            unset($post['tag_names']); // Remove temporary column
            $post['upvotes'] = (int)$post['upvotes'];
            $post['downvotes'] = (int)$post['downvotes'];
        }

        return $post ?: []; // Return empty array if no post found
    }

    public function updatePost($postId, $data) {
        try {
            // Ensure postId is an integer
            $postId = (int)$postId;
            if ($postId <= 0) {
                throw new Exception('Invalid post ID: ' . $postId);
            }
    
            // Start the transaction
            $this->con->beginTransaction();
    
            // Check if post exists
            $checkStmt = $this->con->prepare("SELECT id FROM Posts WHERE id = :id");
            $checkStmt->execute([':id' => $postId]);
            if ($checkStmt->rowCount() === 0) {
                throw new Exception('Post not found: ID ' . $postId);
            }
    
            // Update post
            $postStmt = $this->con->prepare("
                UPDATE Posts 
                SET name = :name, 
                    image_url = :image_url, 
                    post_url = :post_url, 
                    des = :des, 
                    user_id = :user_id
                WHERE id = :id
            ");
            $postStmt->execute([
                ':name' => $data['name'],
                ':image_url' => $data['image_url'] ?: null,
                ':post_url' => $data['post_url'] ?: null,
                ':des' => $data['des'] ?: null,
                ':user_id' => (int)$data['user_id'],
                ':id' => $postId
            ]);
    
            // Log affected rows
            $affectedRows = $postStmt->rowCount();
            error_log("Update post ID $postId: Affected rows = $affectedRows");
    
            // Delete existing tags
            $deleteTagStmt = $this->con->prepare("
                DELETE FROM tags_posts WHERE post_id = :post_id
            ");
            $deleteTagStmt->execute([':post_id' => $postId]);
    
            // Insert new tags (if any)
            if (!empty($data['tags'])) {
                $tagStmt = $this->con->prepare("
                    INSERT INTO tags_posts (post_id, tag_id)
                    VALUES (:post_id, :tag_id)
                ");
                foreach ($data['tags'] as $tagId) {
                    $tagId = (int)$tagId;
                    if ($tagId <= 0) {
                        throw new Exception('Invalid tag ID: ' . $tagId);
                    }
    
                    // Validate tag exists
                    $tagCheckStmt = $this->con->prepare("SELECT id FROM tags WHERE id = :tag_id");
                    $tagCheckStmt->execute([':tag_id' => $tagId]);
                    if ($tagCheckStmt->rowCount() === 0) {
                        throw new Exception('Tag not found: ID ' . $tagId);
                    }
    
                    $tagStmt->execute([
                        ':post_id' => $postId,
                        ':tag_id' => $tagId
                    ]);
                }
            }
    
            // Commit the transaction
            $this->con->commit();
            return true;
        } catch (Exception $e) {
            // Rollback on error
            $this->con->rollBack();
            error_log('Error updating post ID ' . $postId . ': ' . $e->getMessage());
            throw $e;
        }
    }

    public function createPost($data) {
        try {
            // Start the transaction
            $this->con->beginTransaction();
    
            // Insert post
            $postStmt = $this->con->prepare("
                INSERT INTO Posts (name, image_url, post_url, des, user_id, created_at)
                VALUES (:name, :image_url, :post_url, :des, :user_id, NOW())
            ");
            $postStmt->execute([
                ':name' => $data['name'],
                ':image_url' => $data['image_url'] ,
                ':post_url' => $data['post_url'] ?: null,
                ':des' => $data['des'] ?: null,
                ':user_id' => $data['user_id']
            ]);
    
            // Get the inserted post ID
            $postId = $this->con->lastInsertId();
    
            // Insert tags (if any)
            if (!empty($data['tags'])) {
                $tagStmt = $this->con->prepare("
                    INSERT INTO tags_posts (post_id, tag_id)
                    VALUES (:post_id, :tag_id)
                ");
                foreach ($data['tags'] as $tagId) {
                    if (!is_numeric($tagId) || $tagId <= 0) {
                        throw new Exception('Invalid tag ID: ' . $tagId);
                    }
                    $tagStmt->execute([
                        ':post_id' => $postId,
                        ':tag_id' => (int)$tagId
                    ]);
                }
            }
    
            // Commit the transaction
            $this->con->commit();
            return true;
        } catch (Exception $e) {
            // Rollback on error
            $this->con->rollBack();
            error_log('Error creating post: ' . $e->getMessage());
            throw $e;
        }
    }

    public function deletePost($postId) {
        try {
            // Ensure postId is an integer
            $postId = (int)$postId;
            if ($postId <= 0) {
                throw new Exception('Invalid post ID: ' . $postId);
            }

            // Start the transaction
            $this->con->beginTransaction();

            // Check if post exists
            $checkStmt = $this->con->prepare("SELECT id FROM Posts WHERE id = :id");
            $checkStmt->execute([':id' => $postId]);
            if ($checkStmt->rowCount() === 0) {
                throw new Exception('Post not found: ID ' . $postId);
            }

            // Delete associated tags
            $deleteTagStmt = $this->con->prepare("
                DELETE FROM tags_posts WHERE post_id = :post_id
            ");
            $deleteTagStmt->execute([':post_id' => $postId]);

            // Delete post
            $deletePostStmt = $this->con->prepare("
                DELETE FROM Posts WHERE id = :id
            ");
            $deletePostStmt->execute([':id' => $postId]);

            // Log affected rows
            $affectedRows = $deletePostStmt->rowCount();
            error_log("Delete post ID $postId: Affected rows = $affectedRows");

            // Commit the transaction
            $this->con->commit();
            return true;
        } catch (Exception $e) {
            // Rollback on error
            $this->con->rollBack();
            error_log('Error deleting post ID ' . $postId . ': ' . $e->getMessage());
            throw $e;
        }
    }
}