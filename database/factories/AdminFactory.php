<?php

use Faker\Generator as Faker;

$factory->define(Sitetpl\Models\Admin::class, function (Faker $faker) {
    return ['name' => $faker->name, 'email' => $faker->unique()->safeEmail, 'password' => bcrypt($faker->unique()->password), // secret
        'remember_token' => str_random(10),];
});
