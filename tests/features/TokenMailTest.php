<?php


use Illuminate\Support\Facades\Mail;

class TokenMailTest extends FeatureTestCase
{

//lo quiero comprobar es que el mailable puede constuir la vista correctamente y que la vista contenga el token
 //comprobar que el mailable envie un enlace con el token
 //una forma es usar smtp->mailtrap y luego usando la api de mailtrap obtener los datos del email
 //aunque esto haria la prueba muy lenta y si se cae internet la prueba fallaria asi que usaremos otra opcion
 //el mailer de laravel que esta basado en swiftmailer, tiene varios transportes
 //usaremos log asi que debemos configurar el archivo phpunit.xml poniendo la llave MAIL_DRIVER en el valor log
 //ahora en storage, en la carpeta logs, archivo laravel.log veremos el log del email
 //podriamos acceder al archivo del log y comprobar que el html sea el correcto,peroe esto seria engorroso
 //laravel 5.3 incluye un nuevo transporte para guardar los mensajes en memoria (ArrayTransport)
 //debemos configurar el phpunit.xml con array como valor en MAIL_DRIVER

    public function test_it_sends_a_link_with_the_token(){
        $user=new \App\User([
            'first_name'=>'ariel',
            'last_name'=>'villalobos',
            'email'=>'ariel.villalobos96@gmail.com'
        ]);
        $token=new \App\Token([
            'token'=>'this-is-a-token',
            'user_id'=>$user->id
        ]);
        $this->open(new \App\Mail\TokenMail($token));

        $this->seeLink($token->url,$token->url);


    }

    public function open(\Illuminate\Mail\Mailable $mailable){
        $transport=Mail::getSwiftMailer()->getTransport();
        $transport->flush();
        Mail::send($mailable);
        $message=$transport->messages()->first();
        //el contenido del crawler va a ser el mensajd del email
        $this->crawler=new \Symfony\Component\DomCrawler\Crawler($message->getBody());
    }
}
