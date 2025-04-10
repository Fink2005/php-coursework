<?php
class PostModel extends DB {

    public function getAllPosts() {
        $stmt = $this->con->prepare("
            SELECT p.*, u.username 
            FROM Posts p 
            JOIN users u ON p.user_id = u.id
        ");
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function getPostById($id) {
        $stmt = $this->con->prepare("
            SELECT p.*, u.username 
            FROM Posts p 
            JOIN users u ON p.user_id = u.id 
            WHERE p.id = ?
        ");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    // public function getPostTags($postId) {
    //     $stmt = $this->pdo->prepare("
    //         SELECT t.* 
    //         FROM tags t 
    //         JOIN tags_posts tp ON t.id = tp.tag_id 
    //         WHERE tp.post_id = ?
    //     ");
    //     $stmt->execute([$postId]);
    //     return $stmt->fetchAll();
    // }

    public function createPost($data) {
        $stmt = $this->con->prepare("
            INSERT INTO Posts (name, image_url, post_url, des, user_id) 
            VALUES (?, ?, ?, ?, ?)
        ");
        return $stmt->execute([
            $data['name'],
            $data['image_url'],
            $data['post_url'],
            $data['des'],
            $data['user_id']
        ]);
    }
}