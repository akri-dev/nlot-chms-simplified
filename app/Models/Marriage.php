<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Marriage extends Model
{
    protected $dates = ['anniversary_date'];

    // Relationship to get the husband's profile
    public function husband()
    {
        return $this->belongsTo(Profile::class, 'husband_id');
    }

    // Relationship to get the wife's profile
    public function wife()
    {
        return $this->belongsTo(Profile::class, 'wife_id');
    }
}
