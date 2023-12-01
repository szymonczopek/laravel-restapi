<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {return view('welcome');});

Route::get('/pet/findByStatus', [App\Http\Controllers\PetController::class, 'findByStatus']);//4
Route::put('/pet', [App\Http\Controllers\PetController::class, 'put']);//3
Route::post('/pet', [App\Http\Controllers\PetController::class, 'store']); //2
Route::get('/pet/{petId}', [App\Http\Controllers\PetController::class, 'show']);//5
Route::delete('/pet/{petId}', [App\Http\Controllers\PetController::class, 'delete']);//7
Route::post('/pet/{petId}', [App\Http\Controllers\PetController::class, 'update']);//6
Route::post('/pet/{petId}/uploadImage', [App\Http\Controllers\PetController::class, 'uploadImage']);//1

