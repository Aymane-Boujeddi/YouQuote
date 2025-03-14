<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Quote extends Model
{
    /** @use HasFactory<\Database\Factories\QuoteFactory> */
    use HasFactory;

    protected $fillable = [
        'quote',
        'author',
        'source',
        'word_count',
        'view_count',
        'user_id',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
