<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    protected $fillable = ['title', 'meta_title', 'slug', 'summary', 'content', 'category_id', 'image', 'author_id', 'published', 'published_at'];

    // public function category()
    // {
    //     return $this->belongsTo(Category::class);
    // }

    public function author()
    {
        return $this->belongsTo(User::class, 'author_id');
    }

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }
}
