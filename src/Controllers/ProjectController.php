<?php

namespace App\Controllers;

use App\Models\Project;
use App\Middleware\AuthMiddleware;

class ProjectController {

    private $projectModel;

    //Injecting the connection to the database

    public function __construct($db) {
        $this->projectModel = new Project($db);

    }
    
    // Method to get all projects
    public function getAllProjects() {

        $projects = $this->projectModel->getAll();

        http_response_code(200);
        echo json_encode($projects);
    }


    //Method to get a project by {id}
    public function getProjectById($id){
        
        $project = $this->projectModel->getById($id);

        if ($project) {
            
            http_response_code(200);
            echo json_encode($project);

        } else {
            http_response_code(404);
            echo json_encode(["error" => "Project Not Found"]);
        }
    }

    //Method to create a project
    public function createProject() {

        //Autenticatiom
        $user = AuthMiddleware::authenticate();

        // reading directly POST REQUEST
        $data = [
            'title' => $_POST['title'] ?? null,
            'description' => $_POST['description'] ?? null,
            'link' => $_POST['link'] ?? null,
            'technologies' => $_POST['technologies'] ?? null,
            'image_url' => null
            
        ]; 

        if (!$data['title']) {

            http_response_code(400);
            echo json_encode(["error" => "Title is required"]);

            return;
        }

        // Verifying if they sent a file called 'image and if it uploaded succesful

        if(isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {

            $uploadDir = __DIR__ . '/../../public/uploads/projects/';

            if(!is_dir($uploadDir)) {
                
                mkdir($uploadDir, 0777, true);
            }

            $fileTmpPath = $_FILES['image']['tmp_name'];

            $fileName = $_FILES['image']['name'];

            // Validating extension
            $allowedExts = ['jpg','jpeg','png','webp'];
            $fileExt = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));

            if (!in_array($fileExt, $allowedExts)) {
                http_response_code(400);
                echo json_encode(["error" => "Invalid file type."]);
                return;
            }

            // Rename file
            $newFileName = uniqid() . '.' . $fileExt;
            $destPath = $uploadDir . $newFileName;
            
            // Moving file from temp memory to our public folder
            if(move_uploaded_file($fileTmpPath, $destPath)) {

                $data['image_url'] = '/uploads/projects/' . $newFileName;

            } else {
                http_response_code(500);
                echo json_encode(["error"=> "Error saving image on server"]);
            }

            // Saving on database using smart model
            if($this->projectModel->create($data)) {
                http_response_code(201);
                echo json_encode([
                    "message" => "Project created succesfuly",
                    "image" => $data['image_url']
                ]);
            } else {

                http_response_code(500);
                echo json_encode(["error" => "Fail saving project on database"]); 

            }

        }


    }

    //Method to update a project
    public function updateProject($id) {

        //Aitemtication
        AuthMiddleware::authenticate();

        // Reading the new dara
        $data = json_decode(file_get_contents("php://input"), true);

        // traying to update
        $succes = $this->projectModel->update($id, $data);
        if($succes) {
            
            http_response_code(201);
            echo json_encode(["message" => "Project updated Succeslfully!!"]);

        } else {
            
            http_response_code(404);
            echo json_encode(["error" => "It couldn't update the Project, Id not found"]);
        
        }

    }

    // Method to delete a Project
    public function deleteProject($id) {


        // Autentication
        AuthMiddleware::authenticate();
        
        $success = $this->projectModel->delete($id);

        if ($success) {
            
            http_response_code(200);
            echo json_encode(["message" => "Project deleted succesfullly!"]);        
        
        } else {
            
            http_response_code(404);
            echo json_encode(["error" => "Project not found"]);
        
        }

    }



}