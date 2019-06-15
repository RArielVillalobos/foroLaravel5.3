<?php
/**
 * Created by PhpStorm.
 * User: ariel
 * Date: 4/jun/2019
 * Time: 01:40
 */

class CreatePostsTest extends FeatureTestCase
{
    //las pruebas funcionanes de aplicaciones o features
    //a traves de la api que provee laravel que somos un usuario y tratamos d hacer o seguir las acciones que haria un user
    //ej crear un post
    //en las pruebas unitarias o de integracion es mas facil saber si nos equivamos y donde
    //de las  3 pruebas(unitarias,integracion y funcionales ) esta es la mas lenta porque necesita cargar el controlador,la vista,revisar la bd,etc
    //es la prueba que nos da una confianza mayor que todo el feature esta funcionando
    //recomendacion primer crear este tipo de prueba,fncionales o de apliaciones como queramos llamale
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
                'user_id'=>$user->id,
                'slug'=>'esta-es-una-pregunta',
            ]);
            $post=\App\Post::first();
            //se subscribe automaticamente a su post
            $this->seeInDatabase('subscriptions',[
                'user_id'=>$user->id,
                'post_id'=>$post->id
            ]);

            $this->seePageIs($post->url);


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