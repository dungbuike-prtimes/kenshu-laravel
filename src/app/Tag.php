<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Tag extends Model
{
    //
    protected $table = 'tags';

    protected $fillable = ['name', 'description'];

    /**
     * a tag can be assigned on many post
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function posts() {
        return $this->belongsToMany(Post::class)->using(PostTag::class);
    }
}
