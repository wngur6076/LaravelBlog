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

// $factory->define(App\User::class, function (Faker\Generator $faker) {
//     static $password;

//     return [
//         'name' => $faker->name,
//         'email' => $faker->safeEmail,
//         'password' => $password ?: $password = bcrypt('secret'),
//         'remember_token' => str_random(10),
//     ];
// });

$factory->define(App\Comment::class, function (Faker\Generator $faker) {
    $articlesIds = App\Article::pluck('id')->toArray();
    $userIds = App\User::pluck('id')->toArray();


    return [
        'content' => $faker->paragraph,
        'commentable_type' => App\Article::class,
        'commentable_id' => function () use ($faker, $articlesIds) {
            return $faker->randomElement($articlesIds);
        },
        'user_id' => function () use ($faker, $userIds) {
            return $faker->randomElement($userIds);
        },
    ];
});