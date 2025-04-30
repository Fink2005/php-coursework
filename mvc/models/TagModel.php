<?php
class TagModel extends DB {
    public function getTagsPaginated($limit = 6, $offset = 0) {
        $query = "SELECT tags.*, users.username as user_name
        FROM tags 
        INNER JOIN users ON users.id = tags.user_id
        ORDER BY tags.created_at DESC 
        LIMIT :limit OFFSET :offset";
        $stmt = $this->con->prepare($query);
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }


    public function createTag($data) {
        try {
            // Start the transaction
            $this->con->beginTransaction();

            // Insert tag
            $stmt = $this->con->prepare("
                INSERT INTO tags (name, description, user_id, created_at)
                VALUES (:name, :description, :user_id, NOW())
            ");
            $stmt->execute([
                ':name' => $data['name'],
                ':description' => $data['description'] ?: null,
                ':user_id' => (int)$data['user_id']
            ]);

            // Commit the transaction
            $this->con->commit();
            return true;
        } catch (Exception $e) {
            // Rollback on error
            $this->con->rollBack();
            error_log('Error creating tag: ' . $e->getMessage());
            throw $e;
        }
    }



    public function updateTag($tagId, $data) {
        try {
            // Ensure tagId is an integer
            $tagId = (int)$tagId;
            if ($tagId <= 0) {
                throw new Exception('Invalid tag ID: ' . $tagId);
            }

            // Start the transaction
            $this->con->beginTransaction();

            // Check if tag exists
            $checkStmt = $this->con->prepare("SELECT id FROM tags WHERE id = :id");
            $checkStmt->execute([':id' => $tagId]);
            if ($checkStmt->rowCount() === 0) {
                throw new Exception('Tag not found: ID ' . $tagId);
            }

            // Update tag
            $stmt = $this->con->prepare("
                UPDATE tags 
                SET name = :name, 
                    description = :description
                WHERE id = :id
            ");
            $stmt->execute([
                ':name' => $data['name'],
                ':description' => $data['description'] ?: null,
                ':id' => $tagId
            ]);

            // Log affected rows
            $affectedRows = $stmt->rowCount();
            error_log("Update tag ID $tagId: Affected rows = $affectedRows");

            // Commit the transaction
            $this->con->commit();
            return true;
        } catch (Exception $e) {
            // Rollback on error
            $this->con->rollBack();
            error_log('Error updating tag ID ' . $tagId . ': ' . $e->getMessage());
            throw $e;
        }
    }
    public function deleteTag($tagId) {
        try {
            // Ensure tagId is an integer
            $tagId = (int)$tagId;
            if ($tagId <= 0) {
                throw new Exception('Invalid tag ID: ' . $tagId);
            }

            // Start the transaction
            $this->con->beginTransaction();

            // Check if tag exists
            $checkStmt = $this->con->prepare("SELECT id FROM tags WHERE id = :id");
            $checkStmt->execute([':id' => $tagId]);
            if ($checkStmt->rowCount() === 0) {
                throw new Exception('Tag not found: ID ' . $tagId);
            }

            // Delete associated tags_posts entries
            $deleteTagStmt = $this->con->prepare("
                DELETE FROM tags_posts WHERE tag_id = :tag_id
            ");
            $deleteTagStmt->execute([':tag_id' => $tagId]);

            // Delete tag
            $deleteStmt = $this->con->prepare("
                DELETE FROM tags WHERE id = :id
            ");
            $deleteStmt->execute([':id' => $tagId]);

            // Log affected rows
            $affectedRows = $deleteStmt->rowCount();
            error_log("Delete tag ID $tagId: Affected rows = $affectedRows");

            // Commit the transaction
            $this->con->commit();
            return true;
        } catch (Exception $e) {
            // Rollback on error
            $this->con->rollBack();
            error_log('Error deleting tag ID ' . $tagId . ': ' . $e->getMessage());
            throw $e;
        }
    }

    public function getAllTags() {
        $stmt = $this->con->prepare("
        SELECT * from tags
    ");
    $stmt->execute();
    return $stmt->fetchAll();

    }


    public function searchTags($query) {
        try {
            $query = trim($query);
            // Return all tags if query is empty
            if ($query === '') {
                return $this->getAllTags();
            }
            $stmt = $this->con->prepare("
                SELECT id, name
                FROM tags
                WHERE name LIKE :query
                ORDER BY name
            ");
            $stmt->execute([':query' => "%$query%"]);
            $tags = $stmt->fetchAll(PDO::FETCH_ASSOC);
            error_log("Search tags with query '$query': Found " . count($tags) . " tags");
            return $tags;
        } catch (Exception $e) {
            error_log('Error searching tags: ' . $e->getMessage());
            throw $e;
        }
    }


    public function getTag($tagId) {
        try {
            $tagId = (int)$tagId;
            if ($tagId <= 0) {
                throw new Exception('Invalid tag ID: ' . $tagId);
            }
            $stmt = $this->con->prepare("
                SELECT t.id, t.name, t.description, t.user_id, u.username AS user_name, t.created_at
                FROM tags t
                LEFT JOIN users u ON t.user_id = u.id
                WHERE t.id = :id
            ");
            $stmt->execute([':id' => $tagId]);
            $tag = $stmt->fetch(PDO::FETCH_ASSOC);
            if (!$tag) {
                throw new Exception('Tag not found: ID ' . $tagId);
            }
            return $tag;
        } catch (Exception $e) {
            error_log('Error fetching tag ID ' . $tagId . ': ' . $e->getMessage());
            throw $e;
        }
    }

    public function getTotalTags() {
        $query = "SELECT COUNT(*) as total FROM tags";
        $stmt = $this->con->prepare($query);
        $stmt->execute();
        return (int)$stmt->fetch(PDO::FETCH_ASSOC)['total'];
    }
}


?>