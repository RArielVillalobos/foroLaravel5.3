<?php

use Illuminate\Support\Facades\Mail;

class RequestTokenTest extends FeatureTestCase
{

    public function test_a_guest_user_can_request_a_token()
    {
        //having
        Mail::fake();
        $user=$this->defaultUser(['email'=>'ariel@foro.net']);

        //when
        $this->visitRoute('token')
            ->type('ariel@foro.net','email')
            ->press('Solicitar token');

        //un token fue creado para el usuario
        $token=\App\Token::where('user_id',$user->id)->first();
        $this->assertNotNull($token,'el token no fue generado');
        //se le envia el token al usuario
        //comprobar que fue enviado
        Mail::assertSentTo($user,\App\Mail\TokenMail::class,function($mail) use ($token){
            return $mail->token->id===$token->id;
        });
        //comprobar que el usuario aun no inicio sesion
        $this->dontSeeIsAuthenticated();

        //luego quiero que sea redirigito a esta ruta
        $this->see('Enviamos un enlace para que inicies sesion');

    }
    //comprobar que un usuario solicite un token sin un email
    public function test_a_guest_user_can_request_a_token_without_an_email()
    {
        //having
        Mail::fake();

        //when
        $this->visitRoute('token')
            ->press('Solicitar token');
        $token=\App\Token::first();

        //comprobar que el token no es creado
        $this->assertNull($token,'el token fue generado');

        //comprobar que el email no fue enviado
        Mail::assertNotSent(\App\Mail\TokenMail::class);

        $this->dontSeeIsAuthenticated();
        //deberiamos ver los errores
        $this->seeErrors([
            'email'=>'El campo correo electrónico es obligatorio'
        ]);

    }
    //si el usuarioe escribe en el email un valor que no es valido
    public function test_a_guest_user_can_request_a_token_an_invalid_email()
    {
        //having
        Mail::fake();

        //when
        $this->visitRoute('token')
            ->type('Silence','email')
            ->press('Solicitar token');

        //deberiamos ver los errores
        $this->seeErrors([
            'email'=>'Correo electrónico no es un correo válido'
        ]);

    }

    //email valido y que no exista en la bd
    public function test_a_guest_user_can_request_a_token_with_non_existent_email()
    {
        //having

        $user=$this->defaultUser(['email'=>'arielito@foro.net']);

        //when
        $this->visitRoute('token')
            ->type('arieldjmix@foro.net','email')
            ->press('Solicitar token');

        //deberiamos ver los errores
        $this->seeErrors([
            'email'=>'Este correo electrónico no existe'
        ]);

    }
}
