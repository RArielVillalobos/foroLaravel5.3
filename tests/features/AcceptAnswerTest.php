<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class AcceptAnswerTest extends FeatureTestCase
{

    public function test_the_post_author_can_accept_a_comment_as_the_post_answer()
    {
        //creo un post
        //
        //o creo un comentario que ya me va a dar un post y un autor
        $comment = factory(\App\Comment::class)->create([
            'comment' => 'Esta va a ser la respuesta del post'
        ]);

        //actuo como un usuario(sera el autor del post)
        $this->actingAs($comment->post->user);
        //visito la url d post
        $this->visit($comment->post->url)
            //deberia poder ver un boton que diga aceptar respuesta
            ->press('Aceptar respuesta');

        //comprobamos los cambios en la bd, ya esta en la prueba de integracion , pero no esta mal hacerlo
        $this->seeInDatabase('posts', [
            'id' => $comment->post_id,
            //el post ya no deberia estar pendiente
            'pending' => false,
            //la respuesta deberia estar relacionada a este comentario
            'answer_id' => $comment->id
        ]);


        //soy redirigido a la url del post
        $this->seePageIs($comment->post->url)
            //deberia poder ver la clase .answer que contenga el comentario
            //vere un listado con los comentarios pero solo la respuesta llevara esta clase
            ->seeInElement('.answer', $comment->answer);


    }
    //prueba de regresion
    //los usuarios que no son autor del post no pueden ver el boton aceptar respuesta
    public function test_non_post_author_cannot_see_the_accept_answer_button()
    {
        //creo un post
        //
        //o creo un comentario que ya me va a dar un post y un autor
        $comment=factory(\App\Comment::class)->create([
            'comment'=>'Esta va a ser la respuesta del post'
        ]);

        //actuo como un usuario cualquiera
        $this->actingAs($this->defaultUser());
        //visito la url d post
        $this->visit($comment->post->url)
            //los usuarios que no son dueños de los post no pueden ver este boton
            ->dontSee('Aceptar respuesta');


    }
    //prueba de regresion
    //que no modifique la peticion
    public function test_non_post_author_cannot_accept_a_comment_as_the_post_answer()
    {
        //creo un post
        //
        //o creo un comentario que ya me va a dar un post y un autor
        $comment=factory(\App\Comment::class)->create([
            'comment'=>'Esta va a ser la respuesta del post'
        ]);

        //actuo como un usuario cualquiera
        $this->actingAs($this->defaultUser());

        //envio una peticion post a la url para aceptar un comentario
        $this->post(route('comments.accept',$comment));

        //no deberia ver en en la base de datos que el post esta como pendiente
        $this->dontSeeInDatabase('posts',[
            'id'=>$comment->post_id,
            'pending'=>false,

        ]);

    }

    //prueba de regresion
    //el boton aceptar respuesta cuando el comentario ya esta marcado como respuesta del post
    public function test_the_accept_button_hidden_when_the_comment_is_already_the_post_answer()
    {
        //creo un post
        //o creo un comentario que ya me va a dar un post y un autor
        $comment=factory(\App\Comment::class)->create([
            'comment'=>'Esta va a ser la respuesta del post'
        ]);
        //quiero que este usuario pueda aceptar la respuesta del post
        $this->actingAs($comment->post->user);
        //este comentario ya va a hacer la respuesta del post
        $comment->markAsAnswer($comment->post);

        //visito la url d post
        $this->visit($comment->post->url)
            ->dontSee('Aceptar respuesta');


    }

}