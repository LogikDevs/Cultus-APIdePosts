<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Characterizes extends Model
{
    use SoftDeletes;
    use HasFactory;
    protected $table = "characterizes";
    protected $primaryKey = 'id_characterizes';
    

    public function label() {
        return $this->belongsTo(interest::class, "fk_id_label");
    }

    public function post() {
        return $this->belongsTo(Post::class, "fk_id_post");
    }

    protected $fillable = [
        'fk_id_label',
        'fk_id_post'
    ];
}
