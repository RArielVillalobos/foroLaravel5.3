<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class PostListTest extends FeatureTestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function test_a_user_can_see_the_posts_list_and_go_to_the_details()
    {
        $post=$this->createPost([
            'title'=>'debo usar laravel 5.3 0 5.1 LTS'

        ]);

        $this->visit('/')
            ->seeInElement('h1','Posts')
            ->see($post->title)
            //deberia por hacer click en el post
            ->click($post->title)
            //al hacer click en el post deberia ser llevado a la url
            ->seePageIs($post->url);
    }
}
