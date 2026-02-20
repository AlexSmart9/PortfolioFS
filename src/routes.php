<?php

use App\Controllers\UserController;
use App\Controllers\ProjectController;
use App\Controllers\AuthController;
use App\Controllers\CertificationController;
use App\Controllers\SkillController;

// User Static Routes:
// Get All
$router->get('/api/users', [UserController::class, 'getAllUsers']);

// Create
$router->post('/api/users', [UserController::class, 'createUser']);

// User Dinamic Routes
//Get user by ID
$router->get('/api/users/{id}', [UserController::class, 'getuserById']);

// Update user.
$router->put('/api/users/{id}', [UserController::class, 'updateUser']);

// Delete user
$router->delete('/api/users/{id}', [UserController::class, 'deleteUser']);


// Projects Static Routes:
// Get All
$router->get('/api/projects', [ProjectController::class, 'getAllProjects']);

// Create
$router->post('/api/projects', [ProjectController::class, 'createProject']);

//Projects Dinamic Routes:
// Get By Id
$router->get('/api/projects/{id}', [ProjectController::class, 'getProjectById']);

// Update
$router->put('/api/projects/{id}', [ProjectController::class, 'updateProject']);

// Delete
$router->delete('/api/projects/{id}', [ProjectController::class, 'deleteProject']);


// Login route
$router->post('/api/login', [AuthController::class, 'login']);

// Certification Static routes
//Get All
$router->get('/api/certifications', [CertificationController::class,'getAllCertification']);

//Create
$router->post('/api/certifications', [CertificationController::class,'createCertification']);

// Certification Dinamic Routes
// Get By Id
$router->get('/api/certifications/{id}', [CertificationController::class,'getCertificationById']);

// Update
$router->put('/api/certifications/{id}', [CertificationController::class,'updateCertification']);

// Delete
$router->delete('/api/certifications/{id}', [CertificationController::class,'deleteCertification']);

// Skill Static Routes
// Get All
$router->get('/api/skills', [SkillController::class, 'getAllSkills']);

// Create
$router->post('/api/skills', [SkillController::class, 'createSkill']);

// Skill Dinamic Routes
// Get By Id
$router->get('/api/skills/{id}', [SkillController::class, 'getSkillById']);

// Update
$router->put('/api/skills/{id}', [SkillController::class, 'updateSkill']);

// Delete
$router->delete('/api/skills/{id}', [SkillController::class, 'deleteSkill']);

