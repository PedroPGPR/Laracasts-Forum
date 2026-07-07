<?php

use App\Support\PostFixtures;

Route::middleware('api')->group(function () {
    Route::get('post-content', fn () => PostFixtures::getFixturePosts()->random());
});

Route::middleware('web')->group(function () {});
