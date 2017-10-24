<?php

namespace MaxBusiness\Acesso\Controllers;

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
		$this->content['login_error'] = $_SESSION['login_error'] ? $_SESSION['login_error'] : '';
		$this->params = $this->main->getParams();
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
		if($_POST){
			$this->model = $this->main->loadModel('Acesso');
			$rec_pass = $this->model->recoverPass($_POST['login']);

			$ret = array(
				'message' => $rec_pass
			);
			//Exibe o form
			echo $this->main->twig->render('\\login\\redefine.html', $ret);
			return;
		}
		$ret = array(
			'message' => 'Solicite a nova senha.'
		);
		//Exibe o form
		echo $this->main->twig->render('\\login\\redefine.html', $ret);	
	}

	public function redefined()
	{
		$id = $this->params[1];
		$token = $this->params[2];
		$this->model = $this->main->loadModel('Acesso');
		print_r($params);
	}
}
