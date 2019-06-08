<?php

use App\Post;

class PostModelTest extends TestCase
{
    //las pruebas unitarias intentamos probar una sola clase sin llamar a la base de datos
    //y procurando no interactuar con otras clases

    //agregar un titulo genera un slug
    public function test_adding_a_title_generates_a_slug()
    {
        //cuando cree un post y le asigne un titulo
        $post=new Post([
            'title'=>'Como instalar laravel',

        ]);
        //en este punto el post deberia tener una propiedad llamada  slug que contenga como-instalar-lravel
        $this->assertSame('como-instalar-laravel',$post->slug);



    }

    public function test_editing_the_title_changes_the_slug(){
        $post=new Post([
            'title'=>'Como instalar laravel',

        ]);

        $post->title='Como instalar laravel 5.1 LTS';
        $this->assertSame('como-instalar-laravel-51-lts',$post->slug);
    }
}
