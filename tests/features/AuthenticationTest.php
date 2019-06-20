<?php



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
            'id'=>$token->id
        ]);

        $this->seePageIs('/');
    }
    //prueba de regresion
    //comprobar que un usuario no puede iniciar sesion con un token invalido
    public function test_a_user_cannot_login_with_an_invalid_token(){
        //having
        $user=$this->defaultUser();
        $token=\App\Token::generateFor($user);
        $invalidToken=str_random(60);


        //when
        //cuando el usuario visite la url para iniciar sesion

        $this->visit("login/{$invalidToken}");

        //no deberia ver que el usuario este autenticado
        $this->dontSeeIsAuthenticated()
            //enviar al usuario a esta ruta para que solicite otro token
            ->seeRouteIs('token')
            //mostrar error
            ->see('Este enlace ya expiró,solicite otro');
        //en la bd deberia estar el token que es valido
        //ya que no fue utilizado,no fue eliminado
        $this->seeInDatabase('tokens',[
            'id'=>$token->id
        ]);

    }
    //prueba de regresion
    //el usuario no debe ingresar con el mismo token 2 veces
    public function test_a_user_cannot_use_the_same_token_twice(){
        //having
        $user=$this->defaultUser();
        $token=\App\Token::generateFor($user);
        //el usuario ya utiliza el token
        $token->login();
        //cierra sesion
        \Illuminate\Support\Facades\Auth::logout();


        //when
        //cuando el usuario visite la url para iniciar sesion

        $this->visit("login/{$token->token}");

        //cuando visite esa ruta, deberia ver que el usuario no es autenticado
        $this->dontSeeIsAuthenticated()
            //enviar al usuario a esta ruta para que solicite otro token
            ->seeRouteIs('token')
            //mostrar error
            ->see('Este enlace ya expiró,solicite otro');
    }

    //prueba de regresion
    //el token va a expirar despues de 3o minutos
    public function test_the_token_expires_after_30_minutes(){
        //having
        $user=$this->defaultUser();
        $token=\App\Token::generateFor($user);
        //simulo que pasaron 31 minutos
        \Carbon\Carbon::setTestNow(\Carbon\Carbon::parse('+31 minutes'));

        $this->visit("login/{$token->token}");

        //cuando visite esa ruta, deberia ver que el usuario no es autenticado
        $this->dontSeeIsAuthenticated()
            //enviar al usuario a esta ruta para que solicite otro token
            ->seeRouteIs('token')
            //mostrar error
            ->see('Este enlace ya expiró,solicite otro');


    }

    /*public function test_the_token_is_case_sensitive(){
        //having
        $user=$this->defaultUser();
        $token=\App\Token::generateFor($user);
        $token=strtolower($token->token);


        //convierto el token a minuscula
        //no deberia ser valido, ya que el token original tiene mayus y minus
        //deberia en el loginController poner el === cuando filtre la busqueda
        $this->visit("login/$token");

        //cuando visite esa ruta, deberia ver que el usuario no es autenticado
        $this->dontSeeIsAuthenticated()
            //enviar al usuario a esta ruta para que solicite otro token
            ->seeRouteIs('token')
            //mostrar error
            ->see('Este enlace ya expiró,solicite otro');


    }*/

}
