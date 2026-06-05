<?php

namespace App\Models;

use App\Models\BaseEntity\BaseModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class NoteAttachment extends BaseModel
{
    use HasFactory;

    protected $table = 'note_attachments';

    protected $fillable = [
        'CreatedBy',
        'CreatedTime',
        'UpdatedBy',
        'UpdatedTime',
        'DeleteFlag',
        'note_id',
        'file_name',
        'file_path',
        'file_size',
        'file_type',
        'attachment_kind',
    ];

    protected function casts(): array
    {
        return array_merge(parent::casts(), [
            'file_size' => 'integer',
        ]);
    }

    public function note(): BelongsTo
    {
        return $this->belongsTo(Note::class, 'note_id', 'Id');
    }
}
