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
}
