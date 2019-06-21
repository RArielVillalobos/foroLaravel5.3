<?php
/**
 * Created by PhpStorm.
 * User: ariel
 * Date: 21/jun/2019
 * Time: 01:39
 */

namespace Tests;


trait TestsHelper
{


    protected $defaultUser;

    public function defaultUser(array $attributes=[]){
        if($this->defaultUser){
            return $this->defaultUser;
        }
        return $this->defaultUser= factory(\App\User::class)->create($attributes);
    }

    public function createPost(array $attributes=[]){
        return $post=factory(\App\Post::class)->create($attributes);

    }
}