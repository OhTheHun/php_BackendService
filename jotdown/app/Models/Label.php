<?php

namespace App\Models;

use App\Models\BaseEntity\BaseModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Label extends BaseModel
{
    use HasFactory;

    protected $table = 'labels';

    protected $fillable = [
        'CreatedBy',
        'CreatedTime',
        'UpdatedBy',
        'UpdatedTime',
        'DeleteFlag',
        'user_id',
        'name',
        'color',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'Id');
    }

    public function notes(): BelongsToMany
    {
        return $this->belongsToMany(Note::class, 'note_labels', 'label_id', 'note_id', 'Id', 'Id');
    }
}
