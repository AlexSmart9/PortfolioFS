<?php

namespace App\Controllers;

use App\Models\User;

class UserController {

    private $userModel;

    // Injecting the connection to the database.
    public function __construct($db) {
        $this->userModel = new User($db);
    }

    //Metho to Get all users.
    public function getAllUsers() {
        
        $users = $this->userModel->getAll();
        
        http_response_code(200);
        echo json_encode($users);
    }

    //Method to get a user by id
    public function getUserById($id) {
        
        $user = $this->userModel->getById($id);

        if ($user) {
            
            http_response_code(200);
            echo json_encode($user);

        } else {
            http_response_code(404);
            echo json_encode(["error" => "User Not Found"]);
        }
    }

    //Method to create a user.
    public function createUser() {

        // Reading data from body
        $data = json_decode(file_get_contents("php://input"), true);
        
        if(isset($data['title']) && $this->userModel->create($data)) {
            
            http_response_code(201); // 201 Created
            echo json_encode(["message" => "User created successfully!"]);

        } else {
            
            http_response_code(500);
            echo json_encode(["error" => "Failed creating user"]);
        
        }
    }

    //Metho to update a user.
    public function updateUser($id) {
        
       // 1. Leemos los datos nuevos del body
        $data = json_decode(file_get_contents("php://input"), true);

        // 2. Intentamos actualizar
        $success = $this->userModel->update($id, $data);

        if ($success) {
            http_response_code(200);
            echo json_encode(["message" => "User updated succesfuly"]);
        } else {
        
            http_response_code(404); 
            echo json_encode(["error" => "Skill not found"]);
        }
    }

    //Mwthod to delete a user.
    public function deleteUser($id) {

        $success = $this->userModel->delete($id);

        if ($success) {
            
            http_response_code(200);
            echo json_encode(["message" => "Usuario eliminado correctamente"]);

        } else {

            http_response_code(404);
            echo json_encode(["error" => "Usuario no encontrado"]);
        
        }

    }

}
