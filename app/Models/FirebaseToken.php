<?php

namespace App\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class FirebaseToken extends Model {
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'token',
        'device_id',
    ];

    protected $hidden = [
        'status',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected function casts(): array {
        return [
            'user_id'   => 'integer',
            'token'     => 'string',
            'device_id' => 'string',
            'status'    => 'string',
        ];
    }

    public function user(): BelongsTo {
        return $this->belongsTo(User::class);
    }
}
