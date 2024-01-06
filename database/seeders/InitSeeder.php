<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;
use App\Services\CreateMemberService;
use Illuminate\Http\Request;


class InitSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create();
        for ($i = 1; $i <= 10000; $i++) {
            $milliseconds = $faker->numberBetween($min = 1, $max = 100000);
            $email = $faker->email;

            $requestData = [
                'email' => $email,
                'milliseconds' => $milliseconds,
            ];

            $request = new Request($requestData);

            CreateMemberService::create($request);
        }
    }
}
