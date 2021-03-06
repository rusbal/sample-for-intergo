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

/** @var \Illuminate\Database\Eloquent\Factory $factory */
$factory->define(App\User::class, function (Faker\Generator $faker) {
    static $password;

    return [
        'name' => $faker->name,
        'email' => $faker->unique()->safeEmail,
        'password' => $password ?: $password = bcrypt('secret'),
        'verified' => true,
        'remember_token' => str_random(10),
    ];
});

$factory->define(App\AmazonMws::class, function (Faker\Generator $faker) {
    return [
        'merchant_id' => str_random(14),
        'marketplace_id' => str_random(13),
        'key_id' => str_random(20),
        'secret_key' => str_random(40),
    ];
});
