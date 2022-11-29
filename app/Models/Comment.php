<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    use HasFactory;

    // 포스트 1개에 comment 여러개니까 post는 단수형
    public function post() {
        return $this->belongsTo(\App\Models\Post::class );
    }
}
