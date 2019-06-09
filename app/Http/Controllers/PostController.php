<?php

namespace App\Http\Controllers;

use App\Post;
use Illuminate\Http\Request;

class PostController extends Controller
{
    //

    public function show(Post $post,$slug){
        //abortar si el slug es distinto al slug del post
        //abort_if($post->slug!=$slug,404);

        //si el slug del post no es el mismo al slug q esta pasando el usuario lo redigirimos a la url nueva
        if($post->slug!=$slug){
           return redirect($post->url,301);
        }
        return view('posts.show',['post'=>$post]);
    }

    public function index(){
        $posts=Post::all();
        return view('posts.index',['posts'=>$posts]);
    }
}
