<?php

use App\Models\User;
use App\Models\AyahRead;

it('can view history page', function () {
    $user = User::factory()->has(AyahRead::factory()->count(25))->create();

    $response = $this->actingAs($user)
        ->get(route('history'));

    $response->assertOk();
    $response->assertInertia(fn ($page) => $page
        ->component('History')
        ->has('history.data', 20) // First page of 20
        ->has('surahs')
    );
});
