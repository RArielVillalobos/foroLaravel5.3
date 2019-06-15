<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Post;
class SubscriptionController extends Controller
{
    //

    public function subscribe(Post $post){
       /* Subscription::create([
            'post_id'=>$post->id,
            'user_id'=>auth()->user()->id
        ]);*/
       //o
        auth()->user()->subscribeTo($post);
        return redirect($post->url);


    }

    public function unsubscribe(Post $post){
        auth()->user()->unsubscribeFrom($post);
        return redirect($post->url);
    }
}
