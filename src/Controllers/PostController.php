<?php

namespace App\Controllers;

use App\Models\Post;
use App\Middleware\AuthMiddleware;
use App\Traits\ImageUploader;

class PostController {

    use ImageUploader;

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

    // Method to create Post 
    public function createPost() {

        $user = AuthMiddleware::authenticate();
      

        $data = [
            'title' => $_POST['title'] ?? null,
            'content' => $_POST['content'] ?? null,
            'image_url' => null
        ];

        try {
          $data['image_url'] = $this->HandleImageUpload('image', 'posts');

          $post = $this->postModel->create($data);
          
          if($post) {
            http_response_code(201);
            echo json_encode(["message" => "Post created successfully!"]); 
          }
        } catch (\Exception $e) {
            http_response_code(400);
            echo json_encode(["error" => $e->getMessage()]);
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