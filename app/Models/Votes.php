<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Votes extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $table = "votes";
    protected $primaryKey = 'id_vote';
    

    public function fk_id_user() {
        return $this->belongsTo(User::class, "fk_id_user");
    }

    public function fk_id_post() {
        return $this->belongsTo(Post::class, "fk_id_post");
    }
}
