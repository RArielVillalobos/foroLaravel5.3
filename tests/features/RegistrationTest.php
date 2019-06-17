<?php

use App\Token;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\Mail;
use App\Mail\TokenMail;

class RegistrationTest extends FeatureTestCase
{

    public function test_a_user_can_create_an_account()
    {
        Mail::fake();
        $this->visitRoute('register')
            //va a escribir admin@ en el campo email
            ->type('admin@foro.net','email')
            ->type('ariellonko','username')
            ->type('ariel','first_name')
            ->type('villalobos','last_name')
            //luego de llenar los campos, apretara en el boton registrate
            ->press('Registrate');

            //una vez que el sistema procese el registro,deberiamos ver en la bd
            $this->seeInDatabase('users',[
                'email'=>'admin@foro.net',
                'username'=>'ariellonko',
                'first_name'=>'ariel',
                'last_name'=>'villalobos'
            ]);

            //accedemos al usuario
            $user=\App\User::first();
            //como va hacer un sistema d logeo basado en tokens , no le pedimos al usuario que ingrese clave
            //deberia ver en la tokens, que tenemos un nuevo token generado para el usuario
            $this->seeInDatabase('tokens',[
                'user_id'=>$user->id
            ]);
            $token=Token::where('user_id',$user->id)->first();
            //que el token no sea nulo/ osea obtener el token
            $this->assertNotNull($token);

            //tambien quiero comprobar que el usuario recibe dicho token
            //1er argumento el usuario que debe recibir el email
            //2do argumento clase mailalble que nosotros estamos utilizando para enviar el email
            //3ro funcion anonima para asegurarme que el usuario recibe el mismo token que fue generado
            Mail::assertSentTo($user,TokenMail::class,function ($mail) use ($token){
                return $mail->token->id==$token->id;
            });

            //luego quiero que sea redirigito a esta ruta
            $this->seeRouteIs('register_confirmation')
                ->see('Gracias por registrarte')
                ->see('Enviamos un enlace para que inicies sesion');


    }
}
