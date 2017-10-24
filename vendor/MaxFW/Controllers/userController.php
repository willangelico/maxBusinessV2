<?php

namespace MaxFW\Controllers;

use MaxFW\Models\DB;
use Phpass\Hash;
use PDO;

class UserController
{  

    public $login_is;
    public $login_error;    
    private $post = FALSE;
    private $table;

    /*
    * Verifica se está logado
    */    

    public function __construct(string $table, string $folder){
        $this->db = new DB;
        $this->phpassHash = new hash;
        $this->table = $table;
        $this->folder = $folder;


    }

    public function checkLogin()
    {
        
        $userdata = array();
        // COOKIE
        // Verifica se existe um cookie com a chave do usuário
        if ( isset( $_COOKIE['userdata'] )
             && ! empty( $_COOKIE['userdata'] ) 
             && ! isset( $_POST['userdata'] )
            ) { 

            $userdata = $this->getLoginCookie(); 

            $this->setSession($userdata);            
         
        }

        // SESSION
        // Verifica se existe uma sessão com a chave userdata
        if ( isset( $_SESSION['userdata'] )
             && ! empty( $_SESSION['userdata'] )
             && is_array( $_SESSION['userdata'] ) 
             && ! isset( $_POST['userdata'] )
            ) { 

            $userdata = $this->getLoginSession();

            if( session_id() != $userdata['session_id'] 
                && $this->post == FALSE 
                ){

                // Mata a Login
                $this->dieLogin("Sessão expirou!");   
            }
            $this->login_is = TRUE;
            unset($_SESSION['login_error']);
          
        }

        // POST
        // Verifica se existe um HTTP POST com a chave userdata
        if ( isset( $_POST['userdata'] )
             && ! empty( $_POST['userdata'] )
             && is_array( $_POST['userdata'] ) 
            ) {

            $userdata = $this->getLoginPOST();

            $this->post = TRUE;

            $this->setLogin( $userdata );

            return;
        }

        //$this->isUserdata($userdata);


    }

    private function getLoginCookie()
    {
        // Extrai dados do cookie
        $cookie = explode('&',$_COOKIE['userdata']);

        // Consulta se existe a chave
        $query = $this->db->query(
            "SELECT * FROM {$this->table} WHERE id = ? LIMIT 1",
            array( base64_decode($cookie[0]))
        );

        // Verifica a consulta
        if ( ! $query ){
            // Mata a Login
            $this->dieLogin(INTERNAL_ERROR." 1");            
        }

        // Retorna os dados da consulta
        return $query->fetch(PDO::FETCH_ASSOC);        
    }

    private function getLoginSession()
    {
        
        // Verifica se o id do usuario existe na base
        $query = $this->db->query( 
            "SELECT * FROM {$this->table} WHERE id = ? LIMIT 1", 
            array( $_SESSION['userdata']['id'] ) 
        );


        // Verifica a consulta
        if( ! $query ){
            // Mata a Login
            $this->dieLogin(INTERNAL_ERROR." 2");
        }
        // Retorna os dados da base de usuário
        return $query->fetch(PDO::FETCH_ASSOC);
    }

    private function getLoginPOST()
    {
        // Retorna os dados do POST
        return $_POST['userdata'];
        
    }

    private function isUserdata(array $userdata)
    {
        // Verifica se à dados do usuário ou tentativa de login
        if ( ! isset( $userdata ) 
            || ! is_array( $userdata ) 
            || empty($userdata ) 
            ) {
            
            // Mata a Login
            $this->dieLogin(INTERNAL_ERROR." 3");
        }
    }

