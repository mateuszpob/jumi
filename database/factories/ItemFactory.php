<?php

$factory->define(App\Item::class, function ($faker) {

	$desc = 'The visit method makes a GET request into the application. The see method asserts that we should see the given text in the response returned by the application. The dontSee method asserts that the given text is not returned in the application response. This is the most basic application test available in Laravel.';
    return [
        'name' => $faker->name,
        'description' => substr($desc, 0, rand(35, strlen($desc))),
        'image_path' => '2015-09-26/test.jpg',
        'price' => rand(10, 500),
        'weight' => rand(1,100),
        'count' => 10,
        'active' => true,
        'created_at' => date('Y-m-d G:i:s'),
        'updated_at' => date('Y-m-d G:i:s'),
    ];
});