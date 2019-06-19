<?php

namespace App\Http\Controllers;

use App\Token;
use App\User;
use Illuminate\Http\Request;

class TokenController extends Controller
{
    //
    public function create(Request $request){
        return view('token/create');
    }

    public function store(Request $request){
        $this->validate($request,[
            'email'=>'required|email|exists:users'

        ]);
        $user=User::where('email',$request->email)->first();
        Token::generateFor($user)->sendByEmail();
        alert('Enviamos un enlace para que inicies sesion');
        return back();

    }


}
