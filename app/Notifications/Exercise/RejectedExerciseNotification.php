<?php

declare(strict_types=1);

namespace App\Notifications\Exercise;

use App\Models\Exercise;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class RejectedExerciseNotification extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct(
        private Exercise $exercise,
        private string $explanation,
    ) {

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
        return (new MailMessage())
            // TODO: Tłumaczenia
            ->subject("Exercise {$this->exercise->name} has been rejected")
            ->greeting('Hello,')
            ->line('<strong>Because:</strong>')
            ->line($this->explanation)
            ->line('If you want your exercise to be public, please adhere to the changes mentioned in the message')
            ->action('Edit', route('web.exercises.edit', ['exercise' => $this->exercise]))
            ->line('Thank you for using our application!');
        ;
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [

        ];
    }
}
