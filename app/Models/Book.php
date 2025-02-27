<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Book extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'author',
        'registration_number',
        'status',
    ];

    public function genres()
    {
        return $this->belongsToMany(Genre::class);
    }
}
