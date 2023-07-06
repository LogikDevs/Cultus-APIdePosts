<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Votes extends Model
{
    use HasFactory;
    protected $table = "votes";
    protected $primaryKey = 'id_vote';
    
            /*
                public function user($token)
                {
                    $tokenId = Passport::tokenId($token);

                    // Obtén el token de acceso correspondiente al ID de token
                    $accessToken = Passport::token()->where('id', $tokenId)->first();

                    // Accede a los datos del token
                    $userId = $accessToken->user_id;    //id

                    // Realiza la lógica deseada con los datos del token
                    // ...

                    return $resultado;
                }
            */


//CREO QUE ANDA SIIIIIIIIIIIIIIIIIIII
            /*
                public function obtenerIdUsuarioAutenticado() {
                    if (Auth::check()) {
                        $usuarioAutenticado = Auth::user();
                        $userId = $usuarioAutenticado->id;
                        return $usuarioAutenticado;
                    }

                    return null;
                }
            */

    public function fk_id_user() {
        return $this->belongsTo(user::class, "fk_id_user");
    }
    //supongo q es 'user' pq ese es el modelo del user


    public function fk_id_post() {
        return $this->belongsTo(Post::class, "fk_id_post");
    }
}