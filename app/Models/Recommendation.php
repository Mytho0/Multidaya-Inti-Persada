<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Recommendation extends Model
{
    use HasFactory;

    protected $table = 'recommendations';
    
    // Tambahkan fillable untuk mass assignment
    protected $fillable = [
        'type',
        'title',
        'description',
        'reason',
        'score',
        'status'
    ];

    protected $casts = [
        'score' => 'decimal:2'
    ];
}