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
        //dd(route('posts.show',$post));

        //when
        //la funcion url esta definida en el modelo post
        $this->visit($post->url) //posts/1023-->es el id
            ->seeInElement('h1',$post->title)
            //tambien en la misma pagina deberiamos ver el contenido
            ->see($post->content)
            ->see($user->name);




    }
    //url viejas redirijan a las urls nuevas
    public function test_old_urls_are_redirected(){
        //having
        $user=$this->defaultUser();

        $post=factory(\App\Post::class)->make([
            'title'=>'Old title',


        ]);

        $user->posts()->save($post);
        $url=$post->url;

        //actualizamos el post
        $post->update(['title'=>'New title']);

        //cuando visite la url antigua la pagina sea  la url nueva
        //seria q lo redirigimos a la url nueva
        $this->visit($url)
            ->seePageIs($post->url);


    }

   /* function test_post_url_with_wrong_slugs_still_work(){
        //having

        $user=$this->defaultUser();

        $post=factory(\App\Post::class)->make([
            'title'=>'Old title',


        ]);

        $user->posts()->save($post);
        $url=$post->url;

        //actualizamos el post
        $post->update(['title'=>'New title']);

        //visitando url obsoleta
        $this->visit($url)
            ->assertResponseOk()
            ->see('New title');


    }*/

}
