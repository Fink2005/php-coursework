<?php

// http://localhost/live/Home/Show/1/2

class Home extends Controller{
    private   $limit = 6; 
    
    
    function getPosts(): void{
        $posts = $this->model("PostModel");
        $user_id = $_SESSION['user']['id'] ?? null;
        $this->view("main", [   
            "Page"=>"HomePage",
            "view"=> "Posts",
            "allPosts" => $posts->getAllPosts($user_id),
        ]);
    }

    public function getPostId() {
        $posts = $this->model("PostModel");
        if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["id"])) {
            $id = intval($_POST["id"]);
            $post = $posts->getPostById($id);
            echo json_encode($post);
        } else {
            http_response_code(400);
            echo json_encode(["error" => "Invalid request"]);
        }
    }
    
    public function comment($id) {
        $posts = $this->model("CommentModel");
        if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["data"])) {
            $data = $_POST["data"];
            // Capture the return value of addComment
            $result = $posts->addComment($id, $data); 
            // Use the result array instead of the CommentModel object
            echo json_encode([
                "success" => $result['success'],
                "message" => $result['message']
            ]);
        } else {       
            http_response_code(400);
            echo json_encode(["success" => false, "message" => "Invalid request"]);
        }
    }
    public function getComments($page = 1): void
    {
        $comments = $this->model("CommentModel");
        if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["id"])) {
            $postId = filter_var($_POST["id"], FILTER_VALIDATE_INT);
            $page = filter_var($page, FILTER_VALIDATE_INT) ?: 1;
            if ($postId === false || $postId <= 0) {
                http_response_code(400);
                echo json_encode(["status" => "error", "message" => "Invalid post ID"]);
                return;
            }
            if ($page <= 0) {
                $page = 1;
            }
            $result = $comments->getCommentsPaginated($postId, $page, $this->limit);
            echo json_encode($result);
        } else {
            http_response_code(400);
            echo json_encode(["status" => "error", "message" => "Invalid request"]);
        }
    }


    function CreatePosts(){
    
        $postModel = $this->model("PostModel");
    
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
           $data = [
            'name' => $_POST['postName'] ?? "",
            'image_url' => $_POST['postImage'] ?? "",
            'post_url' => $_POST['postUrl'] ?? "",
            'des' => $_POST['description'] ?? "",
            'user_id' => $_POST['userId'] ?? null,
            'tags' => $_POST['tagIds'] ?? []
           ];
           try {
            $result = $postModel->createPost($data);

            if ($result) {
                echo json_encode([
                    'success' => true,
                    'message' => 'Post created successfully.'
                ]);
            } else {
                echo json_encode([
                    'success' => false,
                    'message' => 'Failed to create post.'
                ]);
            }
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode([
                'success' => false,
                'message' => 'Server error: ' . $e->getMessage()
            ]);
        }
    }
 else {
    $this->view("main", [   
        "Page"=>"HomePage",
        "view"=> "Create",
    ]);
 }

    }

    function UserInfo() {
            $this->view("main", [
                "Page" => "HomePage",
                "view" => "UserInfo",
            ]);
     
    }




    public function vote($post_id, $vote_type) {

        $user_id = $_SESSION['user']['id'];
        $vote = $this->model('VoteModel');

        $result = $vote->addVote($user_id, $post_id, $vote_type);
        if ($result['success']) {
            $voteCount = $vote->getVoteCount($post_id);
            $result['vote_count'] = $voteCount;
        }
        header('Content-Type: application/json');
        echo json_encode($result);
    }

    public function removeVote($post_id) {
        $vote = $this->model('VoteModel');


        $user_id = $_SESSION['user']['id'];
        $result = $vote->removeVote($user_id, $post_id);
        if ($result['success']) {
            $voteCount = $vote->getVoteCount($post_id);
            $result['vote_count'] = $voteCount;
        }
        header('Content-Type: application/json');
        echo json_encode($result);
    }





    

}
?>