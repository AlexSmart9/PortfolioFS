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

        http_response_code(200);
        echo json_encode($user);
    }

    //Method to create a user.
    public function createUser() {
        $data = json_decode(file_get_contents("php://input"), true);

        http_response_code(201);

        echo json_encode(["message" => "Usuario creado simulado", "data" => $data]);
    }

    //Metho to update a user.
    public function updateUser($id) {
        
       // 1. Leemos los datos nuevos del body
        $data = json_decode(file_get_contents("php://input"), true);

        // 2. Intentamos actualizar
        $success = $this->userModel->update($id, $data);

        if ($success) {
            http_response_code(200);
            echo json_encode(["message" => "Usuario actualizado correctamente"]);
        } else {
            // Si falló (probablemente el ID no existe o error SQL)
            http_response_code(404); // O 500 dependiendo del error
            echo json_encode(["error" => "No se pudo actualizar el usuario (ID no encontrado)"]);
        }
    }

    //Mwthod to delete a user.
    public function deleteUser($id) {

        $success = $this->userModel->delete($id);

        if ($success) {
            // 204 = No Content (Éxito pero sin mensaje)
            // O usamos 200 si queremos mandar un JSON de confirmación
            http_response_code(200);
            echo json_encode(["message" => "Usuario eliminado correctamente"]);
        } else {
            http_response_code(404);
            echo json_encode(["error" => "Usuario no encontrado"]);
        }

    }

}
