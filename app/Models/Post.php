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
    //protected $fillable = ['text'];    //it protects your model from mass assignment , only the fields you put in the fillable are fillable


/*
    public function fk_id_user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'fk_id_user');
    }
*/

    /*
    CREATE TABLE personas(
        id int primary key auto_increment,
        nombre varchar(255) NOT NULL,
        apellido varchar(255) NOT NULL,
        email varchar(255) NULL UNIQUE,
        created_at timestamp DEFAULT CURRENT_TIMESTAMP,
        updated_at timestamp DEFAULT CURRENT_TIMESTAMP
      );
    */
}




