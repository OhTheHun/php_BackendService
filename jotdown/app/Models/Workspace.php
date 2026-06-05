<?php

namespace App\Models;

use App\Models\BaseEntity\BaseModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Workspace extends BaseModel
{
    use HasFactory;

    protected $table = 'workspaces';

    protected $fillable = [
        'CreatedBy',
        'CreatedTime',
        'UpdatedBy',
        'UpdatedTime',
        'DeleteFlag',
        'user_id',
        'name',
        'description',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'Id');
    }

    public function folders(): HasMany
    {
        return $this->hasMany(Folder::class, 'workspace_id', 'Id');
    }

    public function notes(): HasMany
    {
        return $this->hasMany(Note::class, 'workspace_id', 'Id');
    }
}
