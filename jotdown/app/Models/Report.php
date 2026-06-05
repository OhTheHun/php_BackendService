<?php

namespace App\Models;

use App\Models\BaseEntity\BaseModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Report extends BaseModel
{
    use HasFactory;

    protected $table = 'reports';

    protected $fillable = [
        'CreatedBy',
        'CreatedTime',
        'UpdatedBy',
        'UpdatedTime',
        'DeleteFlag',
        'note_id',
        'reporter_id',
        'reason',
        'status',
        'admin_id',
        'admin_notes',
        'resolved_at',
    ];

    protected function casts(): array
    {
        return array_merge(parent::casts(), [
            'resolved_at' => 'datetime',
        ]);
    }

    public function note(): BelongsTo
    {
        return $this->belongsTo(Note::class, 'note_id', 'Id');
    }

    public function reporter(): BelongsTo
    {
        return $this->belongsTo(User::class, 'reporter_id', 'Id');
    }

    public function admin(): BelongsTo
    {
        return $this->belongsTo(User::class, 'admin_id', 'Id');
    }
}
