<?php

namespace App\Http\Controllers;


//usando una funcionalidad de php7
use App\{Comment,Post};
use Illuminate\Http\Request;

class CommentController extends Controller
{
    //
    //Post $post  -> implicit model binding

    public function store(Request $request,Post $post){

        //@todo: agregar validacion

        //optimizamos la creacion del comentario generando el store(metodo comment) en el modelo User
        auth()->user()->comment($post,$request->comment);

        return redirect($post->url);
    }

    public function accept(Comment $comment){
        //debo implementar la politica de acceso de esta manera para evitar que envien una peticion post
        //deja pasar unicamente a los usuarios que estan autorizados para aceptar este comentario
        $this->authorize('accept',$comment);
         $comment->markAsAnswer($comment->post);

        return redirect($comment->post->url);
    }
}
