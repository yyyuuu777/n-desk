<?php

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| Here you may define all of your model factories. Model factories give
| you a convenient way to create models for testing and seeding your
| database. Just tell the factory how a default model should look.
|
*/

$factory->define(App\Model\User::class, function (Faker\Generator $faker) {
    return [
        'name' => $faker->name,
        'email' => $faker->email,
        'uid' => $faker->randomDigitNotNull,
        'password' => $faker->password,
        'status' => 0,
        'file_number' => 0,
        'space' => 100,
        'is_vip' => 0,
        'vip_datetime' => $faker->date(),
        'balance' => 0,
        'apply_balance' => 0,
        'total_balance' => 0,
        'file_permission' => 1,
        'last_login' => null,
    ];
});

$factory->define(App\Model\File::class, function (Faker\Generator $faker) {
    return [
        'title' => $faker->name,
        'status' => $faker->numberBetween(1,5),
        'type' => $faker->numberBetween(1,5),
        'size' => $faker->numberBetween(3,20),
        'sha1' => $faker->sha1,
        'downloads' => $faker->numberBetween(10,100),
        'user_id' => 1,
        'permission' => $faker->numberBetween(1,3),
        'password' => null,
        'file_id' => 1,
        'file_path' => null,
    ];
});


