<?php

namespace App\Controllers;

use App\Models\Project;

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

        // Reading data from body
        $data = json_decode(file_get_contents("php://input"), true);
        
        if(isset($data['title']) && $this->projectModel->create($data)) {
            
            http_response_code(201); // 201 Created
            echo json_encode(["message" => "Project created successfully!"]);

        } else {
            
            http_response_code(500);
            echo json_encode(["error" => "Failed to create project"]);
        
        }

    }

    //Method to update a project
    public function updateProject($id) {

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