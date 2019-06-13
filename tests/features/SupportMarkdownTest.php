<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class SupportMarkdownTest extends FeatureTestCase
{

    public function test_the_post_content_support_markdown()
    {
        $importantText='Un texto muy importante';
        $post=$this->createPost([
            //el texto texto importante debe estar en negrita asi que el usuario lo cierra en doble asterisco **
            'content'=>"La primera parte del texto.**$importantText**. la ultima parte del texto",

        ]);

        //cuando algun usuario visite la url del post
        //deberia ver el texto en negrita
        $this->visit($post->url)
            ->seeInElement('strong',$importantText);
    }

    //probar que no estoy expuesto a ataques xss por ej inyeccion d js
    public function test_xss_attack()
    {
        $xssAtack = "<script>alert('malicius js')</script>";
        $post = $this->createPost([
            'content' => "$xssAtack. texto normal"
        ]);

        $this->visit($post->url)
            //cuando un usuario visite el post no deberia ver el ataque xss
            //busca en el html al igual que see
            ->dontSee($xssAtack)
            ->seeText('texto normal')
            //se muestra como texto plano
            ->seeText($xssAtack);
            //al final veremos el javascript impreso como texto plano
    }

    public function test_the_code_in_the_post_is_escaped(){
        $xssAtack="<script>alert('malicius js')</script>";
        $post=$this->createPost([
            'content'=>"`$xssAtack`. texto normal"
        ]);

        $this->visit($post->url)
            //cuando un usuario visite el post no deberia ver el ataque xss
            //verlo como texto normal
            ->dontSee($xssAtack)
            ->seeText('texto normal')
            //deberiamos verlo como texto escapado y como html por eso uso seetext
            ->seeText($xssAtack);


    }

    public function test_xss_attack_with_html(){
        $xssAtack="<img src='imagen.jpg'>";
        $post=$this->createPost([
            'content'=>"$xssAtack. texto normal"
        ]);

        $this->visit($post->url)
            //cuando un usuario visite el post no deberia ver el ataque xss

            ->dontSee($xssAtack);


    }

}
