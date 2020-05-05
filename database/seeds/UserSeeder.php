<?php


use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Faker\Factory as Faker;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        $faker = Faker::create('id_ID');

        for($i = 1; $i <= 50; $i++){
        DB::table('user')->insert([
            'firstname' => $faker->firstName,
            'lastname' => $faker->lastName,
            'gender' => $faker->randomElement($array = array ('male','female')),
            'status' => $faker->randomElement($array = array ('Active','Pending','Banned','Loss')),
            'email' => $faker->email,
            'city' => $faker->city,
            'address' => $faker->address,
            'phone' => $faker->phoneNumber,
            'created_at' => $faker->date($format = 'Y-m-d', $max = 'now')
        ]);
    }
}
}
