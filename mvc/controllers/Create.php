<?php

// http://localhost/live/Home/Show/1/2

class Create extends Controller{

   function createPost(){
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'name' => $_POST['name'],
                'image_url' => $_POST['image_url'],
                'post_url' => $_POST['post_url'],
                'des' => $_POST['des'],
                'user_id' => $_POST['user_id']
            ];

            $posts = $this->model("PostModel");
            $result = $posts->createPost($data);

            if ($result) {
                echo json_encode(['success' => true]);
              
            } else {
                echo json_encode(['success' => false]);
            }
        }
        $this->view("main", [
            "Page"=>"CreatePage",
            // "allPosts" => $posts->getAllPosts()
        ]);
    }
   
}
?>