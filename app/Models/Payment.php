<?php

namespace App\Models;

use App\Models\Collection;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Payment extends Model {
    use HasFactory;
    protected $guarded = [];

    protected $hidden = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $casts = [
        'user_id'        => 'integer',
        'collection_id'  => 'integer',
        'name'           => 'string',
        'amount'         => 'decimal:2',
        'transaction_id' => 'string',
    ];

    public function collection(): BelongsTo {
        return $this->belongsTo(Collection::class);
    }
    public function user(): BelongsTo {
        return $this->belongsTo(User::class);
    }
}
