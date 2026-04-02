<?php

use App\Models\AyahRead;
use App\Models\User;

it('can view history page', function () {
    $user = User::factory()->has(AyahRead::factory()->count(25))->create();

    $response = $this->actingAs($user)
        ->get(route('history'));

    $response->assertOk();
    $response->assertInertia(fn ($page) => $page
        ->component('History')
        ->has('history.data', 15) // First page of 15
        ->has('surahs')
    );
});
