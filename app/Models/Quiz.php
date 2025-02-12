<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Quiz extends Model
{
    protected $fillable = [
        'title',
        'description',
        'time_limit',
        'slug',
        'user_id'
    ];

    public function questions()
    {
        return $this->hasMany(Question::class);
    }
}
