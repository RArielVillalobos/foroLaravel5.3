<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\Notification;

class NotifyUsersTest extends FeatureTestCase
{
     public function test_the_subscribers_receive_a_notification_when_post_is_commented(){
         //notificacion de prueba
         Notification::fake();


         $post= $this->createPost();
         //un usuario se suscribe al post
         $subscriber=factory(\App\User::class)->create();
         $subscriber->subscribeTo($post);
         //el usuario que crea un comentario
         $writer=factory(\App\User::class)->create();

         $comment=$writer->comment($post,'Un comentario cualquiera');

         //comprobar que una notificacion fue enviada a un usuario
         Notification::assertSentTo($subscriber,\App\Notifications\PostCommented::class,function ($notificacion) use ($comment){
             //comprobar que esta notificacion recibe el post por el cual quiero notificar al usuario
             return $notificacion->comment->id==$comment->id;


         });

         //comprobar que el autor del comentario no recibe una notificacion de su propio comentario
         //primer argumento es el usuario al que no quiero enviarle la notificacion,2da clase la clase de la notificacion
         Notification::assertNotSentTo($writer,\App\Notifications\PostCommented::class);



     }
}
