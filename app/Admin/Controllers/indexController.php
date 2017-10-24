<?php

namespace MaxBusiness\Admin\Controllers;

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
		$content = array( "user" => 'user');
		echo $this->main->render(
			'\\index\\index.html', 
			$content
		);
	}	

	public function sair()
	{
		$this->main->logout(TRUE);
	}
}
