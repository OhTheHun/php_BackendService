<?php

namespace App\Models;

use App\Models\BaseEntity\BaseModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Payment extends BaseModel
{
    use HasFactory;

    protected $table = 'payments';

    protected $fillable = [
        'CreatedBy',
        'CreatedTime',
        'UpdatedBy',
        'UpdatedTime',
        'DeleteFlag',
        'user_id',
        'plan_id',
        'amount',
        'status',
        'payment_method',
        'transaction_code',
        'paid_at',
    ];

    protected function casts(): array
    {
        return array_merge(parent::casts(), [
            'amount' => 'decimal:2',
            'paid_at' => 'datetime',
        ]);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'Id');
    }

    public function plan(): BelongsTo
    {
        return $this->belongsTo(Plan::class, 'plan_id', 'Id');
    }
}
