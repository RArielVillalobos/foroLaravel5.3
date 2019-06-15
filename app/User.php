<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public function posts(){
        return $this->hasMany(Post::class);
    }

    public function comments(){
        return $this->hasMany(Comment::class);
    }

    public function comment(Post $post,$comentario){
        $comment=new Comment([
            'comment'=>$comentario,
            'post_id'=>$post->id,

        ]);
        $this->comments()->save($comment);


    }

    public function createPost(Request $request){

        //creamos el post
        $post=new Post($request->all());
        //se lo asignamos al usuario conectado
        $this->posts()->save($post);

        //subscribirse al post cuando cree un post
        $this->subscribeTo($post);

        return $post;


    }
    public function subscriptions(){
        //en el segundo argumento puedo personalizar el nombre de la tabla
        //laravel por defecto espera post_user como nombre de tabla
        return $this->belongsToMany(Post::class,'subscriptions');
    }

    //si el usuario ya esta subscrito al post
    public function isSubscribedTo(Post $post){
        //si devuelve 1/true es que ya esta subscrito
        return $this->subscriptions()->where('post_id',$post->id)->count()>0;

    }
    public function subscribeTo(Post $post){
       $this->subscriptions()->attach($post);
    }

    public function unsubscribeFrom(Post $post){
        $this->subscriptions()->detach($post);
    }

    //cualquier modeo de eloquent (post,comentario,etc)
    public function owns(Model $model){
        return $this->id===$model->user_id;
    }
}
