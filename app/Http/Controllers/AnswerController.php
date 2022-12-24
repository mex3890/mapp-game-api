<?php

namespace App\Http\Controllers;

use App\Http\Resources\PatientResource;
use App\Models\Answer;
use App\Models\Level;
use App\Models\Patient;
use App\Models\User;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Mail\Mailer;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
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

    public function downloadPdf(int $patient_id, int $user_id)
    {
        try {
            $user = User::where('id', $user_id)->first();

            if (!($user instanceof User)) {
                return response()->json([
                    'status' => true,
                    'error' => 'Professional not found'
                ], 202);
            }

            $patient = Patient::where('id', $patient_id)->first();

            if (!($patient instanceof Patient)) {
                return response()->json([
                    'status' => true,
                    'error' => 'Patient not found'
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

            $resp = [];
            $resp['answers'] = $answers;
            $resp['patient'] = $patient;
            $resp['count_plays'] = count($answers);
            $resp['professional_name'] = $user->name;
            date_default_timezone_set('America/Sao_Paulo');

            $resp['current_date'] = date('d/m/Y H:i:s');

            view()->share('resp', $resp);

            $pdf = PDF::loadView('pdf/pdf-template');

            return $pdf->download();

        } catch (Throwable $th) {
            Log::notice($th);
            return response()->json([
                'status' => false,
                'message' => $th->getMessage()
            ], 500);
        }
    }
}
