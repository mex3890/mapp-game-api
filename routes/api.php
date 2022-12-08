<?php

use App\Http\Controllers\Api\ProfessionalController;
use App\Http\Controllers\AuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::get('/', function () {
    return new \Illuminate\Http\JsonResponse(
        [
            'error' => false,
            'message' => 'Connection API Test'
        ]
    );
});
Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('register', [AuthController::class, 'register']);
Route::post('login', [AuthController::class, 'login']);
Route::post('professional/validation', [ProfessionalController::class, 'validation'])->middleware('auth:sanctum');

Route::apiResource('patients', ProfessionalController::class)->middleware('auth:sanctum');

// Use middleware('auth:sanctum') para as rotas de consulta e cadastro que precisam estar logado
