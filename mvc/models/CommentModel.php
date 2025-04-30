<?php
class CommentModel extends DB
{
    public function addComment($postId, $data) {
        $content = $data['content'] ?? null;
        $userId = $_SESSION['user']['id'] ?? null;
        $imageUrl = $data['image_url'] ?? null;
    
        if (!$content || !$userId) {
            return [
                'success' => false,
                'message' => 'Missing required fields: content or user ID'
            ];
        }
    
        try {
            $query = "INSERT INTO comments (post_id, user_id, content, image_url) VALUES (:postId, :userId, :content, :imageUrl)";
            $stmt = $this->con->prepare($query);
            $stmt->bindParam(':postId', $postId, PDO::PARAM_INT);
            $stmt->bindParam(':userId', $userId, PDO::PARAM_INT);
            $stmt->bindParam(':content', $content, PDO::PARAM_STR);
            $stmt->bindParam(':imageUrl', $imageUrl, PDO::PARAM_STR);
            $stmt->execute();
            return [
                'success' => true,
                'message' => 'Comment added successfully'
            ];
        } catch (PDOException $e) {
            error_log("AddComment DB error: " . $e->getMessage());
            return [
                'success' => false,
                'message' => 'Database error while adding comment: ' . $e->getMessage()
            ];
        }
    }

    public function getAllComments($postId)
    {
        try {
            $query = "SELECT c.*, u.username, u.avatar
                     FROM comments c 
                     JOIN users u ON c.user_id = u.id 
                     WHERE c.post_id = :postId 
                     ORDER BY c.created_at DESC";
            $stmt = $this->con->prepare($query);
            $stmt->bindParam(':postId', $postId, PDO::PARAM_INT);
            $stmt->execute();
            $comments = $stmt->fetchAll(PDO::FETCH_ASSOC);

            return [
                'status' => 'success',
                'data' => $comments,
                'message' => 'Comments retrieved successfully'
            ];
        } catch (PDOException $e) {
            error_log("GetAllComments DB error: " . $e->getMessage());
            return [
                'status' => 'error',
                'message' => 'Database error while retrieving comments'
            ];
        }
    }

    public function getCommentsPaginated($postId, $page, $limit )
    {
        try {
            $offset = ($page - 1) * $limit;

            $countQuery = "SELECT COUNT(*) as total 
                          FROM comments 
                          WHERE post_id = :postId";
            $countStmt = $this->con->prepare($countQuery);
            $countStmt->bindParam(':postId', $postId, PDO::PARAM_INT);
            $countStmt->execute();
            $totalComments = $countStmt->fetch(PDO::FETCH_ASSOC)['total'];

            $query = "SELECT c.*, u.username, u.avatar
                     FROM comments c 
                     JOIN users u ON c.user_id = u.id 
                     WHERE c.post_id = :postId 
                     ORDER BY c.created_at DESC 
                     LIMIT :limit OFFSET :offset";
            $stmt = $this->con->prepare($query);
            $stmt->bindParam(':postId', $postId, PDO::PARAM_INT);
            $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
            $stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
            $stmt->execute();

            $comments = $stmt->fetchAll(PDO::FETCH_ASSOC);

            $totalPages = ceil($totalComments / $limit);

            return [
                'status' => 'success',
                'data' => [
                    'comments' => $comments,
                    'pagination' => [
                        'current_page' => $page,
                        'per_page' => $limit,
                        'total_pages' => $totalPages,
                        'total_comments' => $totalComments
                    ]
                ],
                'message' => 'Comments retrieved successfully'
            ];
        } catch (PDOException $e) {
            error_log("GetCommentsPaginated DB error: " . $e->getMessage());
            return [
                'status' => 'error',
                'message' => 'Database error while retrieving paginated comments'. $e->getMessage()
            ];
        }
    }
}
?>