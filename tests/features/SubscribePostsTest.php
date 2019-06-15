<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class SubscribePostsTest extends FeatureTestCase
{

    public function test_a_user_can_subscribe_to_a_post()
    {
        //having
        $post=$this->createPost();
        $user=$this->defaultUser();
        $this->actingAs($user);

        //when
        $this->visit($post->url)
            ->press('Subscribirse al post');

        //then
        $this->seeInDatabase('subscriptions',[
            'user_id'=>$user->id,
            'post_id'=>$post->id
        ]);
        $this->seePageIs($post->url)
            ->dontSee('Subscribirse al post');
            //esto suena a que es una tarea a parte lo haremos en otra
            //luego crearemos una prueba para esto
            //->see('Desubscribrse del post')


    }

    public function test_a_user_can_unsubscribe_from_a_post(){
        //having
        $post=$this->createPost();
        $user=$this->defaultUser();
        $this->actingAs($user);
        $user->subscribeTo($post);

        //when
        $this->visit($post->url)
            ->dontSee('Subscribirse al post')
            ->press('Desuscribirse del post');

         $this->dontSeeInDatabase('subscriptions',[
            'user_id'=>$user->id,
            'post_id'=>$post->id
         ]);

         $this->seePageIs($post->url)
                ->see('Subscribirse al post');






    }
}
