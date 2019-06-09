<?php



use Illuminate\Foundation\Testing\DatabaseTransactions;

class PostIntegrationTest extends TestCase
{

    //las pruebas de integracion donde probamos el modelo de post y tambien problamos la integracion del modelo con la bd
    //es decir la integracion de dos componentes con el sistema

    //importo el trait
    //uso databasetransactions para asegurarme de que esta prueba no va a ensuciar la base de datos d prueba

    use DatabaseTransactions;
    public function test_a_slug_is_generated_and_save_to_database()
    {
        //crearemos el usuario dentro de la factory
       // $user=$this->defaultUser();

        //uso el modelo factory para generar el post ya que el contenido lo va a generar el factory
        //asi no lo estoy generando
        //ahora definimos un metodo CreatePost() en TestCase, que adentro tiene la llamada a la factoria asi ahorrmos codigo
        $post=$this->createPost([
            'title'=>'Como instalar laravel',
        ]);

        //$user->posts()->save($post);

        //obtiene una instancia fresca
        $this->assertSame('como-instalar-laravel',$post->fresh()->slug);

        //tambien podemos usar estos otros metodos que son similares
        /*$this->seeInDatabase('posts',[
            'slug'=>'como-instalar-laravel',
            
        ]);

        $this->assertSame('como-instalar-laravel',$post->slug);*/



    }
}
