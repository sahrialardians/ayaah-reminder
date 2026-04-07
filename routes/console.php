<?php

use App\Models\User;
use App\Notifications\AyahReminder;
use App\Services\SurahService;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Schedule;

Schedule::call(function (SurahService $surahService) {
    $now = now();

    // Find users who have reminders enabled and it's their scheduled time
    $users = User::whereHas('reminderSetting', function ($query) {
        $query->where('is_enabled', true);
    })->with(['reminderSetting', 'latestAyahRead'])->get();

    foreach ($users as $user) {
        $settings = $user->reminderSetting;

        // Convert user's scheduled time to UTC for comparison or handle via local timezone
        // Simple approach: Check if current time in user's timezone matches their reminder_time
        $userTime = now($settings->timezone);

        try {
            $timeFormat = strlen($settings->reminder_time) > 5 ? 'H:i:s' : 'H:i';
            $scheduledTime = Carbon::createFromFormat($timeFormat, $settings->reminder_time, $settings->timezone);
        } catch (Exception $e) {
            continue; // Skip if format is completely invalid
        }

        if ($userTime->hour === $scheduledTime->hour && $userTime->minute === $scheduledTime->minute) {
            $latestRead = $user->latestAyahRead;

            $surahName = $latestRead
                ? ($surahService->getSurahs()->firstWhere('number', $latestRead->surah_number)['englishName'] ?? "Surah {$latestRead->surah_number}")
                : 'your first Surah';

            $startAyah = $latestRead ? $latestRead->start_ayah : 1;
            $endAyah = $latestRead ? $latestRead->end_ayah : 1;

            $user->notify(new AyahReminder($surahName, $startAyah, $endAyah));
        }
    }
})->everyMinute();
