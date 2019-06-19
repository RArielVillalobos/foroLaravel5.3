<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class AuthenticationTest extends FeatureTestCase
{

    public function test_a_user_can_login_with_a_token_url()
    {
        //having
        $user=$this->defaultUser();
        $token=\App\Token::generateFor($user);

        //when
        //cuando el usuario visite la url para iniciar sesion

        $this->visit("login/{$token->token}");

        //cuando visite esa ruta, deberia ver que el usuario es autenticado
        $this->seeIsAuthenticated()
            ->seeIsAuthenticatedAs($user);
        //eliminar token
        $this->dontSeeInDatabase('tokens',[
            'id'=>$user->id
        ]);

        $this->seePageIs('/');





    }
}
