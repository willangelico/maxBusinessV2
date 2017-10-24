<?php 

namespace MaxFW\Config;

class Define
{
        
    public function __construct()
    {        
        $this->default();
        $this->vars();
        $this->security();
        $this->debug();
    }
    
    private function default(){
        // Timezone
        date_default_timezone_set('America/Sao_Paulo');
    }

    private function vars(){
        // Nome do Projeto Cliente
        define( 'NAME', 'Max Business');
        // Caminho para a raiz
        define( 'ABSPATH', dirname( __FILE__ ) );
        // Caminho para a aplicação Back End
        define( 'APPLICATION', '/app');
        // Caminho para a pasta de uploads
        define( 'UP_ABSPATH', ABSPATH . '/public/files' );
        // URL da home
        define( 'HOME_URI', '/' );         
        // URL do Domínio Principal
        define( 'URL', 'https://www.maxbusiness.com.br' );
        // Nome do host da base de dados
        define( 'HOSTNAME', 'localhost' );
        // Nome do DB
        define( 'DB_NAME','maxbusiness');
        // Usuário do DB
        define( 'DB_USER', 'root' );
        // Senha do DB        
        define( 'DB_PASSWORD', 'root' );
        // Charset da conexão
        define( 'DB_CHARSET', 'utf8' );

        //define host de email
        define( 'MAIL_HOST', 'smtp.umbler.com' );
        //define porta de envio de email
        define( 'MAIL_PORT', 587);
        //define username do email
        define( 'MAIL_USERNAME', 'contato@maxwill.com.br');
        //define username do email
        define( 'MAIL_PASSWORD', 'Ch@polin348593');
        //define TLS encryption, `ssl` also accepted do email
        define( 'SMTPSecure', 'tls');
        //define recipient do email
        define( 'MAIL_FROM', 'contato@maxwill.com.br');
        //define recipient do email
        define( 'MAIL_REPLY', 'contato@maxwill.com.br');
        
        // Se você estiver desenvolvendo, modifique o valor para true
        define( 'DEBUG', true );
        // Pastas extras da app
        define( 'FOLDERS', 'admin|acesso');
        // URL de Login admin
        define( 'LOGIN_URI', '/login' );
        // Mensagem de Erro Interno
        define( 'INTERNAL_ERROR', "Erro interno. Tente novamente ou contacte o administrador do sistema.");
    }

    private function security(){
        // Evita que usuários acesse este arquivo diretamente
        if ( ! defined('ABSPATH')) die;
        // Inicia a sessão
        session_start();
    }

    private function debug(){
        // Verifica o modo para debugar
        if ( ! defined('DEBUG') || DEBUG === false ) {
            // Esconde todos os erros
            error_reporting(0);
            ini_set("display_errors", 0); 
            exit;            
        } 
        // Mostra todos os erros
        error_reporting(E_ALL);
        ini_set("display_errors", 1); 
    }
}