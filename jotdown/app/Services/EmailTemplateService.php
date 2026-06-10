<?php

namespace App\Services;

use App\Enums\EmailTemplateType;
use App\Services\Interface\IEmailTemplateService;
use Illuminate\Support\Facades\File;

class EmailTemplateService implements IEmailTemplateService
{
    private array $emailTemplateDictionaries = [];

    public function __construct()
    {
        $emailTemplateDirectory = resource_path('email-templates');

        if (!File::exists($emailTemplateDirectory)) {
            File::makeDirectory($emailTemplateDirectory, 0755, true);
        }

        $this->loadTemplate(EmailTemplateType::ResetPassword, $emailTemplateDirectory, 'reset-password.html');
    }

    public function getEmailTemplate(EmailTemplateType $emailTemplateType, array $data = []): string
    {
        $template = $this->emailTemplateDictionaries[$emailTemplateType->value] ?? '';

        foreach ($data as $key => $value) {
            $template = str_replace('{{'.$key.'}}', (string) $value, $template);
        }

        return $template;
    }

    private function loadTemplate(EmailTemplateType $emailTemplateType, string $directory, string $fileName): void
    {
        $templatePath = $directory.DIRECTORY_SEPARATOR.$fileName;

        if (File::exists($templatePath)) {
            $this->emailTemplateDictionaries[$emailTemplateType->value] = File::get($templatePath);
        }
    }
}
