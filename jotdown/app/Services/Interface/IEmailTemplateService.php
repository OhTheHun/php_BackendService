<?php

namespace App\Services\Interface;

use App\Enums\EmailTemplateType;

interface IEmailTemplateService
{
    public function getEmailTemplate(EmailTemplateType $emailTemplateType, array $data = []): string;
}
