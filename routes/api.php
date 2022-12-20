<?php

use App\Http\Controllers\AnswerController;
use App\Http\Controllers\PatientController;
use App\Http\Controllers\ProfessionalController;
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

Route::post('register', [AuthController::class, 'register'])->name('register');
Route::post('login', [AuthController::class, 'login'])->name('login');
Route::post('forgot_password', [AuthController::class, 'forgotPassword'])->name('forgot_password');
Route::put('user/update', [AuthController::class, 'update'])->name('users.update');

Route::get('patients/index/{user_id}', [PatientController::class, 'index'])->name('patients.index');
Route::get('patients/answers/{patient_id}', [PatientController::class, 'answers'])->name('patients.answers');
Route::post('patients/store/{user_id}', [PatientController::class, 'store'])->name('patients.store');
Route::put('patients/update/{patient_id}', [PatientController::class, 'update'])->name('patients.update');
Route::delete('patients/destroy/{patient_id}', [PatientController::class, 'destroy'])->name('patients.destroy');
Route::get('patients/email/{user_email}', [PatientController::class, 'searchByUserEmail'])->name('patients.search');

Route::get('professionals/patients/{user_id}', [ProfessionalController::class, 'patients'])->name('professionals.patients');
Route::get('professionals/{user_id}', [ProfessionalController::class, 'show'])->name('professionals.show');
Route::put('professionals/{user_id}', [ProfessionalController::class, 'update'])->name('professionals.update');
Route::post('professionals/store/{user_id}', [ProfessionalController::class, 'store'])->name('professionals.store');
Route::post('professionals/patient/store', [ProfessionalController::class, 'patientStore'])->name('professionals.patient.store');

Route::post('answers', [AnswerController::class, 'store'])->name('answers.store');
