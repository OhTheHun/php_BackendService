<?php

namespace App\Providers;

use App\Repositories\Interface\IPasswordResetTokenRepository;
use App\Repositories\Interface\IUserRepository;
use App\Repositories\Interface\INoteShareRepository;
use App\Repositories\NoteShareRepository;
use App\Repositories\PasswordResetTokenRepository;
use App\Repositories\UserRepository;
use App\Services\AuthService;
use App\Services\CloudinaryService;
use App\Services\EmailTemplateService;
use App\Services\Interface\IAuthService;
use App\Services\Interface\ICloudinaryService;
use App\Services\Interface\IEmailTemplateService;
use App\Services\Interface\IJwtTokenService;
use App\Services\Interface\INoteShareService;
use App\Services\Interface\IPasswordHasherService;
use App\Services\Interface\ISMTPEmailService;
use App\Services\Interface\IUserProfileService;
use App\Services\Interface\IUserService;
use App\Services\JwtTokenService;
use App\Services\NoteShareService;
use App\Services\PasswordHasherService;
use App\Services\SMTPEmailService;
use App\Services\UserProfileService;
use App\Services\UserService;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(IUserRepository::class, UserRepository::class);
        $this->app->bind(IPasswordResetTokenRepository::class, PasswordResetTokenRepository::class);
        $this->app->bind(INoteShareRepository::class, NoteShareRepository::class);
        $this->app->bind(IAuthService::class, AuthService::class);
        $this->app->bind(INoteShareService::class, NoteShareService::class);
        $this->app->bind(IUserProfileService::class, UserProfileService::class);
        $this->app->bind(IUserService::class, UserService::class);
        $this->app->bind(IPasswordHasherService::class, PasswordHasherService::class);
        $this->app->bind(ICloudinaryService::class, CloudinaryService::class);
        $this->app->bind(IEmailTemplateService::class, EmailTemplateService::class);
        $this->app->bind(ISMTPEmailService::class, SMTPEmailService::class);
        $this->app->bind(IJwtTokenService::class, JwtTokenService::class);
    }

    public function boot(): void
    {
    }
}
