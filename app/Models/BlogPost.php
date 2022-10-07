<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BlogPost extends Model
{
    use HasFactory;

    public function user() {
        return $this->belongsTo(User::class,'users_id');
    }

    public function postComment(){
        return $this->hasMany(Comment::class,'blog_posts_id');
    }
}
