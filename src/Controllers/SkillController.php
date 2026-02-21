<?php

namespace App\Controllers;

use App\Models\Skill;
use App\Middleware\AuthMiddleware;

class SkillController {

    private $skillModel;

    // Injecting database connection
    public function __construct($db)
    {
        $this->skillModel = new Skill($db);
    }

    // Method to get all Skills
    public function getAllSkills() {

        $skills = $this->skillModel->getAll();

        http_response_code(200);
        echo json_encode($skills);

    }

    // Method to get Skill by Id
    public function getSkillById($id) {

        $skill = $this->skillModel->getById($id);

        if ($skill) {

            http_response_code(200);
            echo json_encode($skill);

        } else {

            http_response_code(404);
            echo json_encode(["Error" => "Skill not found"]);
        }
    }

    // Method to create a Skill
    public function createSkill() {

        //Autentication
        $user = AuthMiddleware::authenticate();

        // Read data from body
        $data = json_decode(file_get_contents("php://input"), true);

        if(isset($data['name']) && $this->skillModel->create($data)) {

            http_response_code(201);
            echo json_encode(["Message" => "Skill created succesfuly"]);

        } else {

            http_response_code(500);
            echo json_encode(["error" => "Failed creating skill"]);

        }

    }

    // Method to update a skill
    public function updateSkill($id) {


        //Autentication
        AuthMiddleware::authenticate();

        // Read new Data from body
        $data = json_decode(file_get_contents("php://input"), true);

        // Try update
        $succes = $this->skillModel->update($id, $data);

        if($succes) {

            http_response_code(201);
            echo json_encode(["message" => "Skill updated succesfuly"]);
        } else {

            http_response_code(404);
            echo json_encode(["error" => "Skill not found"]);
        }
    }

    // Method to delate a Skill
    public function deleteSkill($id) {

        //Autentication
        AuthMiddleware::authenticate();
        
        $succes = $this->skillModel->delete($id);

        if($succes) {
            
            http_response_code(200);
            echo json_encode(["message" => "Skill deleted succesfuly"]);
        } else {

            http_response_code(404);
            echo json_encode(["error" => "Skill not found"]);
        }
    }
}

