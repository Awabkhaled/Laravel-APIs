<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Post extends Model
{
    use SoftDeletes;
    public $timestamps = false;
    protected $fillable = ['title', 'body', 'cover_image', 'pinned'];
    protected $hidden = [
        'deleted_at'
    ];
}
