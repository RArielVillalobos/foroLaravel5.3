<?php

namespace App\Http\Controllers;

use App\Post;
use Illuminate\Http\Request;

class CreatePostController extends Controller
{
    //
    public function create(){
        return view('posts.create');

    }

    public function store(Request $request){
        //$user_id=auth()->user()->id;
       // $request->merge(['user_id'=>$user_id]);

        $this->validate($request,[
            'title'=>'required',
            'content'=>'required'
        ]);
        //creamos el post
        $post=new Post($request->all());
        //se lo asignamos al usuario conectado
        auth()->user()->posts()->save($post);

        //si no retornamos nada dara un error Crawler la prueba
        //debemos retornar algo
        return "Post". $post->title;

    }
}
