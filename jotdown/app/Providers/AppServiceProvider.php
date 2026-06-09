<?php

namespace App\Providers;

use App\Repositories\FolderRepository;
use App\Repositories\Interface\IFolderRepository;
use App\Repositories\Interface\ILabelRepository;
use App\Repositories\Interface\INoteRepository;
use App\Repositories\Interface\INoteShareRepository;
use App\Repositories\Interface\IPasswordResetTokenRepository;
use App\Repositories\Interface\IUserRepository;
use App\Repositories\Interface\IWorkspaceRepository;
use App\Repositories\LabelRepository;
use App\Repositories\NoteRepository;
use App\Repositories\NoteShareRepository;
use App\Repositories\PasswordResetTokenRepository;
use App\Repositories\UserRepository;
use App\Repositories\WorkspaceRepository;
use App\Services\AuthService;
use App\Services\CloudinaryService;
use App\Services\EmailTemplateService;
use App\Services\FolderService;
use App\Services\Interface\IAuthService;
use App\Services\Interface\ICloudinaryService;
use App\Services\Interface\IEmailTemplateService;
use App\Services\Interface\IFolderService;
use App\Services\Interface\IJwtTokenService;
use App\Services\Interface\ILabelService;
use App\Services\Interface\INoteService;
use App\Services\Interface\INoteShareService;
use App\Services\Interface\IPasswordHasherService;
use App\Services\Interface\ISMTPEmailService;
use App\Services\Interface\IUserProfileService;
use App\Services\Interface\IUserService;
use App\Services\Interface\IWorkspaceService;
use App\Services\JwtTokenService;
use App\Services\LabelService;
use App\Services\NoteService;
use App\Services\NoteShareService;
use App\Services\PasswordHasherService;
use App\Services\SMTPEmailService;
use App\Services\UserProfileService;
use App\Services\UserService;
use App\Services\WorkspaceService;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(IUserRepository::class, UserRepository::class);
        $this->app->bind(IPasswordResetTokenRepository::class, PasswordResetTokenRepository::class);
        $this->app->bind(INoteShareRepository::class, NoteShareRepository::class);
        $this->app->bind(INoteRepository::class, NoteRepository::class);
        $this->app->bind(IWorkspaceRepository::class, WorkspaceRepository::class);
        $this->app->bind(IAuthService::class, AuthService::class);
        $this->app->bind(INoteShareService::class, NoteShareService::class);
        $this->app->bind(INoteService::class, NoteService::class);
        $this->app->bind(IWorkspaceService::class, WorkspaceService::class);
        $this->app->bind(IUserProfileService::class, UserProfileService::class);
        $this->app->bind(IUserService::class, UserService::class);
        $this->app->bind(IPasswordHasherService::class, PasswordHasherService::class);
        $this->app->bind(ICloudinaryService::class, CloudinaryService::class);
        $this->app->bind(IEmailTemplateService::class, EmailTemplateService::class);
        $this->app->bind(ISMTPEmailService::class, SMTPEmailService::class);
        $this->app->bind(IJwtTokenService::class, JwtTokenService::class);

        $this->app->bind(IFolderRepository::class, FolderRepository::class);
        $this->app->bind(ILabelRepository::class, LabelRepository::class);
        $this->app->bind(IFolderService::class, FolderService::class);
        $this->app->bind(ILabelService::class, LabelService::class);
    }

    public function boot(): void {}
}
