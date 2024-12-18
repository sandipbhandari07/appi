<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PostInfo extends Model
{
    protected $table = 'posts_info'; 

    protected $fillable = [
        'name',
        'descriptions',
    ];
}
