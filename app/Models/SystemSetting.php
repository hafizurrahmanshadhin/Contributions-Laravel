<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SystemSetting extends Model {
    use HasFactory, SoftDeletes;

    protected $guarded = [];

    protected $hidden = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $casts = [
        'title'          => 'string',
        'email'          => 'string',
        'system_name'    => 'string',
        'copyright_text' => 'string',
        'logo'           => 'string',
        'favicon'        => 'string',
        'description'    => 'string',
    ];
}
