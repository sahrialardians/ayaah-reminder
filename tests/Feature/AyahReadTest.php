<?php

use App\Models\AyahRead;
use App\Models\User;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;

it('can create a reading entry', function () {
    $user = User::factory()->create();

    $ayahRead = AyahRead::factory()->create([
        'user_id' => $user->id,
        'surah_number' => 1,
        'ayah_number' => 1,
    ]);

    expect($ayahRead->user_id)->toBe($user->id);
    expect($ayahRead->surah_number)->toBe(1);
    expect($ayahRead->ayah_number)->toBe(1);
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
        'ayah_number' => 5,
    ]);

    $latest = AyahRead::factory()->create([
        'user_id' => $user->id,
        'read_at' => now(),
        'ayah_number' => 10,
    ]);

    expect($user->latestAyahRead->id)->toBe($latest->id);
    expect($user->latestAyahRead->ayah_number)->toBe(10);
});

it('can store a new ayah read via controller', function () {
    $user = User::factory()->create();

    $response = $this->actingAs($user)
        ->post(route('ayah.store'), [
            'surah_number' => 1,
            'ayah_number' => 7,
        ]);

    $response->assertRedirect();
    $this->assertDatabaseHas('ayah_reads', [
        'user_id' => $user->id,
        'surah_number' => 1,
        'ayah_number' => 7,
    ]);
});

it('validates ayah number against surah length', function () {
    $user = User::factory()->create();

    // Surah 1 (Al-Fatihah) has 7 ayahs. Trying to save 8 should fail.
    $response = $this->actingAs($user)
        ->post(route('ayah.store'), [
            'surah_number' => 1,
            'ayah_number' => 8,
        ]);

    $response->assertInvalid(['ayah_number']);
});
