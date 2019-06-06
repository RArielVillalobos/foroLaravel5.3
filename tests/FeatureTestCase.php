<?php
/**
 * Created by PhpStorm.
 * User: ariel
 * Date: 4/jun/2019
 * Time: 01:05
 */

use Illuminate\Foundation\Testing\DatabaseTransactions;
class FeatureTestCase extends TestCase
{

    use DatabaseTransactions;

    //definimos un metodo para el test

    /**
     * @param array $fields
     */
    public function seeErrors(array $fields){
        foreach($fields as $name=>$errors){
            //que convierta en array

            foreach ((array) $errors as $message){
                
                $this->seeInElement("#field_{$name}.has-error .help-block" ,"{$message}");
            }

        }


    }
}