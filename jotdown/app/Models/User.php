<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, HasUuids, Notifiable;

    protected $table = 'users';

    protected $primaryKey = 'Id';

    protected $keyType = 'string';

    public $incrementing = false;

    public const CREATED_AT = 'CreatedTime';

    public const UPDATED_AT = 'UpdatedTime';

    protected $fillable = [
        'Id',
        'CreatedBy',
        'CreatedTime',
        'UpdatedBy',
        'UpdatedTime',
        'DeleteFlag',
        'display_name',
        'email',
        'password',
        'role',
        'status',
        'avatar_url',
        'theme',
        'font_size',
        'default_note_color',
        'plan_id',
        'email_verified_at',
        'last_login_at',
    ];

    protected $hidden = [
        'password',
    ];

    protected function casts(): array
    {
        return [
            'Id' => 'string',
            'CreatedTime' => 'datetime',
            'UpdatedTime' => 'datetime',
            'DeleteFlag' => 'boolean',
            'email_verified_at' => 'datetime',
            'last_login_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function plan(): BelongsTo
    {
        return $this->belongsTo(Plan::class, 'plan_id', 'Id');
    }

    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class, 'user_id', 'Id');
    }

    public function workspaces(): HasMany
    {
        return $this->hasMany(Workspace::class, 'user_id', 'Id');
    }

    public function folders(): HasMany
    {
        return $this->hasMany(Folder::class, 'user_id', 'Id');
    }

    public function notes(): HasMany
    {
        return $this->hasMany(Note::class, 'user_id', 'Id');
    }

    public function labels(): HasMany
    {
        return $this->hasMany(Label::class, 'user_id', 'Id');
    }

    public function noteShares(): HasMany
    {
        return $this->hasMany(NoteShare::class, 'shared_by_user_id', 'Id');
    }

    public function reports(): HasMany
    {
        return $this->hasMany(Report::class, 'reporter_id', 'Id');
    }

    public function activityLogs(): HasMany
    {
        return $this->hasMany(ActivityLog::class, 'user_id', 'Id');
    }
}
