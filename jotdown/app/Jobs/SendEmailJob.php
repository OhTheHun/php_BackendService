<?php

namespace App\Jobs;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Mail;

class SendEmailJob implements ShouldQueue
{
    use Queueable;

    public function __construct(
        private readonly string $to,
        private readonly string $subject,
        private readonly string $html,
    ) {
    }

    public function handle(): void
    {
        Mail::html($this->html, function ($message): void {
            $message->to($this->to)->subject($this->subject);
        });
    }
}
