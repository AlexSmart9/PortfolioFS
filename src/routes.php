<?php

use App\Controllers\UserController;

//User Static Routes
//Get All
$router->get('/api/users', [UserController::class, 'getAllUsers']);

//Create
$router->post('/api/users', [UserController::class, 'createUser']);

//User dinamic Routes
//Get user by ID
$router->get('/api/users/{id}', [UserController::class, 'getuserById']);

//Update user.
$router->put('/api/users/{id}', [UserController::class, 'updateUser']);

//Delete user
$router->delete('/api/users/{id}', [UserController::class, 'deleteUser']);