    private function setLogin(array $userdata)
    {

        // Extrai variáveis dos dados do usuário
        extract( $userdata );

        // Verifica se existe email e senha
        if( ! isset( $email ) || ! isset( $password )){
            // Mata a Login
            $this->dieLogin(INTERNAL_ERROR." 4");
        }

        // Verifica se o usuário existe na base de dados
        $query = $this->db->query(
            "SELECT * FROM {$this->table} WHERE email = ? LIMIT 1",
            array( $email )
        );

        // Verifica a consulta
        if( ! $query ){
            // Mata a Login
            $this->dieLogin(INTERNAL_ERROR." 5");     
        }

        // Obtém os dados da consulta
        $fetch = $query->fetch(PDO::FETCH_ASSOC);

        // Obtém o id do usuário
        $id_user = $fetch['id'];

        //Verifica se existe ID
        if( empty( $id_user )){
            // Mata a Login
            $this->login_is = FALSE;
            $_SESSION['login_error'] = "Usuário e/ou senha errado. Tente novamente.";
            if($fetch['recover_pass'] == 0){
                $_SESSION['login_error'] = "Primeiro acesso à nova plataforma? Solicite uma nova senha";
            }
            return; 
        }

        // Confere se a senha enviada pelo usuário coincide com o hash do BD
        if( ! $this->phpassHash->checkPassword( $password, $fetch['password'] ) ){
      
            $this->login_is = FALSE;
            $_SESSION['login_error'] = "Usuário e/ou senha errado. Tente novamente.";
            if($fetch['recover_pass'] == 0){
                $_SESSION['login_error'] = "Primeiro acesso à nova plataforma? Solicite uma nova senha";
            }
            return;
            // Mata a Login
            //$this->dieLogin("Usuário e/ou senha errado. Tente novamente.");
        }       
        
        // Recria o Id da Sessão
        session_regenerate_id();
        $session_id = session_id();

        // Envia os dados do usuário para a sessão

        $user_session['id']         = $id_user;
        $user_session['session_id'] = $session_id;
        $user_session['permissions'] = unserialize( $fetch['permissions'] );
        $user_session['status']     = $fetch['status'];


        $this->setSession($user_session);

        // Atualiza o id da sessão no BD
        $query = $this->db->query(
            "UPDATE {$this->table} SET session_id = ? WHERE id = ?",
            array( $session_id, $id_user )
        );

        // Se setado "manter conctado"
        if( isset( $remember_me ) ){
            //Cria Cookie
            setcookie('userdata',base64_encode($id).'&'.base64_encode($session_id),time()+60*60*24*265*10,'/');
        }

        // Verifica se existe uma URL para redirecionar
        if( isset( $_SESSION['goto_url'] ) ){
            // Passa a URL para uma variável
            $goto_url = urldecode( $_SESSION['goto_url'] );

            // Remove a sessão com a URL
            unset( $_SESSION['goto_url'] );

            // Redireciona a página           
            header("Location: {$goto_url}"); 
            exit;
        }


    }

    private function setSession(array $userdata)
    {
        // Envia os dados do usuário para a sessão
        $_SESSION['userdata']['id']         = $userdata['id'];
        $_SESSION['userdata']['session_id'] = $userdata['session_id'];
        $_SESSION['userdata']['permissions'] = unserialize( $userdata['permissions'] );
        $_SESSION['userdata']['status']     = $userdata['status'];
        $this->login_is = TRUE;
        unset($_SESSION['login_error']);

    }

    private function dieLogin(string $error)
    {
        $this->login_is = FALSE;
        $_SESSION['login_error'] = $error;
    
        // Desconfigura qualquer sessão que possa existir sobre o usuário
        $this->logout();
        die;         
        
    }

    public function logout(bool $redirect = FALSE)
    {
        $this->login_is = FALSE;
        // Remove dados do usuário da sessão 
        $_SESSION['userdata'] = array();

        // Forçar remoção
        unset( $_SESSION['userdata'] );

        // Apaga Cookie
        setcookie('userdata','',time()-3600,'/');

        // Regenera o id da sessão
        session_regenerate_id();

        // Efetua Redirecionamento se necessário
        if( $redirect === TRUE ){
            // Redireciona para a página de login
            $this->gotoLogin();
        }
    } 

    public function gotoLogin()
    {
       
        $login_uri = '/'.$this->folder.LOGIN_URI;      
       
        // Pega e seta na sessão a página que o usuário estava
        $_SESSION['goto_url'] = urlencode( $_SERVER['REQUEST_URI'] );
        header("Location: {$login_uri}");
    }
    
    // final protected function gotoPage();
    
    // final protected function checkPermission();
    
}