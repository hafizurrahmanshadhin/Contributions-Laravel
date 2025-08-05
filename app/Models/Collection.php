<?php

namespace App\Models;

use App\Models\Payment;
use App\Models\User;
use App\Models\Withdraw;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Collection extends Model {
    use HasFactory;
    protected $guarded = [];

    protected $hidden = [
        'status',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $casts = [
        'user_id'          => 'integer',
        'image'            => 'string',
        'name'             => 'string',
        'target_amount'    => 'decimal:2',
        'collected_amount' => 'decimal:2',
    ];

    public function user(): BelongsTo {
        return $this->belongsTo(User::class);
    }

    public function donations(): HasMany {
        return $this->hasMany(Payment::class);
    }

    public function getTotalDonations() {
        return $this->donations()->sum('amount');
    }

    public function withdraws(): HasMany {
        return $this->hasMany(Withdraw::class);
    }
}
