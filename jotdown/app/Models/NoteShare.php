<?php

namespace App\Models;

use App\Models\BaseEntity\BaseModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class NoteShare extends BaseModel
{
    use HasFactory;

    protected $table = 'note_shares';

    protected $fillable = [
        'CreatedBy',
        'CreatedTime',
        'UpdatedBy',
        'UpdatedTime',
        'DeleteFlag',
        'note_id',
        'shared_by_user_id',
        'shared_with_email',
        'permission',
        'share_token',
        'expires_at',
        'revoked_at',
    ];

    protected function casts(): array
    {
        return array_merge(parent::casts(), [
            'expires_at' => 'datetime',
            'revoked_at' => 'datetime',
        ]);
    }

    public function note(): BelongsTo
    {
        return $this->belongsTo(Note::class, 'note_id', 'Id');
    }

    public function sharedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'shared_by_user_id', 'Id');
    }
}
