<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Contact extends Model {
    use HasFactory;

    protected $fillable = [
        'name',
        'email',
        'phone',
        'company',
        'country',
        'country_flag',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

}

