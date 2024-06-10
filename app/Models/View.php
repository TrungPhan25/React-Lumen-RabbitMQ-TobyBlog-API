<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class View extends Model
{
    protected $table = 'views';

    protected $fillable = ['post_id', 'views'];

    public function post()
    {
        return $this->belongsTo(Post::class);
    }
}
