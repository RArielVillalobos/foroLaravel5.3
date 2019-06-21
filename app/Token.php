<?php

namespace App;

use App\Mail\TokenMail;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

class Token extends Model
{
    //
    protected $fillable=[
        'token','user_id'
    ];
    public function user(){
        return $this->belongsTo(User::class);
    }

    public static function generateFor(User $user){
        return static::create([
            'token'=>str_random(60),
            'user_id'=>$user->id
        ]);
    }
    public function sendByEmail(){
        //le paso el token al mailable(TokenMail)
        Mail::to($this->user)->send(new TokenMail($this));
    }

    public function getRouteKeyName()
    {
        //es el atributo token, no el id
        return 'token';
    }

    public function login(){
        Auth::login($this->user);

        $this->delete();
    }

    public function getUrlAttribute(){
        //devuelvo la url necesaria para iniciar sesion
        return route('login',['token'=>$this->token]);
    }
}
