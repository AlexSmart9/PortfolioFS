<?php

namespace App\Middleware;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Exception;

class AuthMiddleware {

    public static function authenticate( $requiredRole = null ) {

        // Storing headers sended by client
        $headers = getallheaders();

        $authHeader = $headers['Authorization'] ?? $headers['authorization'] ?? null;

        // If serVer hides headers, then try to pull it out from $_SERVER
        if (!$authHeader && isset($_SERVER['HTTP_AUTHORIZATION'])) {

            $authHeader = $_SERVER['HTTP_AUTHORIZATION'];
        }

        // Verifying header exists and it got the format "Bearer <token>"
        if (!$authHeader || !preg_match('/Bearer\s(\S+)/', $authHeader, $matches)) {

            http_response_code(401);
            echo json_encode(["error" => "Acces denied"]);

            exit;
        }

        $token = $matches[1];

        try {

            // Try uncrypt token using secret key
            $secret_key = $_ENV['JWT_SECRET'];

            $decoded = JWT::decode($token, new Key($secret_key, 'HS256'));

            $user = $decoded->data;

            // Authorization to create, delete and update
            if ($requiredRole !== null) {
                if(!isset($user->role) || $user->role !== $requiredRole) {
                    http_response_code(403);
                    echo json_encode(["error" => "Forbidden: Unauthorized"]);
                    exit;
                }
            }

            return $user;


        } catch (Exception $e) {
            
            http_response_code(401);
            echo json_encode([
                "error" => "Token invalid or expired",
                "details" => $e->getMessage()
            ]);
            exit;
        }


    }


}