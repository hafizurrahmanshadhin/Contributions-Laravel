<?php

namespace App\Models;

use App\Models\Collection;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Withdraw extends Model {
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'collection_id',
        'user_id',
        'amount',
        'bank_account',
        'account_name',
        'account_type',
    ];

    protected $hidden = [
        'status',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $casts = [
        'collection_id' => 'integer',
        'user_id'       => 'integer',
        'amount'        => 'decimal:2',
        'bank_account'  => 'string',
        'account_name'  => 'string',
        'account_type'  => 'string',
    ];

    public function user(): BelongsTo {
        return $this->belongsTo(User::class);
    }

    public function collection(): BelongsTo {
        return $this->belongsTo(Collection::class);
    }
}
