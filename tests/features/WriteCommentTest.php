<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class WriteCommentTest extends FeatureTestCase
{

    public function test_a_user_write_comment()
    {
        $post=$this->createPost();
        $user=$this->defaultUser();
        //necesitamos que el usuario este logueado
        $this->actingAs($user)
            //se visita
            ->visit($post->url)
            //vamos a escribir un comentario
            //veremos un campo de name comment
            ->type('un comentario','comment')
            //se presiona el boton
            ->press('Publicar comentario');

            //tenemos en la bd
            $this->seeInDatabase('comments',[
                'comment'=>'Un comentario',
                'user_id'=>$user->id,
                'post_id'=>$post->id,

            ]);

            //una vez escrito y publicado el comentario
            //sea redirigido a la url del detalle del post
            $this->seePageIs($post->url);
    }
}
