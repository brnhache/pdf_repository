<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Document extends Model
{
    use HasFactory;

    //
    // Create a relationship to the user for easy restriction of data
    //
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
