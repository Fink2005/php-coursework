<?php

// http://localhost/live/Home/Show/1/2

class Admin extends Controller{
    private   $limit = 6; 

    function Dashboard(){
        $dashboard = $this->model("Dashboard");
        $this->view("main", [
            "Page"=>"AdminPage",
            "manage" => $dashboard
        ]);
    }

    public function Users($page = 1){
        $userModel = $this->model("UserModel");
        $offset = ((int)$page - 1) * $this->limit;
    
        $users = $userModel->getUsersPaginated($this->limit, $offset);
        $totalUsers = $userModel->getTotalUsers();
        $totalPages = ceil($totalUsers / $this->limit);
    
        // Check if request is AJAX
        if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
            echo json_encode([
                'users' => $users,
                'currentPage' => $page,
                'totalPages' => $totalPages
            ]);
            return;
        }
    
        // Fallback for normal rendering
        $this->view("main", [
            "Page"=>"AdminPage",
            "Manage"=> "Users",
        ]);
    }
    public function UpdateUser($id) {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'email' => $_POST['email'] ?? '',
                'username' => $_POST['username'] ?? '',
                'password' => $_POST['password'] ?? '',
                'newPassword' => $_POST['newPassword'] ?? '',
                'permission_id' => $_POST['permission_id'] ?? '',
                'verify_status' => $_POST['verify_status'] ?? ''
            ];
            $userModel = $this->model("UserModel");
            $result = $userModel->updateUser($id, $data);
    
            echo json_encode([
                "success" => $result["success"],
                "message" => $result["message"],
                "user_id" => $result["user_id"] ?? null,
                "user_data" => $result["data"] ?? null
            ]);
        } else {
            echo json_encode([
                "success" => false,
                "message" => "Invalid request method"
            ]);
        }
    }
    public function DeleteUser($id) {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
         
            $userModel = $this->model("UserModel");
            $result = $userModel->deleteUser($id);
    
            echo json_encode([
                "success" => $result["status"],
                "message" => $result["message"],
            ]);
        } else {
            echo json_encode([
                "success" => false,
                "message" => "Invalid request method"
            ]);
        }
    }
    
    function Posts($page = 1){
    
        $postModel = $this->model("PostModel");
        $offset = ((int)$page - 1) * $this->limit;
    
        $posts = $postModel->getPostsPaginated($this->limit, $offset);
        $totalPosts = $postModel->getTotalPosts();
        $totalPages = ceil($totalPosts / $this->limit);
    
        // Check if request is AJAX
        if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
            echo json_encode([
                'posts' => $posts,
                'currentPage' => $page,
                'totalPages' => $totalPages
            ]);
            return;
        }

        $this->view("main", [
            "Page"=>"AdminPage",
            "Manage"=> "Posts",
        ]);
    }


 


    public function UpdatePost($id)
    {
        $postModel = $this->model("PostModel");
    
        if ($_SERVER['REQUEST_METHOD'] === 'POST' ) {
            // Log the post ID
            $data = [
                'name' => $_POST['postName'] ?? '',
                'image_url' => $_POST['postImage'] ?? '',
                'post_url' => $_POST['postUrl'] ?? '',
                'des' => $_POST['description'] ?? '',
                'user_id' => $_POST['userId'] ?? null,
                'tags' => isset($_POST['tagIds']) && is_array($_POST['tagIds']) ? $_POST['tagIds'] : []
            ];
    
            // Log data for debugging
            error_log('Data for update: ' . print_r($data, true));
    
            // Validate required fields
            if (empty($data['name']) || empty($data['des']) || empty($data['image_url']) || empty($data['tags'])) {
                http_response_code(400);
                echo json_encode([
                    'success' => false,
                    'message' => 'All required fields must be provided. Missing: ' .
                        (empty($data['name']) ? 'name ' : '') .
                        (empty($data['des']) ? 'description ' : '') .
                        (empty($data['image_url']) ? 'image_url ' : '') .
                        (empty($data['tags']) ? 'tags' : '')
                ]);
                return;
            }
    
            try {
                $result = $postModel->updatePost($id, $data);
    
                if ($result) {
                    echo json_encode([
                        'success' => true,
                        'message' => 'Post updated successfully.'
                    ]);
                } else {
                    http_response_code(500);
                    echo json_encode([
                        'success' => false,
                        'message' => 'Failed to update post.'
                    ]);
                }
            } catch (Exception $e) {
                http_response_code(500);
                echo json_encode([
                    'success' => false,
                    'message' => 'Server error: ' . $e->getMessage()
                ]);
            }
        } else {
            echo json_encode([
                'success' => false,
                'message' => 'Method not allowed. Use POST with _method=PUT.'
            ]);
        }
    }


    public function DeletePost($id) {
        $postModel = $this->model("PostModel");

        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['_method']) && $_POST['_method'] === 'DELETE') {
            // Log the post ID
            error_log("Deleting post ID: $id");

            try {
                $result = $postModel->deletePost($id);
                echo json_encode([
                    'success' => true,
                    'message' => 'Post deleted successfully.'
                ]);
            } catch (Exception $e) {
                http_response_code(500);
                echo json_encode([
                    'success' => false,
                    'message' => 'Server error: ' . $e->getMessage()
                ]);
            }
        } else {
            http_response_code(405);
            echo json_encode([
                'success' => false,
                'message' => 'Method not allowed. Use POST with _method=DELETE.'
            ]);
        }
    }

    function Tags($page = 1){
    
        $tagModel = $this->model("TagModel");

      
        $offset = ((int)$page - 1) * $this->limit;

        // Get users and total count
        $tags = $tagModel->getTagsPaginated($this->limit, $offset);
        $totaltags = $tagModel->getTotalTags();
        $totalPages = ceil($totaltags / $this->limit);

        if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
            echo json_encode([
                'tags' => $tags,
                'currentPage' => $page,
                'totalPages' => $totalPages
            ]);
            return;
        }


        $this->view("main", [
            "Page"=>"AdminPage",
            "Manage"=> "Tags",
        ]);
    }

    function AllTags() {
        $tagModel = $this->model("TagModel");
        $tags = $tagModel->getAllTags();

        if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
            echo json_encode([
                'tags' => $tags,
            ]);
            return;
        }



    }


    public function CreateTag() {
        $tagModel = $this->model("TagModel");

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Log the received data
            error_log('Creating tag: ' . print_r($_POST, true));

            // Prepare data
            $data = [
                'name' => $_POST['name'] ?? '',
                'description' => $_POST['description'] ?? '',
                'user_id' => $_POST['userId'] ?? null
            ];

            // Validate required fields
            if (empty($data['name']) || empty($data['user_id'])) {
                http_response_code(400);
                echo json_encode([
                    'success' => false,
                    'message' => 'All required fields must be provided. Missing: ' .
                        (empty($data['name']) ? 'name ' : '') .
                        (empty($data['user_id']) ? 'user_id' : '')
                ]);
                return;
            }

            try {
                $result = $tagModel->createTag($data);
                echo json_encode([
                    'success' => true,
                    'message' => 'Tag created successfully.'
                ]);
            } catch (Exception $e) {
                http_response_code(500);
                echo json_encode([
                    'success' => false,
                    'message' => 'Server error: ' . $e->getMessage()
                ]);
            }
        } else {
            http_response_code(405);
            echo json_encode([
                'success' => false,
                'message' => 'Method not allowed. Use POST.'
            ]);
        }
    }

    public function UpdateTag($id) {
        $tagModel = $this->model("TagModel");

        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['_method']) && $_POST['_method'] === 'PUT') {
            // Log the tag ID and data
            error_log("Updating tag ID: $id");
            error_log('Data for update: ' . print_r($_POST, true));

            // Prepare data
            $data = [
                'name' => $_POST['name'] ?? '',
                'description' => $_POST['description'] ?? ''
            ];

            // Validate required fields
            if (empty($data['name'])) {
                http_response_code(400);
                echo json_encode([
                    'success' => false,
                    'message' => 'All required fields must be provided. Missing: name'
                ]);
                return;
            }

            try {
                $result = $tagModel->updateTag($id, $data);
                echo json_encode([
                    'success' => true,
                    'message' => 'Tag updated successfully.'
                ]);
            } catch (Exception $e) {
                http_response_code(500);
                echo json_encode([
                    'success' => false,
                    'message' => 'Server error: ' . $e->getMessage()
                ]);
            }
        } else {
            http_response_code(405);
            echo json_encode([
                'success' => false,
                'message' => 'Method not allowed. Use POST with _method=PUT.'
            ]);
        }
    }

    public function GetTag($id) {
        $tagModel = $this->model("TagModel");

        try {
            $tag = $tagModel->getTag($id);
            echo json_encode([
                'success' => true,
                'tag' => $tag
            ]);
        } catch (Exception $e) {
            http_response_code(404);
            echo json_encode([
                'success' => false,
                'message' => $e->getMessage()
            ]);
        }
    }
    public function SearchTags($query) {
        $tagModel = $this->model("TagModel");

        try {

            error_log(message: "SearchTags called with query: '$query'");
            $tags = $tagModel->searchTags($query);
            echo json_encode(['tags' => $tags]);
        } catch (Exception $e) {
            http_response_code(500);
            error_log('SearchTags error: ' . $e->getMessage());
            echo json_encode(['success' => false, 'message' => 'Server error: ' . $e->getMessage()]);
        }
    }

    public function DeleteTag($id) {
        $tagModel = $this->model("TagModel");

        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['_method']) && $_POST['_method'] === 'DELETE') {
            // Log the tag ID
            error_log("Deleting tag ID: $id");

            try {
                $result = $tagModel->deleteTag($id);
                echo json_encode([
                    'success' => true,
                    'message' => 'Tag deleted successfully.'
                ]);
            } catch (Exception $e) {
                http_response_code(500);
                echo json_encode([
                    'success' => false,
                    'message' => 'Server error: ' . $e->getMessage()
                ]);
            }
        } else {
            http_response_code(405);
            echo json_encode([
                'success' => false,
                'message' => 'Method not allowed. Use POST with _method=DELETE.'
            ]);
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
    
         
            if ($result['status'] === 'success') {
                echo json_encode(['success' => true, 'message' => 'User created successfully.']);

            }
            
            else {
                echo json_encode([
                    'success' => false,
                    'message' => $result['message']
                ]);
            }
        } 
    }



}
?>