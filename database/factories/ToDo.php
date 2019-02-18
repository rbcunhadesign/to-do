<?php

use Faker\Generator as Faker;

$factory->define(\App\ToDo::class, function (Faker $faker) {
    return [
        'user_id' => function() {
            return factory(\App\User::class)->create()->id;
        },
        'title' => $faker->words(4, true),
        'complete' => rand(0, 1),
    ];
});
