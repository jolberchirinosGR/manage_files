<?php

use App\Http\Controllers\UserController;
use App\Http\Controllers\ApplicationController;
use App\Http\Controllers\FileController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', ApplicationController::class)->where('view', '(.*)')->middleware('auth');

Route::middleware('auth')->group(function () {
    //Ajsutes basicos de la WebService
    Route::get('/web/users', [UserController::class, 'index']);
    Route::post('/web/users', [UserController::class, 'store']);
    Route::put('/web/users/{user}', [UserController::class, 'update']);
    Route::delete('/web/users/{user}', [UserController::class, 'destory']);
    Route::get('/web/users_unpaged', [UserController::class, 'index_unpaged']);
    Route::get('/web/roles', [UserController::class, 'getRoles']);
    Route::get('/web/profile', [UserController::class, 'profile']);
    Route::resource('web/files', FileController::class);
});

Route::get('{view}', ApplicationController::class)->where('view', '(.*)')->middleware('auth');
