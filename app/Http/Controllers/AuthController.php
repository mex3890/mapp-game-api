<?php

namespace App\Http\Controllers;

use App\Mail\SendAnswerMail;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;
use Throwable;

class AuthController extends Controller
{
    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function register(Request $request): JsonResponse
    {
        try {
            $validateUser = Validator::make($request->all(),
                [
                    'name' => 'required',
                    'email' => 'required|email|unique:users',
                    'phone' => 'required|min:10|unique:users',
                    'password' => [
                        Password::required(),
                        Password::min(8)->letters()->mixedCase()->numbers()->symbols(),
                    ],
                ]);

            if ($validateUser->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'validation error',
                    'error' => $validateUser->errors()->first()
                ], 202);
            }

            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'phone' => $request->phone
            ]);

            return response()->json([
                'status' => true,
                'message' => 'Register Successfully, let\'s make login!',
                'token' => $user->createToken("API TOKEN")->plainTextToken
            ]);

        } catch (Throwable $th) {
            Log::error($th->getMessage());
            return response()->json([
                'status' => false,
                'message' => 'Internal Error',
                'error' => 'Failed to register'
            ], 500);
        }
    }

    /**
     * Login The User
     * @param Request $request
     * @return JsonResponse
     */
    public function login(Request $request): JsonResponse
    {
        try {
            $validateUser = Validator::make($request->all(),
                [
                    'email' => 'required|email',
                    'password' => 'required'
                ]);

            if ($validateUser->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'validation error',
                    'error' => $validateUser->errors()->first()
                ], 202);
            }

            $user = User::where('email', $request->get('email'))->first();

            if ($user instanceof User) {
                $check = Hash::check($request->get('password'), $user->temporary_password);

                if (!$check) {
                    if (!Auth::attempt($request->only(['email', 'password']))) {
                        return response()->json([
                            'status' => false,
                            'message' => 'Credentials does not match with our record.',
                        ], 203);
                    }
                }
            }

            $user = User::where('email', $request->email)->first();

            if ($user->role === 1) {

                return response()->json([
                    'status' => true,
                    'message' => 'User Logged In Successfully',
                    'token' => $user->createToken("API TOKEN")->plainTextToken,
                    'user' => [
                        'id' => $user->id,
                        'email' => $user->email,
                        'name' => $user->name,
                        'phone' => $user->phone,
                        'role' => $user->role
                    ]
                ]);
            }

            return response()->json([
                'status' => true,
                'message' => 'Professional Logged In Successfully',
                'token' => $user->createToken("API TOKEN")->plainTextToken,
                'user' => [
                    'id' => $user->id,
                    'email' => $user->email,
                    'name' => $user->name,
                    'phone' => $user->phone,
                    'role' => $user->role
                ]
            ]);
        } catch (Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => $th->getMessage()
            ], 500);
        }
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function update(Request $request): JsonResponse
    {
        try {
            $validateUser = Validator::make($request->all(),
                [
                    'name' => 'required',
                    'email' => ['required', 'email', Rule::unique('users', 'email')->ignore($request->get('id'))],
                    'phone' => [
                        'required',
                        'min:10',
                        Rule::unique('users', 'phone')->ignore($request->get('id'))
                    ],
                    'password_confirmation' => 'required',
                    'password' => [
                        Password::min(8)->letters()->mixedCase()->numbers()->symbols(),
                    ],
                ]);

            /** @var User $user */
            $user = User::where('id', $request->get('id'))->first();

            if ($validateUser->fails()) {
                Log::notice($validateUser->errors());
                return response()->json([
                    'status' => false,
                    'message' => 'validation error',
                    'error' => $validateUser->errors()->first()
                ], 202);
            }

            $password_confirmation = $request->get('password_confirmation');
            if (!Hash::check($password_confirmation, $user->password) &&
                !Hash::check($password_confirmation, $user->temporary_password)
            ) {
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
                    'phone' => $request->get('phone')
                ]);

                return response()->json([
                    'status' => true,
                    'message' => 'Update Successfully!',
                    'token' => $user->createToken("API TOKEN")->plainTextToken
                ]);
            }

            if (Hash::check($request->get('password'), $user->password)) {
                return response()->json([
                    'status' => false,
                    'message' => 'validation error',
                    'error' => 'The new password must be different from the previous password.'
                ], 202);
            }

            $user->update([
                'email' => $request->get('email'),
                'name' => $request->get('name'),
                'phone' => $request->get('phone'),
                'password' => Hash::make($request->get('password')),
                'temporary_password' => null
            ]);

            return response()->json([
                'status' => true,
                'message' => 'Update Successfully, let\'s make login!'
            ], 201);

        } catch (Throwable $th) {
            Log::error($th->getMessage());
            return response()->json([
                'status' => false,
                'message' => 'Internal Error',
                'error' => 'Failed to update'
            ], 500);
        }
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function forgotPassword(Request $request): JsonResponse
    {
        try {
            $validateEmail = Validator::make($request->all(), ['email' => 'required|email']);

            if ($validateEmail->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'validation error',
                    'error' => $validateEmail->errors()->first()
                ], 202);
            }

            $reset_email = $request->get('email');

            /** @var User $user */
            $user = User::where('email', $reset_email)->first();

            if ($user instanceof User) {
                $temporary_password = Str::random(15);
                $description = "This is an automatic email due to a password exchange request, use the temporary password below to login and change your password in the Mapp Game app.";

                $mail = new SendAnswerMail($user->name, $description, $temporary_password);
                Mail::to($reset_email)->send($mail);

                $user->update([
                    'temporary_password' => Hash::make($temporary_password)
                ]);
            }

            return response()->json([
                'status' => true,
                'message' => 'If the email is registered an email will be sent! Look your Email inbox.',
            ]);
        } catch (Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => $th->getMessage()
            ], 500);
        }
    }
}
