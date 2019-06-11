 <?php

use Illuminate\Foundation\Testing\DatabaseTransactions;

class MarkCommentAsAnswerTest extends TestCase
{
    use DatabaseTransactions;

    //probar si un un post puede ser respondido
    public function test_a_post_can_be_answerd()
    {
        $post=$this->createPost();
        $comment=factory(\App\Comment::class)->create([
            'post_id'=>$post->id,


        ]);


        //marcar un comentario como respuesta
        $comment->markAsAnswer($post);
        //asegurarme de que este comentario esta marcado como la respuesta del post
        //podria tener una columna llamada answer que podria ser true o false
        //requerimos que sea true
        //si no marcamos el fresh y nos olvidamos de hacer el ->save() en el modeo, pasara la prueba, por eso es mejor usar el fresh para obtener una instancia fresca
        $this->assertTrue($comment->fresh()->answer);


        $this->assertFalse($post->fresh()->pending);


    }

    //hasta aca dos comentarios podrian quedar como respuesta  a un post
    //lo demostramos con una prueba de REGRESSION(tipo de pruebas de software que intentan descubrir errores, carencias de funcionalidad, o divergencias funcionales con respecto al comportamiento esperado del software, )
    //comprabamos que un post solamente puede tener una sola respuesta
    public function test_a_post_can_only_have_one_answer()
    {
        $post=$this->createPost();
        //crearemos dos comentarios
        //esto nos devolvera una coleccion de comentarios
        $comments=factory(\App\Comment::class)->times(2)->create([
            'post_id'=>$post->id,
        ]);
        //dd($comments->toArray());
        //marcar un comentario como respuesta
        //llamare al metodo por cada comentario(tanto para el primero como para el segundo)
        $comments->first()->markAsAnswer($post);
        $comments->last()->markAsAnswer($post);


        //esperaria que el primer comentario de la coleccion no este marcado como respuesta
        //osea que answer seria falso
        //fresh->obtener una instancia fresca de la bd
        $this->assertFalse($comments->first()->fresh()->answer);

        //que el ultimo comentario de la coleccion sea marcado como respuesta
        $this->assertTrue($comments->last()->fresh()->answer);
    }

}
