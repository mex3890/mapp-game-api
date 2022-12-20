<?php

namespace App\Http\Controllers;

use App\Http\Resources\ProfessionalResource;
use App\Models\Professional;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;
use Throwable;

class ProfessionalController extends Controller
{
    /**
     * @param int $user_id
     * @return JsonResponse
     */
    public function patients(int $user_id): JsonResponse
    {
        try {
            $professional = Professional::where('user_id', $user_id)->first();

            if ($professional instanceof Professional) {
                $patients = $professional->patients()->get();

                return response()->json([
                    'status' => true,
                    'patients' => $patients
                ]);
            }

            return response()->json([
                'status' => true,
                'message' => 'Professional not found'
            ], 202);
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
    public function show(int $user_id): JsonResponse
    {
        try {
            $professional = new ProfessionalResource(Professional::where('user_id', $user_id)->first());

            Log::notice($professional->name);
            Log::notice($professional->state);
            Log::notice($professional->license);
            return response()->json([
                'status' => true,
                'professional' => $professional
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
            $validate_professional = Validator::make($request->all(),
                [
                    'license' => 'required|unique:professionals|regex:/^CRM\/[a-zA-Z]{2}[0-9]{6}$/',
                ], [
                    'required' => 'The :attribute is required',
                    'license.regex' => "The format of CRM is invalid."
                ]);


            if ($validate_professional->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'validation error',
                    'error' => $validate_professional->errors()->first()
                ], 202);
            }

            $state = substr($request->get('license'), 4, 2);

            $professional = new Professional([
                'user_id' => $user_id,
                'license' => $request->get('license'),
                'state' => $state,
                'validated_at' => now()
            ]);

            $professional->save();

            $user = $professional->user()->first();

            $user->update([
                'role' => 2
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
                'error' => 'Failed to save license'
            ], 500);
        }
    }

    /**
     * @param int $user_id
     * @param Request $request
     * @return JsonResponse
     */
    public function update(int $user_id, Request $request): JsonResponse
    {
        try {
            /** @var Professional $professional */
            $professional = Professional::where('user_id', $user_id)->first();
            $user = User::where('id', $user_id)->first();

            $validate_professional = Validator::make($request->all(),
                [
                    'email' =>
                        [
                            'required',
                            'email',
                            Rule::unique('users', 'email')->ignore($user_id)
                        ],
                    'name' => 'required',
                    'phone' => [
                        'required',
                        'min:10',
                        Rule::unique('users', 'phone')->ignore($user_id)
                    ],
                    'license' => [
                        'required',
                        Rule::unique('professionals', 'license')->ignore($professional->id),
                        'regex:/^CRM\/[a-zA-Z]{2}[0-9]{6}$/'
                    ],
                    'password_confirmation' => 'required',
                    'password' => [
                        Password::min(8)->letters()->mixedCase()->numbers()->symbols(),
                    ]
                ],
                [
                    'required' => 'The :attribute is required',
                    'license.regex' => "The format of CRM is invalid."
                ]);

            Log::notice($professional->id . ' '. $user_id);
            if ($validate_professional->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'validation error',
                    'error' => $validate_professional->errors()->first()
                ], 202);
            }

            if (!Hash::check($request->get('password_confirmation'), $user->password)) {
                return response()->json([
                    'status' => false,
                    'message' => 'validation error',
                    'error' => 'Credentials does not match with our record.'
                ], 202);
            }

            if ($request->get('password') === 'PasswordDefaultUnset2022@#*$') {
                $user->update([
                    'email' => $request->get('email'),
                    'name' => $request->get('name'),
                    'phone' => $request->get('phone'),
                    'updated_at' => now()
                ]);
            } else if (Hash::check($request->get('password'), $user->password)) {
                return response()->json([
                    'status' => false,
                    'message' => 'validation error',
                    'error' => 'The new password must be different from the previous password.'
                ], 202);
            } else if ($password = $request->get('password')) {
                $user->update([
                    'email' => $request->get('email'),
                    'name' => $request->get('name'),
                    'phone' => $request->get('phone'),
                    'password' => Hash::make($password),
                    'temporary_password' => null,
                    'updated_at' => now()
                ]);
            } else {
                $user->update([
                    'email' => $request->get('email'),
                    'name' => $request->get('name'),
                    'phone' => $request->get('phone'),
                    'updated_at' => now()
                ]);
            }

            if ($professional instanceof Professional) {
                if ($request->get('license') && $request->get('license') !== $professional->license) {

                    $state = substr($request->get('license'), 4, 2);

                    $professional->update([
                        'license' => $request->get('license'),
                        'state' => $state,
                        'validated_at' => null
                    ]);

                    return response()->json([
                        'status' => true,
                        'message' => 'Professional updated Successfully!'
                    ], 201);
                }

                if (isset($password)) {
                    return response()->json([
                        'status' => true,
                        'message' => 'Professional updated Successfully!'
                    ], 201);
                }

                return response()->json([
                    'status' => true,
                    'message' => 'Professional updated Successfully!'
                ]);
            }

            return response()->json([
                'status' => false,
                'message' => 'Professional not found',
                'error' => 'Professional not found'
            ], 202);
        } catch (Throwable $throwable) {
            Log::error($throwable);
            return response()->json([
                'status' => false,
                'message' => 'Internal Error',
                'error' => 'Failed to update professional'
            ], 500);
        }
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function patientStore(Request $request): JsonResponse
    {
        try {
            /** @var Professional $professional */
            $professional = Professional::where('user_id', $request->get('user_id'))->first();

            if ($log = DB::table('professional_patient')
                    ->where('professional_id', $professional->id)
                    ->where('patient_id', $request->get('patient_id'))
                    ->count() > 0) {
                Log::notice($log);
                return response()->json([
                    'status' => false,
                    'message' => 'Patient already in list!',
                ], 202);
            }

            DB::table('professional_patient')->insert([
                'patient_id' => $request->get('patient_id'),
                'professional_id' => $professional->id,
                'created_at' => now()
            ]);

            return response()->json([
                'status' => true,
                'message' => 'Successfully!'
            ]);

        } catch (Throwable $throwable) {
            Log::error($throwable);
            return response()->json([
                'status' => false,
                'message' => 'Internal Error',
                'error' => 'Failed to save license'
            ], 500);
        }
    }
}
