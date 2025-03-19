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
        'tags',
        'categories'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function tags(){
        return $this->belongsToMany(Tag::class,'quotes_tags');
    }
    public function category(){
        return $this->belongsToMany(Category::class,'categories_quotes');
    }
}
