<?php

namespace App\Listeners;

use App\Events\UserCreated;
use App\Mail\WelcomeMail;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Mail;

class SendWelcomeEmail implements ShouldQueue
{
    public function handle(UserCreated $event): void
    {
        Mail::to($event->user->email)
            ->send(new WelcomeMail($event->user));
    }
}
