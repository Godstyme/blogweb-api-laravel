<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    use HasFactory;

    protected $fillable = [
        'comments_content',
        'blog_posts_id',
        'users_id',
    ];

    function user() {
        return $this->belongsTo(User::class);
    }

    public function blogPost(){
        return $this->belongsTo(BlogPost::class,'blog_posts_id');
    }
}
