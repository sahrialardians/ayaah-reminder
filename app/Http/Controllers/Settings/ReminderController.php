<?php

namespace App\Http\Controllers\Settings;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class ReminderController extends Controller
{
    /**
     * Show the reminder settings page.
     */
    public function edit(Request $request): Response
    {
        return Inertia::render('settings/Reminder', [
            'settings' => $request->user()->reminderSetting ?? [
                'is_enabled' => true,
                'reminder_time' => '08:00',
                'timezone' => 'UTC',
            ],
        ]);
    }

    /**
     * Update the user's reminder settings.
     */
    public function update(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'is_enabled' => ['required', 'boolean'],
            'reminder_time' => ['required', 'date_format:H:i'],
            'timezone' => ['required', 'string', 'timezone'],
        ]);

        $request->user()->reminderSetting()->updateOrCreate(
            ['user_id' => $request->user()->id],
            $validated
        );

        return back()->with('status', 'Reminder settings updated successfully!');
    }
}
