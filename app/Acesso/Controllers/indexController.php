<?php

namespace MaxBusiness\Acesso\Controllers;

use MaxFW\Controllers\MainController;

class IndexController 
{
	public $login_requires = TRUE;
	public $body_class = 'dashboard';
	public $title = NAME;

	public function __construct(MainController $main)
	{
		$this->main = $main;
		
		if ( ! $this->main->login_is ) {
			$this->main->logout(TRUE);
			
			return;
		}
		// echo $main->login_error;
	}

	public function index(){
		$contents['usuario'] = "Usuario";
		$contents['cursos'] = "Cursos";
		
		$content = array(
			'contents' => $contents
		);

		echo $this->main->render(
			'\\index\\index.html', 
			$content
		);
	}

	public function teste(){
		echo "teste";
	}

	public function sair()
	{
		$this->main->logout(TRUE);
	}
}
