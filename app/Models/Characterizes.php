<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Characterizes extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $table = "characterizes";
    protected $primaryKey = 'id_characterizes';
    

    public function fk_id_label() {
        return $this->belongsTo(interest::class, "fk_id_label");
    }
    //funca??? pq interest es parte de la clase interest en api de autenticacion

    public function fk_id_post() {
        return $this->belongsTo(Post::class, "fk_id_post");
    }
}
