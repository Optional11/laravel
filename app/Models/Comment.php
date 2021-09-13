<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    use HasFactory;

    public function blogPost()
    {
        return $this->belongsTo(BlogPost::class, 'blog_post_id'); // you can specifz name of foreign ket as 2nd param
    }
}
