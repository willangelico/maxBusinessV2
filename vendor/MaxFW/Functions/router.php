<?php 

namespace MaxFW\Functions;

use MaxFW\Controllers\mainController;

class Router
{
    private $folder;
    private $controller;
    private $action;
    private $param;
    private $helpers;

    public function __construct(helpers $helpers)
    {	
        $this->helpers = $helpers;
        $this->run($this->getUrl());
    }

    private function run(string $url)
    {
        $array_url = explode("/",$url);
        $array_url[1] = empty($array_url[1]) ? "index" : $array_url[1];
        $array_url[2] = empty($array_url[2]) ? "index" : $array_url[2];

        // Pega as pastas extras
        $folders = explode('|',FOLDERS);

        // Configura as propriedades pelas pastas extras
        if( in_array($array_url[1] , $folders) ){
            $array_url[3] = empty($array_url[3]) ? "index" : $array_url[3];
            $this->folder = $this->helpers->checkArray( $array_url,1 );
            $this->controller = $this->helpers->checkArray( $array_url, 2 );
            $this->action = $this->helpers->checkArray( $array_url, 3 );
            $class = "MaxBusiness\\".$this->folder."\\Controllers\\".ucfirst($this->controller)."Controller";

           // Retira os campos de função da url
            unset( $array_url[0]);      
 

        }else{
             $this->controller = $this->helpers->checkArray( $array_url, 1 );
                $this->action = $this->helpers->checkArray( $array_url, 2 );
                $class = "MaxBusiness\\Controllers\\".ucfirst($this->controller)."Controller";  
        }
        

        // array_walk($folders, function($folder) use ($array_url){

        //     if($array_url[1] == $folder){
        //         echo $folder;

        //         return;
        //     }else{   

               
        //     }
        // }); 

            unset($array_url[0]);

            // Seta o restante da url como parametros
            $this->param = array_values( $array_url );
            // Verifica se existe a classe
            if (! class_exists($class)) { 
              
                $controllerPages = new \MaxBusiness\Controllers\PagesController($this->setParams());

                if(! $controllerPages->friendlyUrl() ){

                    header("HTTP/1.0 404 Not Found");
                    echo "Erro 404";
                    die();
                }
                exit;
            }    

            // Verifica se é um método da classe
            if( ! method_exists( $class, $this->action )){
                $this->action = 'friendlyUrl';

            // Retira os campos de função da url
                unset( $array_url[1] );
            }else{
            // Retira os campos de função da url
                unset( $array_url[1], $array_url[2]);
            }        	

            // Seta o restante da url como parametros
            $this->param = array_values( $array_url );

            

            // Instacia a classe controladora passando a classe main com os parametros
            $controller = new $class($this->setParams());
            // Seta a ação
            $action = $this->action;  


            // Chama a função da classe
            $controller->$action();
            die();
        	
    }

    private function setParams()
    {

        $access_table = !$this->folder ? 'users' : $this->folder == 'admin' ? 'users' : $this->folder;
        $main = new mainController($access_table, $this->folder);		
        $main->setParams($this->param); 
        $main->configTemplate($this->folder);
        return $main;        
    }

    private function getUrl()
    {
        return parse_url($_SERVER["REQUEST_URI"], PHP_URL_PATH);
    }
}
