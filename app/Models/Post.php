<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class posts extends Model
{   
    use HasFactory;
    use SoftDeletes;
    protected $table = "post";
    protected $primaryKey = 'id_post';
    

    public function fk_id_user() {
        return $this->belongsTo(User::class, "fk_id_user");
    }

    protected $fillable = [
        'text'
    ];
}




