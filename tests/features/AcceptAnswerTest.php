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
        $comment=factory(\App\Comment::class)->create([
            'comment'=>'Esta va a ser la respuesta del post'
        ]);

        //actuo como un usuario(sera el autor del post)
        $this->actingAs($comment->post->user);
        //visito la url d post
        $this->visit($comment->post->url)
            //deberia poder ver un boton que diga aceptar respuesta
            ->press('Aceptar respuesta');

        //comprobamos los cambios en la bd, ya esta en la prueba de integracion , pero no esta mal hacerlo
        $this->seeInDatabase('posts',[
            'id'=>$comment->post_id,
            //el post ya no deberia estar pendiente
            'pending'=>false,
            //la respuesta deberia estar relacionada a este comentario
            'answer_id'=>$comment->id
        ]);


        //soy redirigido a la url del post
        $this->seePageIs($comment->post->url)
            //deberia poder ver la clase .answer que contenga el comentario
            //vere un listado con los comentarios pero solo la respuesta llevara esta clase
            ->seeInElement('.answer',$comment->answer);




    }
}
