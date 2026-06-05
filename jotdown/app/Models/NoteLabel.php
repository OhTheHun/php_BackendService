<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class NoteLabel extends Model
{
    protected $table = 'note_labels';

    public $incrementing = false;

    public $timestamps = false;

    protected $fillable = [
        'note_id',
        'label_id',
    ];

    public function note(): BelongsTo
    {
        return $this->belongsTo(Note::class, 'note_id', 'Id');
    }

    public function label(): BelongsTo
    {
        return $this->belongsTo(Label::class, 'label_id', 'Id');
    }
}
