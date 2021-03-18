<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Tag;
use App\Image;
use App\User;

class Post extends Model
{
    //
    public function tags() {
        return $this->belongsToMany(Tag::class);
    }

    public function images() {
        return $this->hasMany(Image::class);
    }

    public function owner() {
        return $this->belongsTo(User::class);
    }
}
