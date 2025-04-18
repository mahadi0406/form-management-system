<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Form extends Model
{
    protected $fillable = [
        'title',
        'method',
        'action',
        'configuration',
    ];

    protected $casts = [
        'configuration' => 'json',
    ];
}
