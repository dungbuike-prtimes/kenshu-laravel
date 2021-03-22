<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Image extends Model
{
    //
    protected $table = 'images';
    protected $fillable = ['url', 'description', 'post_id'];

    public function post()
    {
        return $this->belongsTo(Post::class);
    }
}
