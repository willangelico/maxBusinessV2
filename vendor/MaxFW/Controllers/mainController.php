<?php

namespace MaxFW\Controllers;

use MaxFW\Controllers\UserController;
use MaxFW\Models\DB;

class MainController extends UserController
{
    private $params;
    public $db;
    public $twig;

    public function __construct(string $table, string $folder)
    {        
        parent::__construct($table, $folder);
        $this->checkLogin();        
    }

    public function setParams(array $params)
    {
        $this->params = $params;
    }

    public function getParams()
    {
        return $this->params;
    }

    public function configTemplate(string $folder = NULL){
        if($folder){
            $loader = new \Twig_Loader_Filesystem("app\\$folder\\Views");
            // $this->twig = new \Twig_Environment($loader, array(
            //     'cache' => "app\\$folder\\Views\\Cache",
            // ));
             $this->twig = new \Twig_Environment($loader);
             return;
        }
        $loader = new \Twig_Loader_Filesystem("app\\Views");
        // $this->twig = new \Twig_Environment($loader, array(
        //     'cache' => "app\\Views\\Cache",
        // ));
         $this->twig = new \Twig_Environment($loader);
         return;
        
    }

    public function render(string $path, array $array)
    {
        return $this->twig->render($path,$array);
    }

    public function loadModel(string $model ) {
    
        // Um nome de arquivo deverá ser enviado
        if ( ! $model ) return;
    
        $model = "\\MaxBusiness\\Models\\{$model}Model";
        
        return new $model($this->db,$this);
        // // Inclui o arquivo
        // $model_path = ABSPATH .'/'. APPLICATION .'/models/' . $model_name . '.php';
        
        // // Verifica se o arquivo existe
        // if ( file_exists( $model_path ) ) {
        //     // Inclui o arquivo
        //     require_once $model_path;
            
        //     // Remove os caminhos do arquivo (se tiver algum)
        //     $model_name = explode('/', $model_name);
            
        //     // Pega só o nome final do caminho
        //     $model_name = end( $model_name );
            
        //     // Remove caracteres inválidos do nome do arquivo
        //     $model_name = preg_replace( '/[^a-zA-Z0-9]/is', '', $model_name );
            
        //     // Verifica se a classe existe
        //     if ( class_exists( $model_name ) ) {
        //         // Retorna um objeto da classe
        //         return new $model_name( $this->db, $this );
            
        //     }
            
        //     // The end :)
        //     return;
            
        // }else{
        // } // load_model
        
    } // load_model
    

    
    
}