<?php


use Illuminate\Foundation\Testing\DatabaseTransactions;
use App\Policies\CommentPolicy;

class CommentPolicyTest extends TestCase
{
    use DatabaseTransactions;

    //el autor del post puede  seleccionar una respuesta
    public function test_the_post_author_can_select_a_comment_as_an_answer()
    {
        $comment=factory(\App\Comment::class)->create();
        $policy=new CommentPolicy();
        //es el autor del post el que puede marcar como correcto una respuesta
        //no el autor del comentario $comment->user
        //afirmacion de que el autor del post puede aceptar un comentario como la respuesta del post
        $this->assertTrue($policy->accept($comment->post->user,$comment));

    }

    //las personas que no son autores no pueden seleccionar un comentario como una respuesta
    public function test_non_authors_cannot_select_a_comment_as_an_answer()
    {
        $comment=factory(\App\Comment::class)->create();
        $policy=new CommentPolicy();

        //espero que el metodo accept devuelva falso cuando pase otro usuario que no sea el author del post
        $this->assertFalse($policy->accept(factory(\App\User::class)->create(),$comment));

    }


}
