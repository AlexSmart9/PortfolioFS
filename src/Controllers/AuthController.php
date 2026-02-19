<?php
namespace App\Controllers;

use App\Models\User;
use Firebase\JWT\JWT;

class AuthController {

    private $userModel;

    public function __construct($db) {

        $this->userModel = new User($db);        

    }

    public function login() {

        // Reading sent data
        $data = json_decode(file_get_contents("php://input"), true);

        // Verifying that the email and the pass has been sent
        if (!isset($data['email']) || !isset($data['password'])) {

            http_response_code(400);
            echo json_encode(["error" => "Email and password are required"]);
            return;
        }

        // Searching user in database by email
        $user = $this->userModel->getByEmail($data['email']);

        // Verifying uf user exist and passwords are the same
        if(
            $user && password_verify($data['password'], $user['password'])
        ) {

            $secret_key= $_ENV['JWT_SECRET'];
            $issuer_claim = "http://localhost:8000"; // Who emite token
            $issuedat_claim = time(); // Time when it has emited
            $expire_claim = $issuedat_claim + 3600; // When it expires

            $token = array(
                "iss" => $issuer_claim,
                "iar" => $issuedat_claim,
                "exp" => $expire_claim,
                "data" => array(
                    "id" => $user['id'],
                    "name" => $user['name'],
                    "email" => $user['email'],
                    "role" => $user['role']
                )

                
                );
                
                //Sainging the token whit algorithm HS256
                $jwt = JWT::encode($token, $secret_key, 'HS256');


                // Return token to user
                http_response_code(200);
                echo json_encode([
                    "message" => "Login succesful",
                    "token" => $jwt
                ]);

        } else {
            
            http_response_code(401); // 401 Unauthorized

            echo json_encode(["error" => "Invalid credentials"]); 
        }

    }

}