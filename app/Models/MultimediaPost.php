<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MultimediaPost extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $table = "multimedia_post";
    protected $primaryKey = 'id_multimediaPost';
    
    
    public function fk_id_post() {
        return $this->belongsTo(Post::class, "fk_id_post");
    }
}
