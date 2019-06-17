<?php

namespace App;

use App\Notifications\PostCommented;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Facades\Notification;


class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'username','first_name','last_name', 'email',
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

        //enviar notifiacacion a los suscriptores del post, una vez que creo el comentario
        //se lo enviaremos a los suscritores 1er argumento(excepto al autor del comentario)
        //2do argumento la notification(postCommented)//autor del comentario//el post que esta siendo comentado//conviene el comentario ya que contiene el post
        Notification::send($post->suscribers()->where('users.id','!=',$this->id)->get(),
            new PostCommented($comment));
        return $comment;

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

        if($this->subscriptions()->attach($post)){
            return true;
        }else{
            return false;
        }

    }

    public function unsubscribeFrom(Post $post){
        $this->subscriptions()->detach($post);
    }

    //cualquier modeo de eloquent (post,comentario,etc)
    public function owns(Model $model){
        return $this->id===$model->user_id;
    }
}
