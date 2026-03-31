<?php

use App\Models\User;
use App\Models\ReminderSetting;

it('can view reminder settings page', function () {
    $user = User::factory()->create();

    $response = $this->actingAs($user)
        ->get(route('reminders.edit'));

    $response->assertOk();
});

it('can update reminder settings', function () {
    $user = User::factory()->create();

    $response = $this->actingAs($user)
        ->patch(route('reminders.update'), [
            'is_enabled' => true,
            'reminder_time' => '09:30',
            'timezone' => 'Asia/Jakarta',
        ]);

    $response->assertRedirect();
    
    $this->assertDatabaseHas('reminder_settings', [
        'user_id' => $user->id,
        'is_enabled' => true,
        'reminder_time' => '09:30',
        'timezone' => 'Asia/Jakarta',
    ]);
});

it('validates reminder settings', function () {
    $user = User::factory()->create();

    $response = $this->actingAs($user)
        ->patch(route('reminders.update'), [
            'is_enabled' => true,
            'reminder_time' => 'invalid-time',
            'timezone' => 'Invalid/Timezone',
        ]);

    $response->assertInvalid(['reminder_time', 'timezone']);
});
