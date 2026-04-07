<?php

use App\Models\AyahRead;
use App\Models\User;

it('can create a reading entry', function () {
    $user = User::factory()->create();

    $ayahRead = AyahRead::factory()->create([
        'user_id' => $user->id,
        'surah_number' => 1,
        'start_ayah' => 1,
        'end_ayah' => 5,
    ]);

    expect($ayahRead->user_id)->toBe($user->id);
    expect($ayahRead->surah_number)->toBe(1);
    expect($ayahRead->start_ayah)->toBe(1);
    expect($ayahRead->end_ayah)->toBe(5);
});

it('belongs to a user', function () {
    $ayahRead = AyahRead::factory()->create();

    expect($ayahRead->user)->toBeInstanceOf(User::class);
});

it('can get latest ayah read for user', function () {
    $user = User::factory()->create();

    AyahRead::factory()->create([
        'user_id' => $user->id,
        'read_at' => now()->subDay(),
        'start_ayah' => 1,
        'end_ayah' => 5,
    ]);

    $latest = AyahRead::factory()->create([
        'user_id' => $user->id,
        'read_at' => now(),
        'start_ayah' => 6,
        'end_ayah' => 10,
    ]);

    expect($user->latestAyahRead->id)->toBe($latest->id);
    expect($user->latestAyahRead->end_ayah)->toBe(10);
});

it('can store a new ayah read via controller', function () {
    $user = User::factory()->create();

    $response = $this->actingAs($user)
        ->post(route('ayah.store'), [
            'surah_number' => 1,
            'start_ayah' => 1,
            'end_ayah' => 7,
        ]);

    $response->assertRedirect();
    $this->assertDatabaseHas('ayah_reads', [
        'user_id' => $user->id,
        'surah_number' => 1,
        'start_ayah' => 1,
        'end_ayah' => 7,
    ]);
});

it('validates end ayah number against surah length', function () {
    $user = User::factory()->create();

    // Surah 1 (Al-Fatihah) has 7 ayahs. Trying to save 8 should fail.
    $response = $this->actingAs($user)
        ->post(route('ayah.store'), [
            'surah_number' => 1,
            'start_ayah' => 1,
            'end_ayah' => 8,
        ]);

    $response->assertInvalid(['end_ayah']);
});
