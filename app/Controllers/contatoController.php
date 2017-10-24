<?php

namespace MaxBusiness\Controllers;

use MaxFW\Controllers\MainController;

class ContatoController
{
    
    public function __construct(MainController $main)
    {
        //var_dump($main->getParams());        
        $this->main = $main;      
    }
    
    public function index()
    {
        $params = $this->main->getParams();
        if(! $params ){
            $params[0] = "Nenhum parametro";
        }
        echo $this->main->twig->render('\\contato\\index.html', 
            array('name' => $params[0]));
    }
    
    public function enviar()
    {
        echo $this->main->twig->render('\\contato\\enviar.html', array('name' => 'página contato enviar'));
    }
}