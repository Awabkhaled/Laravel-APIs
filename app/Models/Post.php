<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Post extends Model
{
    use SoftDeletes;
    public $timestamps = false;
    protected $fillable = ['title', 'body', 'cover_image', 'pinned', 'user_id'];
    protected $hidden = [
        'deleted_at'
    ];

    public function tags()
    {
        return $this->belongsToMany(Tag::class);
    }

    public function user(){
        return $this->belongsTo(User::class);
    }

}
