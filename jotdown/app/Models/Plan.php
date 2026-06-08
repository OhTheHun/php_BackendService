<?php

namespace App\Models;

use App\Models\BaseEntity\BaseModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Plan extends BaseModel
{
    use HasFactory;

    protected $table = 'plans';

    protected $fillable = [
        'CreatedBy',
        'CreatedTime',
        'UpdatedBy',
        'UpdatedTime',
        'DeleteFlag',
        'name',
        'price',
        'max_notes',
        'max_workspaces',
        'max_attachment_size',
        'can_export',
        'status',
    ];

    protected function casts(): array
    {
        return array_merge(parent::casts(), [
            'price' => 'decimal:2',
            'max_notes' => 'integer',
            'max_workspaces' => 'integer',
            'max_attachment_size' => 'integer',
            'can_export' => 'boolean',
            'status' => 'boolean',
        ]);
    }

    public function users(): HasMany
    {
        return $this->hasMany(User::class, 'plan_id', 'Id');
    }

    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class, 'plan_id', 'Id');
    }
}
