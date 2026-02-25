<?php

namespace App\Controllers;

use App\Models\Post;
use App\Middleware\AuthMiddleware;

class PostController {

    protected $postModel;

    public function __construct($db) {

        $this->postModel = new Post($db);

    }
    // Method to Get all
    public function getAllPosts() {

        $posts = $this->postModel->getAll();
        http_response_code(200);
        echo json_encode($posts);

    }

    // Method to Get By Id
    public function getPostById($id) {

        $post = $this->postModel->getById($id);
        if($post) {
            
            http_response_code(200);
            echo json_encode($post);

        } else {
            
            http_response_code(404);
            echo json_encode(["error" => " Post not found"]);

        } 

    }

    // Metho to create Post 
    public function createPost() {

        $user = AuthMiddleware::authenticate();

        $authorId = $user->data->id ?? $user->id ?? null;

        if(!$authorId) {
            http_response_code(403);
            echo json_encode(["error" => "Security Breach> User ID missing form token"]);
            return;
        }

        $data = [
            'title' => $_POST['title'] ?? null,
            'content' => $_POST['content'] ?? null,
            'image_url' => null
        ];

        if (!$data['title'] || !$data['content']) {
            http_response_code(400);
            echo json_encode(["error" => "Title and content are required"]);
            return;
        }

        json_decode($data['content']);
        if (json_last_error() !== JSON_ERROR_NONE) {
            http_response_code(400);
            echo json_encode(["error" => "The 'content' field must be a valid JSON string."]);
            return;
        }

        if(isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
            $uploadDir = __DIR__ . '/../../public/uploads/posts';

            if(!is_dir($uploadDir)) {
                
                mkdir($uploadDir, 0777, true);

            }

            $fileExt = strtolower(pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION));
            $allowedExts = ['jpg', 'jpeg', 'png', 'webp'];

            if(in_array($fileExt, $allowedExts)) {
                $newFileName = uniqid() . ',' . $fileExt;
                if(move_uploaded_file($_FILES['image']['tmp_name'], $uploadDir . $newFileName)) {

                    $data['image_url'] = '/uploads/posts/' . $newFileName;
                }
            }
         }

         if($this->postModel->create($data)) {

            http_response_code(201);
            echo json_encode(["message" => "Blog post publiched succesfully",
            "image" =>$data['image_url']]);

         } else {
            
            http_response_code(500);
            echo json_encode(["error" => "Failed to save post to database"]);

         }

    }

    // Method to update a post
    public function updatePost($id) {

        AuthMiddleware::authenticate();

        $data = json_decode(file_get_contents("php://input"), true);

        $succes = $this->postModel->update($id, $data);

        if($succes) {

            http_response_code(200);
            echo json_encode(["message" => "Post updated Succesfully"]);

        } else {
            
            http_response_code(404);
            echo json_encode(["error" => "Post not fond"]);
        }

    }

    // Method to delete  post
    public function deletePost($id) {

        AuthMiddleware::authenticate();
        
        if($this->postModel->delete($id)) {
            
            http_response_code(200);
            echo json_encode(["message" => "Post deleted succesfully"]);

        } else {
            
            http_response_code(404);
            echo json_encode(["error" => "Post not found"]);

        }


    }

    // Method to search a Post by it's title
    public function searchPost() {

        $keyword = $_GET['q'] ?? '';
        if(!$keyword) {
            
            http_response_code(400);
            echo json_encode(["error" => "Please provide a search keyword using ?q="]);
            return;

        }

        $results = $this->postModel->searchByTitle($keyword);

        if(count($results) > 0) {
            
            http_response_code(200);
            echo json_encode($results);

        } else {
                
            http_response_code(404);
            echo json_encode(["error" => "No posts found matching that title"]);

        }
    }


}