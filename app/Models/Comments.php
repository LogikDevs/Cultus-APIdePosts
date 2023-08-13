<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Comments extends Model
{
    use HasFactory;
    protected $table = "comments";
    protected $primaryKey = 'id_comment';


    public function user() {
        return $this->belongsTo(user::class, "fk_id_user");
    }

    public function post() {
        return $this->belongsTo(Post::class, "fk_id_post");
    }
}
