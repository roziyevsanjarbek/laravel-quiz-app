<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Result extends Model
{
    protected $fillable = [
        'user_id',
        'quiz_id',
        'started_at',
        'finished_at',
    ];
}
