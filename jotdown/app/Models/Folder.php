<?php

namespace App\Models;

use App\Models\BaseEntity\BaseModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Folder extends BaseModel
{
    use HasFactory;

    protected $table = 'folders';

    protected $fillable = [
        'CreatedBy',
        'CreatedTime',
        'UpdatedBy',
        'UpdatedTime',
        'DeleteFlag',
        'user_id',
        'workspace_id',
        'name',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'Id');
    }

    public function workspace(): BelongsTo
    {
        return $this->belongsTo(Workspace::class, 'workspace_id', 'Id');
    }

    public function notes(): HasMany
    {
        return $this->hasMany(Note::class, 'folder_id', 'Id');
    }
}
