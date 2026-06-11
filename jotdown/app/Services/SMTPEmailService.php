<?php

namespace App\Services;

use App\Jobs\SendEmailJob;
use App\Services\Interface\ISMTPEmailService;

class SMTPEmailService implements ISMTPEmailService
{
    public function send(string $to, string $subject, string $html): void
    {
        SendEmailJob::dispatch($to, $subject, $html)->onQueue('emails');
    }
}
