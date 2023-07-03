<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Comments extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $table = "comments";
    protected $primaryKey = 'id_comment';
    

    public function fk_id_user() {
        return $this->belongsTo(user::class, "fk_id_user");
    }

    public function fk_id_post() {
        return $this->belongsTo(Post::class, "fk_id_post");
    }
}
