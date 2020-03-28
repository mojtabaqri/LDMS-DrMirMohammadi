<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Demand;
use Faker\Generator as Faker;

$factory->define(Demand::class, function (Faker $faker) {
    return [
        'title' => $faker->name,
        'content' => $faker->text,
        'user_id' => 2,
        'state' =>1,
    ];
});
