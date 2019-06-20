<?php

namespace App\Http\Controllers;

use App\Token;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    //

    //al usar model impliciting binding(Token $token), el usuario debe enviar un token valido
    //si envia uno invalido, dira que la clase Token no fue encontrada
    //como nuestra prueba requiere que si envia un token invalido, sea redirigido, no vamos a usar model impliciting binding
    public function login($token){

        $modeloToken=Token::where('token',$token)
            //donde la fecha de creacion haya trancurrido en un lapso de 30 minutos
            ->where('created_at','>=',Carbon::parse('-30 minutes'))
            ->first();




        if($modeloToken==null){
            alert('Este enlace ya expiró,solicite otro','danger');
            return redirect()->route('token');
        }
        $modeloToken->login();
        return redirect('/');

    }
}
