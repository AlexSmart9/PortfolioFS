<?php

namespace App\Controllers;

use App\Models\Certification;
use App\Middleware\AuthMiddleware;

class CertificationController {

    private $certificationModel;

    // Injecting Database connection
    public function __construct($db) {
        
        $this->certificationModel = new Certification($db);

    }

    // Method to get all certifications
    public function getAllCertification() {

        $certifications = $this->certificationModel->getAll();

        http_response_code(200);
        echo json_encode($certifications);

    }

    // Method to get a certification by {id}
    public function getCertificationById($id) {

        $certification = $this->certificationModel->getById($id);

        if ($certification) {
                
                http_response_code(200);
                echo json_encode($certification);
            
        } else {
            
            http_response_code(404);
            echo json_encode(["error" => "Certification Not Found"]);

        }
    }

    // Method to create a certification
    public function createCertification() {

        // Authentication
        $user = AuthMiddleware::authenticate();

        // Reading directly POST REQUEST
        $data = [
            'title' => $_POST['title'] ?? null,
            'issuing_entity' => $_POST['issuing_entity'] ?? null,
            'date_acquisition' => $_POST['date_acquisition'] ?? null,
            'image_url' => null

        ];

        if(!$data['title']) {

            http_response_code(400);
            echo json_encode(["error" => "Tilte is required"]);

            return;
        }

        // Verifying if they sent a file called 'image' and if it uploaded succesful
        if(isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {

        $uploadDir = __DIR__ . '/../../public/uploads/certifications/';

            if(!is_dir($uploadDir)) {

                mkdir($uploadDir, 0777, true);

            }

            $fileTmpPath = $_FILES['image']['tmp_name'];
            
            $fileName = $_FILES['image']['name'];

            //Validating extension
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

            // Move file from tempo memory to our public folder
            if(move_uploaded_file($fileTmpPath, $destPath)) {

                $data['image_url'] = '/uploads/certifications/' . $newFileName;

            } else {
                
                http_response_code(500);
                echo json_encode(["error"=> "Error saving image on server"]);

            }

        }
        
        // Saving on Database using smart model
        if($this->certificationModel->create($data)) {

            http_response_code(201);
            echo json_encode(["message" => "Certification created succesful",
            "image" => $data['image_url']
                
            ]);
        }
            
            
    }
            
    // Meethod to update a certification
    public function updateCertification($id) {

        // Autentication
        AuthMiddleware::authenticate();

        // Read new data
        $data = json_decode(file_get_contents("php://imput", true));

        // Update 
        $succes = $this->certificationModel->update($id, $data);

        if($succes) {

            http_response_code(201);
            echo json_encode(["message" => "Certification updated succesful"]);

        } else {

            http_response_code(404);
            echo json_encode(["error" => "Certification not found"]);

        }
    }

    // Method to delete a certification.
    public function deleteCertification($id) {
        // Autentication
        AuthMiddleware::authenticate();
        
        $success = $this->certificationModel->delete($id);

        if ($success) {
            
            http_response_code(200);
            echo json_encode(["message" => "Certification deleted succesfullly!"]);        
        
        } else {
            
            http_response_code(404);
            echo json_encode(["error" => "Certification not found"]);
        
        }
    }
}