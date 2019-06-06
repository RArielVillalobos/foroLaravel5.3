<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class ShowPostTest extends TestCase
{
    //comprobar que un usuario puede ver los detalles de un post
    public function test_a_user_can_see_the_post_details(){

        //having
        $user=$this->defaultUser([
            'name'=>'Ariel Villalobos',

        ]);
        //en ves de usar create usaremos make , que no guardara el modelo en la base de datos aun a diferencia del create
        $post=factory(\App\Post::class)->make([
            'title'=>'Como instalar laravel',
            'content'=>'Este es el contenido del post',

        ]);
        //le asigno el post al usuario
        $user->posts()->save($post); //asigna automaticamente el user_id al post

        //when
        $this->visit(route('posts.show',$post)) //posts/1023-->es el id
            ->seeInElement('h1',$post->title)
            //tambien en la misma pagina deberiamos ver el contenido
            ->see($post->content)
            ->see($user->name);




    }
}
