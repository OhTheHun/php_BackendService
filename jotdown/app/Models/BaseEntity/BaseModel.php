<?php

namespace App\Models\BaseEntity;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

abstract class BaseModel extends Model
{
    use HasUuids;

    protected $primaryKey = 'Id';

    protected $keyType = 'string';

    public $incrementing = false; // tắt tự động tăng do dùng UUID

    public const CREATED_AT = 'CreatedTime';

    public const UPDATED_AT = 'UpdatedTime';

    protected function casts(): array
    {
        return [
            'Id' => 'string',
            'CreatedTime' => 'datetime',
            'UpdatedTime' => 'datetime',
            'DeleteFlag' => 'boolean',
        ];
    }
}
