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
    
    
    public function user() {
        return $this->hasMany(user::class, 'fk_id_user');
    }

    public function votes() {
        return $this->hasMany(Vote::class, 'fk_id_post');
    }

    public function comments() {
        return $this->hasMany(Comments::class, 'fk_id_post');
    }

    protected $fillable = [
        'text',
        'location'
    ];
}

