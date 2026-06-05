<?php

namespace App\Services\Interface;

interface ISMTPEmailService
{
    public function send(string $to, string $subject, string $html): void;
}
