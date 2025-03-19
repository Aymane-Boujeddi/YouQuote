<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tag extends Model
{
    /** @use HasFactory<\Database\Factories\TagFactory> */
    use HasFactory;
    
    protected $fillable = [
        'tag_name'
    ];

    public function quote(){
        return $this->belongsToMany(Quote::class,'quotes_tags');
    }
}
        