<?php

namespace App\Notifications;

use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Notifications\Messages\MailMessage;

class AdminResetPasswordNotification extends ResetPassword
{
    public function toMail($notifiable): MailMessage
    {
        if ($this->shouldExposeResetLink()) {
            session()->flash('dev_reset_url', $this->resetUrl($notifiable));
        }

        return parent::toMail($notifiable);
    }

    protected function shouldExposeResetLink(): bool
    {
        return config('mail.default') === 'log';
    }
}
