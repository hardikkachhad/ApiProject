<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\PostController;

Route::post('/signup',[AuthController::class,'signup']);
Route::post('/signin',[AuthController::class,'login']);
Route::post('/logout',[AuthController::class,'logout']);

Route::apiResource('posts', PostController::class);