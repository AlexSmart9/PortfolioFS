<?php

namespace App\Controllers;

use App\Models\Project;
use App\Middleware\AuthMiddleware;
use App\Traits\ImageUploader;

class ProjectController {

    use ImageUploader;

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

        try {
          $data['image_url'] = $this->HandleImageUpload('image', 'projects');

          $project = $this->projectModel->create($data);
          if($project) {
            http_response_code(201);
            echo json_encode(["message" => "Project created successfully!"]); 
          }
        } catch (\Exception $e) {
            http_response_code(400);
            echo json_encode(["error" => $e->getMessage()]);
        }

    }

    //Method to update a project
    public function updateProject($id) {

        //Authentication
        AuthMiddleware::authenticate();

        // Reading the new data
        $data = $_POST;

        if (isset($data['_method'])) {
            unset($data['_method']);
        }

        // traying to update
        $success = $this->projectModel->update($id, $data);
        if($success) {
            
            http_response_code(200);
            echo json_encode(["message" => "Project updated Successfully!!"]);

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