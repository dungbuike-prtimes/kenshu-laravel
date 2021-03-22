<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Pivot;

class PostTag extends Pivot
{
    protected $table = 'post_tag';

    protected $fillable = ['post_id', 'tag_id'];

    public $incrementing = true;
}
