<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AnswersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(): void
    {
        for ($i = 0; $i < 30; $i++) {
            $date_time = fake()->dateTime;

            DB::table('answers')->insert([
                'patient_id' => 1,
                'value' => $value = fake()->randomElement([1, 2, 4]),
                'level_id' => 1,
                'status' => $value === 1 || $i % 5 === 0,
                'created_at' => $date_time
            ]);

            DB::table('answers')->insert([
                'patient_id' => 1,
                'value' => $value = fake()->randomElement([1, 2, 3, 4]),
                'level_id' => 2,
                'status' => $value === 4 || $i % 5 === 0,
                'created_at' => $date_time
            ]);

            DB::table('answers')->insert([
                'patient_id' => 1,
                'value' => $value = fake()->randomElement([1, 2, 3, 4]),
                'level_id' => 3,
                'status' => $value === 2 || $i % 5 === 0,
                'created_at' => $date_time
            ]);

            DB::table('answers')->insert([
                'patient_id' => 1,
                'value' => $value = fake()->randomElement([1, 2]),
                'level_id' => 4,
                'status' => $value === 1 || $i % 5 === 0,
                'created_at' => $date_time
            ]);

            DB::table('answers')->insert([
                'patient_id' => 1,
                'value' => $value = fake()->randomElement([1, 3, 4]),
                'level_id' => 5,
                'status' => $value === 3 || $i % 5 === 0,
                'created_at' => $date_time
            ]);
        }
    }
}
