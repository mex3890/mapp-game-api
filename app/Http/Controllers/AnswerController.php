<?php

namespace App\Http\Controllers;

use App\Models\Answer;
use App\Models\Level;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Throwable;

class AnswerController extends Controller
{
    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function store(Request $request): JsonResponse
    {
        try {
            $current_time = now();

            $answers = $request->get('answers');

            $answers = explode('|', $answers);

            foreach ($answers as $answer) {
                $answer = explode(':', $answer);
                $status = false;

                $level = str_replace('l', '', $answer[0]);
                $value = str_replace('o', '', $answer[1]);

                /** @var Level $level_answer */
                $level_answer = Level::where('id', $level)->first();

                if ($value == $level_answer->right_value) {
                    $status = true;
                }

                $answer = new Answer([
                    'level_id' => $level,
                    'patient_id' => $request->get('patient_id'),
                    'value' => $value,
                    'status' => $status,
                    'created_at' => $current_time,
                ]);

                $answer->save();
            }

            return response()->json([
                'status' => true,
                'message' => 'Answers saved!',
            ]);
        } catch (Throwable $throwable) {
            Log::error($throwable->getMessage());

            return response()->json([
                'status' => false,
                'message' => 'Internal Error',
                'error' => 'Failed to register'
            ], 500);
        }
    }
}
