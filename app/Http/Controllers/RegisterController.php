<?php

namespace App\Http\Controllers;

use App\Token;
use App\User;
use Illuminate\Http\Request;

class RegisterController extends Controller
{
    //
    public function create(){
        return view('register/create');
    }

    public function store(Request $request){
        //@todo agregarr validacion+test para validacion
        $user=User::create($request->all());

        //despues de generar el token,enviar el email
        Token::generateFor($user)->sendByEmail();

        return redirect()->route('register_confirmation');

    }

    public function confirmation(){
        return view('register/confirmation');
    }
}
