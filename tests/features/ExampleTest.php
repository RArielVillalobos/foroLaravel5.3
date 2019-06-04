<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class ExampleTest extends FeatureTestCase
{
    //al usar este trait cada vez que ejecutamos una prueba ejecuta un migrate y luego un rollback
    //osea la base de datos siempre termina vacia
   // use DatabaseMigrations;

    //mejor es usar databasetransactions
    //todas las consultas se haran dentro de una transaction
    //como ya lo estoy usando en FeatureTestCase lo puedo comentar
    //use DatabaseTransactions;
    /**
     * A basic functional test example.
     *
     * @return void
     */
    public function test_basic_example()
    {
        //creando usuario
        //como factory usa faker para crear los usuarios debemos definir el nombre ariel para que la prueba pase
        //cada vez que ejecutamos la prueba estamos creando un usuario nuevo por eso si la ejecutamos dos veces dara duplicidad en el email(podemos usar el trait databasemigrations)
        //o mejor aun podemos usar databaseTransaction porque el databasemigrations si tenemos muchas migraciones sera muy lento
        $user=factory(\App\User::class)->create([
            'name'=>'Ariel Villalobos',
            'email'=>'arieldjmix@gmail.com'
        ]);
        //aseguramdome que inicie sesion con el driver api
        $this->actingAs($user,'api');
        $this->visit('api/user')
             ->see('Ariel Villalobos')
            ->see('arieldjmix@gmail.com');
    }
}
