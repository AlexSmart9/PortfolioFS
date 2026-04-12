<?php

namespace App\Controllers;

use App\Models\Certification;
use App\Middleware\AuthMiddleware;
use App\Traits\ImageUploader;

class CertificationController {

    use ImageUploader;

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

        try {
          $data['image_url'] = $this->HandleImageUpload('image', 'certifications');

          $certification = $this->certificationModel->create($data);
          if($certification) {
            http_response_code(201);
            echo json_encode(["message" => "Certification created succesfully!"]);
          }
        } catch (\Exception $e) {
            http_response_code(400);
            echo json_encode(["error" => $e->getMessage()]);
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
