<?php

namespace App;
use GrahamCampbell\Markdown\Facades\Markdown;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{

    //casteando la columna answrd para que sea booleana ya que en la bd se creo como tinint(0 y 1)
    //pero nosotros le pasamos parametros true o false si no dara error al insertar valores
    protected $casts=[
        'pending'=>'boolean'
    ];
    protected $fillable=['title','content','user_id'];


    public function user(){
        return $this->belongsTo(User::class);
    }

    public function comments(){
        return $this->hasMany(Comment::class);
    }

    public function suscribers(){
        return $this->belongsToMany(User::class,'subscriptions');
    }

    public function latestComment(){
        //los comentarios mas recientes al principio
        return $this->comments()->orderBy('id','desc');

    }

    public function setTitleAttribute($value)
    {
        $this->attributes['title']=$value;
        $this->attributes['slug']=Str::slug($value);

    }

    //atributo dinamico eloquent

    public function getUrlAttribute(){

        return route('posts.show',[$this->id,$this->slug]);
    }


    //atributo dinamico
    public function getSafeHtmlContentAttribute(){
        return Markdown::convertToHtml(e($this->content));

    }
}
