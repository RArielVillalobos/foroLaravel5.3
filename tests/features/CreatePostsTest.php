<?php
/**
 * Created by PhpStorm.
 * User: ariel
 * Date: 4/jun/2019
 * Time: 01:40
 */

class CreatePostsTest extends FeatureTestCase
{

    public function test_a_user_create_a_post(){
        $user=$this->defaultUser();
        //simular que un usuario esta conectado actionAs
        $this->actingAs($user)
            //eventos de la prueba

            //visita la ruta
            ->visit(route('posts.create'))
            ->type('esta es una pregunta','title')
            ->type('este es el contenido','content')
            //boton publicar
            ->press('Publicar');

            //resutlados
            //deberiamos poder  ver en la base de datos
            $this->seeInDatabase('posts',[
                'title'=>'esta es una pregunta',
                'content'=>'este es el contenido',
                'pending'=>true,
                'user_id'=>$user->id
            ]);

            //si el usuario fue redirigido a otra pagina(detalle del posts)
            //comprobamos si vemos el titulo del post
            //$this->seeInElement('h1','esta es una pregunta');
            //para simplificar la prueba usaremos el metodo see
            $this->see('esta es una pregunta');

    }
    public function test_creating_a_post_requires_authentication(){
        //@todo: add validation

        //cuando intentemos ingresar sin haber logueado que nos lleve al login
        $this->visit(route('posts.create'))->seePageis(route('login'));



    }

    public function test_create_post_form_validation(){

        $this->actingAs($this->defaultUser())
            ->visit(route('posts.create'))
            //presionar el boton publicar sin completar ningun campo
            ->press('Publicar')
            //la pagina todavia deberia ser post create
            ->seePageIs(route('posts.create'))
            //deberiamos ver una serie de erroers
            //el elemento field_title tenga el bloque de ayuda
            //si el campo tiene un error tendra la clase .help-error(definido el el metodo errors)
            //aca usamos el metodo que acabamos de definir en FeatureTestcase
            ->seeErrors([
                'title'=>'El campo título es obligatorio',
                'content'=>'El campo contenido es obligatorio'
            ]);

    }
}