<?php

namespace Tests\Feature;

use App\Models\AyahRead;
use App\Models\ReminderSetting;
use App\Models\User;
use App\Notifications\AyahReminder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;

class ReminderSchedulingTest extends TestCase
{
    use RefreshDatabase;

    public function test_ayah_reminder_notification_formats_range_correctly(): void
    {
        $user = User::factory()->create();

        // Test single ayah
        $notificationSingle = new AyahReminder('Al-Faatiha', 1, 1);
        $mailSingle = $notificationSingle->toMail($user);
        $this->assertStringContainsString('Al-Faatiha', $mailSingle->render());
        $this->assertStringContainsString('Ayah', $mailSingle->render());
        $this->assertStringContainsString('1', $mailSingle->render());

        // Test range
        $notificationRange = new AyahReminder('Al-Faatiha', 1, 7);
        $mailRange = $notificationRange->toMail($user);
        $this->assertStringContainsString('Ayah', $mailRange->render());
        $this->assertStringContainsString('1-7', $mailRange->render());
    }

    public function test_scheduler_sends_reminders_to_enabled_users_at_correct_time(): void
    {
        Notification::fake();

        // Use a fixed time for the test
        $now = now('UTC')->startOfMinute();
        $timeString = $now->format('H:i:s');

        // User 1: Enabled and time matches
        $user1 = User::factory()->create(['name' => 'User One']);
        ReminderSetting::factory()->create([
            'user_id' => $user1->id,
            'is_enabled' => true,
            'reminder_time' => $timeString,
            'timezone' => 'UTC',
        ]);
        AyahRead::factory()->create([
            'user_id' => $user1->id,
            'surah_number' => 1, // Al-Faatiha
            'start_ayah' => 1,
            'end_ayah' => 7,
        ]);

        // User 2: Disabled but time matches
        $user2 = User::factory()->create();
        ReminderSetting::factory()->create([
            'user_id' => $user2->id,
            'is_enabled' => false,
            'reminder_time' => $timeString,
            'timezone' => 'UTC',
        ]);

        // User 3: Enabled but time doesn't match
        $user3 = User::factory()->create();
        ReminderSetting::factory()->create([
            'user_id' => $user3->id,
            'is_enabled' => true,
            'reminder_time' => $now->copy()->addHour()->format('H:i:s'),
            'timezone' => 'UTC',
        ]);

        // Mock the current time to match User 1's reminder time
        $this->travelTo($now);

        // Run the scheduler command
        $this->artisan('schedule:run');

        Notification::assertSentTo($user1, AyahReminder::class, function ($notification) {
            $data = $notification->toArray(new User);

            return $data['surah_name'] === 'Al-Faatiha' &&
                   $data['start_ayah'] === 1 &&
                   $data['end_ayah'] === 7;
        });

        Notification::assertNotSentTo($user2, AyahReminder::class);
        Notification::assertNotSentTo($user3, AyahReminder::class);
    }
}
