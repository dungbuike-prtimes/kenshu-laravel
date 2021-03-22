<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Tag;
use App\Image;
use App\User;

class Post extends Model
{
    //
    protected $table = 'posts';

    protected $fillable = ['owner', 'title', 'content'];

    public function tags() {
        return $this->belongsToMany(Tag::class)->using(PostTag::class);
    }

    public function images() {
        return $this->hasMany(Image::class);
    }

    public function owner() {
        return $this->belongsTo(User::class);
    }
}
