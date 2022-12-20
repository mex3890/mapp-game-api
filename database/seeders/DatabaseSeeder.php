<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\Answer;
use App\Models\Patient;
use App\Models\Professional;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->insert([
            'name' => 'Admin',
            'email' => 'admin@email.com',
            'email_verified_at' => now(),
            'phone' => '21965656565',
            'role' => 1,
            'password' => Hash::make('password'),
            'remember_token' => Str::random(10),
        ]);

        DB::table('users')->insert([
            'name' => 'Super Admin',
            'email' => 'super.admin@email.com',
            'email_verified_at' => now(),
            'phone' => '21965856565',
            'role' => 2,
            'password' => Hash::make('password'),
            'remember_token' => Str::random(20),
        ]);

        DB::table('companies')->insert([
            'name' => 'Psychology Institute of Jacaré',
            'cnpj' => Str::random(14),
            'license' => Str::random(10),
            'validated_at' => now(),
            'created_at' => now()
        ]);

        DB::table('professionals')->insert([
            'user_id' => 2,
            'company_id' => 1,
            'license' => "CRM/RJ123127",
            'state' => 'RJ',
            'validated_at' => now(),
            'created_at' => now()
        ]);

        DB::table('companies_professionals')->insert([
            'professional_id' => 1,
            'company_id' => 1,
            'created_at' => now()
        ]);

        DB::table('patients')->insert([
            'name' => 'Patient 1',
            'birth_date' => now(),
            'user_id' => 1,
            'created_at' => now()
        ]);

        DB::table('professional_patient')->insert([
            'patient_id' => 1,
            'professional_id' => 1,
            'created_at' => now()
        ]);

        User::factory(100)->create();

        DB::table('levels')->insert([
            'right_value' => 1,
        ]);

        DB::table('levels')->insert([
            'right_value' => 4,
        ]);

        DB::table('levels')->insert([
            'right_value' => 2,
        ]);

        DB::table('levels')->insert([
            'right_value' => 1,
        ]);

        DB::table('levels')->insert([
            'right_value' => 3,
        ]);

        DB::table('options')->insert([
            'value' => 1,
            'level_id' => 1
        ]);

        DB::table('options')->insert([
            'value' => 2,
            'level_id' => 1
        ]);

        DB::table('options')->insert([
            'value' => 3,
            'level_id' => 1
        ]);

        DB::table('options')->insert([
            'value' => 4,
            'level_id' => 1
        ]);

        DB::table('options')->insert([
            'value' => 1,
            'level_id' => 2
        ]);

        DB::table('options')->insert([
            'value' => 2,
            'level_id' => 2
        ]);

        DB::table('options')->insert([
            'value' => 3,
            'level_id' => 2
        ]);

        DB::table('options')->insert([
            'value' => 4,
            'level_id' => 2
        ]);

        DB::table('options')->insert([
            'value' => 1,
            'level_id' => 3
        ]);

        DB::table('options')->insert([
            'value' => 2,
            'level_id' => 3
        ]);

        DB::table('options')->insert([
            'value' => 3,
            'level_id' => 3
        ]);

        DB::table('options')->insert([
            'value' => 4,
            'level_id' => 3
        ]);

        DB::table('options')->insert([
            'value' => 1,
            'level_id' => 4
        ]);

        DB::table('options')->insert([
            'value' => 2,
            'level_id' => 4
        ]);

        DB::table('options')->insert([
            'value' => 3,
            'level_id' => 4
        ]);

        DB::table('options')->insert([
            'value' => 4,
            'level_id' => 4
        ]);

        DB::table('options')->insert([
            'value' => 1,
            'level_id' => 5
        ]);

        DB::table('options')->insert([
            'value' => 2,
            'level_id' => 5
        ]);

        DB::table('options')->insert([
            'value' => 3,
            'level_id' => 5
        ]);

        DB::table('options')->insert([
            'value' => 4,
            'level_id' => 5
        ]);

        DB::table('answers')->insert([
            'patient_id' => 1,
            'value' => 1,
            'level_id' => 1,
            'status' => true,
            'created_at' => now()
        ]);

        DB::table('answers')->insert([
            'patient_id' => 1,
            'value' => 4,
            'level_id' => 2,
            'status' => true,
            'created_at' => now()->addDay()
        ]);

        DB::table('answers')->insert([
            'patient_id' => 1,
            'value' => 4,
            'level_id' => 3,
            'status' => true,
            'created_at' => now()->addDay()->addDay()
        ]);

        DB::table('answers')->insert([
            'patient_id' => 1,
            'value' => 4,
            'level_id' => 4,
            'status' => true,
            'created_at' => now()->addDay()
        ]);

        DB::table('answers')->insert([
            'patient_id' => 1,
            'value' => 1,
            'level_id' => 1,
            'status' => true,
            'created_at' => now()->addHour()
        ]);

        DB::table('answers')->insert([
            'patient_id' => 1,
            'value' => 4,
            'level_id' => 2,
            'status' => true,
            'created_at' => now()->addDay()->addHour()
        ]);

        DB::table('answers')->insert([
            'patient_id' => 1,
            'value' => 4,
            'level_id' => 3,
            'status' => true,
            'created_at' => now()->addDay()->addDay()->addHour()->addHour()
        ]);

        DB::table('answers')->insert([
            'patient_id' => 1,
            'value' => 4,
            'level_id' => 4,
            'status' => true,
            'created_at' => now()->addDay()->addHour()->addHour()->addHour()->addHour()->addHour()->addHour()
        ]);

        DB::table('answers')->insert([
            'patient_id' => 1,
            'value' => 4,
            'level_id' => 4,
            'status' => true,
            'created_at' => now()->addDay()
        ]);

        DB::table('answers')->insert([
            'patient_id' => 1,
            'value' => 1,
            'level_id' => 1,
            'status' => true,
            'created_at' => now()->addHour()
        ]);

        DB::table('answers')->insert([
            'patient_id' => 1,
            'value' => 4,
            'level_id' => 2,
            'status' => true,
            'created_at' => now()->addDay()->addHour()
        ]);

        DB::table('answers')->insert([
            'patient_id' => 1,
            'value' => 4,
            'level_id' => 3,
            'status' => true,
            'created_at' => now()->addDay()->addDay()->addHour()->addHour()
        ]);

        DB::table('answers')->insert([
            'patient_id' => 1,
            'value' => 4,
            'level_id' => 4,
            'status' => true,
            'created_at' => now()->addDay()->addHour()->addHour()->addHour()->addHour()->addHour()->addHour()
        ]);

        DB::table('answers')->insert([
            'patient_id' => 1,
            'value' => 4,
            'level_id' => 4,
            'status' => true,
            'created_at' => now()->addDay()
        ]);

        DB::table('answers')->insert([
            'patient_id' => 1,
            'value' => 1,
            'level_id' => 1,
            'status' => true,
            'created_at' => now()->addHour()
        ]);

        DB::table('answers')->insert([
            'patient_id' => 1,
            'value' => 4,
            'level_id' => 2,
            'status' => true,
            'created_at' => now()->addDay()->addHour()
        ]);

        DB::table('answers')->insert([
            'patient_id' => 1,
            'value' => 4,
            'level_id' => 3,
            'status' => true,
            'created_at' => now()->addDay()->addDay()->addHour()->addHour()
        ]);

        DB::table('answers')->insert([
            'patient_id' => 1,
            'value' => 4,
            'level_id' => 4,
            'status' => true,
            'created_at' => now()->addDay()->addHour()->addHour()->addHour()->addHour()->addHour()->addHour()
        ]);

        Patient::factory(400)->create();
    }
}
