<?php
require 'vendor/autoload.php';
use Cloudinary\Api\Upload\UploadApi;

class Ajax extends Controller {
    public $PostModel;

    public function __construct() {
        $this->PostModel = $this->model("PostModel");
    }

    public function upload() {
        // Start output buffering to catch any stray output
        ob_start();

        // Default response
        $response = [
            'success' => false,
            'message' => 'Invalid request or no file uploaded'
        ];

        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['file'])) { // Changed 'image' to 'file'
            $file = $_FILES['file']['tmp_name'];

            try {
                // Upload the file to Cloudinary
                $upload = new UploadApi();
                $result = $upload->upload($file);

                if (isset($result['error'])) {
                    $response = [
                        'success' => false,
                        'message' => $result['error']['message'] // More specific error message
                    ];
                } else {
                    $response = [
                        'success' => true,
                        'image_url' => ['url' => $result['secure_url']] // Match your JS expectation
                    ];
                }
            } catch (Exception $e) {
                $response = [
                    'success' => false,
                    'message' => 'Upload failed: ' . $e->getMessage()
                ];
            }
        }

        // Clear buffer and send JSON response
        ob_end_clean();
        header('Content-Type: application/json');
        echo json_encode($response);
        exit;
    }
}