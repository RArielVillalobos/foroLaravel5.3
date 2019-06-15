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

        $post=auth()->user()->createPost($request);
        return redirect($post->url);


    }
}
