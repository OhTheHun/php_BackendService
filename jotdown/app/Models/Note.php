<?php

namespace App\Models;

use App\Models\BaseEntity\BaseModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Note extends BaseModel
{
    use HasFactory;

    protected $table = 'notes';

    protected $fillable = [
        'CreatedBy',
        'CreatedTime',
        'UpdatedBy',
        'UpdatedTime',
        'DeleteFlag',
        'user_id',
        'workspace_id',
        'folder_id',
        'title',
        'content',
        'color',
        'is_pinned',
        'pinned_at',
        'is_favorite',
        'visibility',
        'is_protected',
        'note_password',
        'archived_at',
    ];

    protected $hidden = [
        'note_password',
    ];

    protected function casts(): array
    {
        return array_merge(parent::casts(), [
            'is_pinned' => 'boolean',
            'pinned_at' => 'datetime',
            'is_favorite' => 'boolean',
            'is_protected' => 'boolean',
            'archived_at' => 'datetime',
        ]);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'Id');
    }

    public function workspace(): BelongsTo
    {
        return $this->belongsTo(Workspace::class, 'workspace_id', 'Id');
    }

    public function folder(): BelongsTo
    {
        return $this->belongsTo(Folder::class, 'folder_id', 'Id');
    }

    public function labels(): BelongsToMany
    {
        return $this->belongsToMany(Label::class, 'note_labels', 'note_id', 'label_id', 'Id', 'Id');
    }

    public function attachments(): HasMany
    {
        return $this->hasMany(NoteAttachment::class, 'note_id', 'Id');
    }

    public function shares(): HasMany
    {
        return $this->hasMany(NoteShare::class, 'note_id', 'Id');
    }

    public function reports(): HasMany
    {
        return $this->hasMany(Report::class, 'note_id', 'Id');
    }
}
