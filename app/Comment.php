<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{

    protected $fillable=['comment','post_id','user_id'];

    public function post(){
        return $this->belongsTo(Post::class);
    }

    public function user(){
        return $this->belongsTo(User::class);
    }

    public function markAsAnswer(Post $post){
            /*
            //si ya hay un comentario marcado como la respuesta del post, actualizarlo el comentario actual para que ya no sea la respuesta d post
            $post->comments()->where('answer',true)->update(['answer'=>false]);

            //$post->comments()->where('answer',true)->update(['answer'=>false]);

            //marcar la columna como verdadera
            $this->answer=true;
            $this->save();

            //el post ya no esta pendiente
            $post->pending=false;
            $post->save();*/
            //refactorizado
            //ahora tenemos la columna answer_id en el modelo post
            //una vez que un comentario se marque como respuesta el valor de answer_id va a cambiar
            //cualquier otro comentario que haya sido marcado como respuesta ya no va a estar
            $post->pending=false;
            $post->answer_id=$this->id;
            $post->save();

    }

    //atributo dinamico de eloquent
    public function getAnswerAttribute()
    {
        //retorna verdadero si el id del comentario es igual al answer_id de post
        return $this->id==$this->post->answer_id;
    }
}
