<?php

namespace App\Notifications;

use App\Admin;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class AccountCreated extends Notification
{
    use Queueable;

    /**
     * user
     *
     * @var
     */
    public $user;

    public $unencrypted_password;

    /**
     * Create a new notification instance.
     *
     * @param $user
     * @param $unencrypted_password
     */
    public function __construct($user, $unencrypted_password)
    {
        $this->user = $user;
        $this->unencrypted_password = $unencrypted_password;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        $user = $this->user;
        $unencrypted_password = $this->unencrypted_password;
        return (new MailMessage)
            ->from('noreply@unifinrs.com')
            ->subject('Welcome to Unifin')
            ->markdown('mail.welcome', compact('user', 'unencrypted_password'));
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            //
        ];
    }
}
