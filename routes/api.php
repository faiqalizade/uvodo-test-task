<?php
use Core\Route;

Route::get('/users', [\App\Controllers\UserController::class, 'all']);
Route::get('/users/:id', [\App\Controllers\UserController::class, 'find']);
Route::put('/users/:id', [\App\Controllers\UserController::class, 'edit']);
Route::delete('/users/:id', [\App\Controllers\UserController::class, 'delete']);
Route::post('/users', [\App\Controllers\UserController::class, 'create']);
Route::get('/test', function (\Core\Request $request) {
    echo "<pre>";
    print_r(\App\Models\User::getAll());
    die();
});
