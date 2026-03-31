<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class AyahReminder extends Notification implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct(protected string $surahName, protected int $ayahNumber)
    {
        //
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
                    ->subject('Your Daily Ayah Reminder')
                    ->greeting('Assalamu Alaikum, ' . $notifiable->name . '!')
                    ->line('This is a gentle reminder to continue your Qur\'an reading journey.')
                    ->line("Your last recorded progress: **{$this->surahName}**, Ayah **{$this->ayahNumber}**.")
                    ->action('Continue Reading', url('/dashboard'))
                    ->line('May Allah make it easy for you and accept your efforts.');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'surah_name' => $this->surahName,
            'ayah_number' => $this->ayahNumber,
        ];
    }
}
