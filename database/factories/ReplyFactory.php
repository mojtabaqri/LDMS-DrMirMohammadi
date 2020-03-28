<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Reply;
use Faker\Generator as Faker;

$factory->define(Reply::class, function (Faker $faker) {
    return [
     'admin_id'=>2,
     'text'=>$faker->text,
     'demand_id'=>factory('App\Demand')->create()->id,
    ];
});
