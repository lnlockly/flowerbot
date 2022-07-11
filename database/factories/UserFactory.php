<?php

/** @var Factory $factory */

use App\User;
use Faker\Generator as Faker;
use Illuminate\Database\Eloquent\Factory;

$factory->define(User::class, function (Faker $faker) {
    return [
        'name' => 'Admin',
        'current_shop' => null,
        'role' => 'admin',
        'username' => $faker->userName,
        'remember_token' => null,
    ];
});
