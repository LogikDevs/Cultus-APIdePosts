<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Passport\Passport;

class Post extends Model
{   
    use HasFactory;
    protected $table = "post";
    protected $primaryKey = 'id_post';
    

    public function fk_id_user() {
        return $this->belongsTo(user::class, "fk_id_user");
    }

    protected $fillable = [
        'text'
    ];
}

