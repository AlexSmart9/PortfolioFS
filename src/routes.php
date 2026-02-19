<?php

use App\Controllers\UserController;
use App\Controllers\ProjectController;

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

// Updadte
$router->put('/api/projects/{id}', [ProjectController::class, 'updateProject']);

// Delete
$router->delete('/api/projects/{id}', [ProjectController::class, 'deleteProject']);

