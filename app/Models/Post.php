<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{   
    use HasFactory;
    use SoftDeletes;
    protected $table = "post";
    protected $primaryKey = 'id_post';
    

    public function fk_id_user() {
        return $this->belongsTo(user::class, "fk_id_user");
    }
    //supongo q es 'user' pq ese es el modelo del user


    protected $fillable = [
        'text'
    ];
}




