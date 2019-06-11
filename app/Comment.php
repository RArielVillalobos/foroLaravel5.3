<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    //
    //casteando la columna answrd para que sea booleana ya que en la bd se creo como tinint(0 y 1)
    //pero nosotros le pasamos parametros true o false si no dara error al insertar valores
    protected $casts=[
        'answer'=>'boolean'
    ];
    protected $fillable=['comment','post_id','user_id'];

    public function post(){
        return $this->belongsTo(Post::class);
    }

    public function user(){
        return $this->belongsTo(User::class);
    }

    public function markAsAnswer(Post $post){




            //si ya hay un comentario marcado como la respuesta del post, actualizarlo el comentario actual para que ya no sea la respuesta d post
            $post->comments()->where('answer',true)->update(['answer'=>false]);

            //$post->comments()->where('answer',true)->update(['answer'=>false]);

            //marcar la columna como verdadera
            $this->answer=true;
            $this->save();

            //el post ya no esta pendiente
            $post->pending=false;
            $post->save();

    }
}
