<?php

namespace App\Http\Controllers;

use App\Http\Resources\PatientResource;
use App\Models\Patient;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Throwable;

class PatientController extends Controller
{
    public function answers(int $patient_id): JsonResponse
    {
        try {
            $patient = Patient::where('id', $patient_id)->first();

            if (!($patient instanceof Patient)) {
                return response()->json([
                    'status' => true,
                    'error' => 'Paciente não encontrado!'
                ], 202);
            }

            $answers = DB::select("
            select distinct * from(
            select created_at, count(status) as hits, 5 - count(status) as errors
            from mapp_game_api.answers
            where patient_id = $patient_id
            and status = 1
            group by created_at
            union(
            select created_at, 5 - count(status) as hits, count(status) as hits
            from mapp_game_api.answers
            where patient_id = $patient_id
            and status = 0
            group by created_at
            order by created_at)
            ) as answers_format order by created_at;");

            $patient = new PatientResource($patient);

            return response()->json([
                'status' => true,
                'patient' => $patient,
                'count_of_games' => count($answers),
                'answers' => $answers
            ]);
        } catch (Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => $th->getMessage()
            ], 500);
        }
    }

    /**
     * @param int $user_id
     * @return JsonResponse
     */
    public function index(int $user_id): JsonResponse
    {
        try {
            $patients = Patient::where('user_id', $user_id)->get();

            return response()->json([
                'status' => true,
                'patients' => $patients
            ]);
        } catch (Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => $th->getMessage()
            ], 500);
        }
    }

    /**
     * @param int $user_id
     * @param Request $request
     * @return JsonResponse
     */
    public function store(int $user_id, Request $request): JsonResponse
    {
        try {
            $validate_patient = Validator::make($request->all(),
                [
                    'name' => 'required|max:25',
                    'birth_date' => 'required|date_format:Y-m-d',
                ], [
                    'required' => 'The :attribute is required',
                    'birth_date.date_format' => 'The format of date is yyyy-mm-dd'
                ]);

            if ($validate_patient->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'validation error',
                    'error' => $validate_patient->errors()->first()
                ], 202);
            }

            Patient::create([
                'name' => $request->get('name'),
                'birth_date' => $request->get('birth_date'),
                'user_id' => $user_id,
                'created_at' => now()
            ]);


            return response()->json([
                'status' => true,
                'message' => 'Register Successfully!'
            ]);

        } catch (Throwable $throwable) {
            Log::error($throwable);
            return response()->json([
                'status' => false,
                'message' => 'Internal Error',
                'error' => 'Failed to register patient'
            ], 500);
        }
    }

    /**
     * @param int $patient_id
     * @param Request $request
     * @return JsonResponse
     */
    public function update(int $patient_id, Request $request): JsonResponse
    {
        $date = Carbon::now()->format('Y-m-d');
        try {
            $validate_patient = Validator::make($request->all(),
                [
                    'name' => 'required|max:25',
                    'birth_date' => 'required|date',
                ],
                [
                    'birth_date.date' => 'The date format is like ' . $date
                ]);

            if ($validate_patient->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'validation error',
                    'error' => $validate_patient->errors()->first()
                ], 202);
            }

            /** @var Patient $patient */
            $patient = Patient::where('id', $patient_id)->first();

            if ($patient instanceof Patient) {
                $patient->update([
                    'name' => $request->get('name'),
                    'birth_date' => $request->get('birth_date'),
                    'updated_at' => now()
                ]);

                return response()->json([
                    'status' => true,
                    'message' => 'Patient updated Successfully!',
                    'patient' => $patient
                ]);
            }

            return response()->json([
                'status' => false,
                'message' => 'Patient not found'
            ], 201);
        } catch (Throwable $throwable) {
            Log::error($throwable);
            return response()->json([
                'status' => false,
                'message' => 'Internal Error',
                'error' => 'Failed to update patient'
            ], 500);
        }
    }

    /**
     * @param int $patient_id
     * @return JsonResponse|RedirectResponse
     */
    public function destroy(int $patient_id): JsonResponse|RedirectResponse
    {
        try {
            /** @var Patient $patient */
            $patient = Patient::where('id', $patient_id)->first();

            if (!$patient) {
                return response()->json([
                    'status' => false,
                    'message' => 'Profile not found!',
                    'error' => 'Profile not found!'
                ], 202);
            }

            $patient->initializeSoftDeletes();

            $patient->delete();

            return response()->json([
                'message' => 'Profile deleted',
                'status' => true,
            ]);
        } catch (Throwable $throwable) {
            Log::notice($throwable);
            return response()->json([
                'status' => false,
                'message' => 'Internal server error!'
            ], 500);
        }
    }

    /**
     * @param string $user_email
     * @return JsonResponse
     */
    public function searchByUserEmail(string $user_email): JsonResponse
    {
        try {
            $validate_patient = Validator::make(['email' => $user_email],
                [
                    'email' => 'required|email'
                ]);

            if ($validate_patient->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'validation error',
                    'error' => $validate_patient->errors()->first()
                ], 202);
            }

            /** @var User $user */
            $user = User::where('email', $user_email)->first();

            if (!($user instanceof User)) {
                return response()->json([
                    'status' => true,
                    'message' => 'Email not found',
                    'error' => 'Email not found!'
                ], 202);
            }

            $patients = $user->patients()->get();

            return response()->json([
                'status' => true,
                'patients' => $patients
            ]);
        } catch (Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => $th->getMessage()
            ], 500);
        }
    }
}
