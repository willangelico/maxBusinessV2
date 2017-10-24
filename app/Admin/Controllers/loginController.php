<?php

namespace MaxBusiness\Admin\Controllers;

use MaxFW\Controllers\MainController;

class LoginController 
{
	public $login_requires = TRUE;
	public $body_class = 'login-forms';
	public $title = NAME;
	public $content = array();

	public function __construct(MainController $main )
	{
		$this->main = $main;
		$this->content['login_error'] = $_SESSION['login_error'];
		$this->content['login_error'] = $_SESSION['login_error'] ? $_SESSION['login_error'] : '';
		 //echo $main->login_error;
	}

	public function index()
	{
		
		echo $this->main->twig->render('\\login\\index.html', $this->content);		
	}

	public function forget()
	{
		echo $this->main->twig->render('\\login\\forget.html', array('name' => 'formulário de esqueceu a senha'));
		
	}

	public function redefine()
	{
		//Verifica se há post
		//Exibe o form
		echo $this->main->twig->render('\\login\\redefine.html', array('name' => 'formulário de redefinição de senha'));		
	}
}
