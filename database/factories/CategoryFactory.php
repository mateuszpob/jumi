<?php

$factory->define(App\Category::class, function ($faker) {

	$desc = 'The visit method makes a GET request into the application. The see method asserts that we should see the given text in the response returned by the application. The dontSee method asserts that the given text is not returned in the application response. This is the most basic application test available in Laravel.';
    return [
        'name' => $faker->name,
//        'id_upper' => rand(3,9),
        'image_path' => '2015-09-26/test_category.jpeg',
        'description' => 'The dontSee method asserts that the given text is not returned in the application response. This is the most basic application test available in Laravel',
        'active' => true,
        'created_at' => date('Y-m-d G:i:s'),
        'updated_at' => date('Y-m-d G:i:s'),
    ];
});
