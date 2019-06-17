<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class PostCommentedTest extends TestCase
{

    use DatabaseTransactions;
    //@test
    //comprobar que se esta construyendo un mensaje de email
    public function test_it_builds_a_mail_message()
    {
        $post=factory(\App\Post::class)->create([
            'title'=>'Titulo del post'
        ]);
        $author=factory(\App\User::class)->create([
            'name'=>'Ariel villalobos'
        ]);
        $comment=factory(\App\Comment::class)->create([
            'post_id'=>$post->id,
            'user_id'=>$author->id
        ]);

        //queremos comprobar que podememos instanciar la clase de la notificacion
        $notification=new \App\Notifications\PostCommented($comment);

        $subscriber=factory(\App\User::class)->create();
        $message=$notification->toMail($subscriber);
        //esta es la clase que espera laravel
        $this->assertInstanceOf(\Illuminate\Notifications\Messages\MailMessage::class,$message);
        //comprobar que en el asunto del mensaje diga
        $this->assertSame('Nuevo comentario en: Titulo del post',$message->subject);
        $this->assertSame('Ariel villalobos escribio un comentario en: Titulo del post',$message->introLines[0]);
        $this->assertSame($comment->post->url,$message->actionUrl);
    }
}
