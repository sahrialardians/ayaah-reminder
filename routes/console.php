<?php

use App\Models\User;
use App\Notifications\AyahReminder;
use App\Services\SurahService;
use Illuminate\Support\Facades\Schedule;

Schedule::call(function (SurahService $surahService) {
    $now = now();
    
    // Find users who have reminders enabled and it's their scheduled time
    $users = User::whereHas('reminderSetting', function ($query) use ($now) {
        $query->where('is_enabled', true);
    })->with(['reminderSetting', 'latestAyahRead'])->get();

    foreach ($users as $user) {
        $settings = $user->reminderSetting;
        
        // Convert user's scheduled time to UTC for comparison or handle via local timezone
        // Simple approach: Check if current time in user's timezone matches their reminder_time
        $userTime = now($settings->timezone);
        $scheduledTime = \Illuminate\Support\Carbon::createFromFormat('H:i:s', $settings->reminder_time, $settings->timezone);
        
        if ($userTime->hour === $scheduledTime->hour && $userTime->minute === $scheduledTime->minute) {
            $latestRead = $user->latestAyahRead;
            
            $surahName = $latestRead 
                ? ($surahService->getSurahs()->firstWhere('number', $latestRead->surah_number)['englishName'] ?? "Surah {$latestRead->surah_number}")
                : "your first Surah";
                
            $ayahNumber = $latestRead ? $latestRead->ayah_number : 1;

            $user->notify(new AyahReminder($surahName, $ayahNumber));
        }
    }
})->everyMinute();
