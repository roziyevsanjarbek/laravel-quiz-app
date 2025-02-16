<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
    protected $fillable = [
        'quiz_id',
        'name',
    ];

    public function options()
    {
        return $this->hasMany(Option::class);
    }

    public function quiz()
    {
        return $this->belongsTo(Quiz::class);
    }
}
