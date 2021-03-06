<?php

namespace MaxBusiness\Admin\Controllers;

use MaxFW\Controllers\MainController;

class MateriaisController 
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
			'\\materiais\\list.html', 
			$content
		);
	}	

	public function add(){
		$content = array( "user" => 'user');
		echo $this->main->render(
			'\\materiais\\add.html', 
			$content
		);
	}	

	public function edit(){
		$content = array( "user" => 'user');
		echo $this->main->render(
			'\\materiais\\edit.html', 
			$content
		);
	}	

	public function delete(){
		$content = array( "user" => 'user');
		echo $this->main->render(
			'\\materiais\\delete.html', 
			$content
		);
	}	

	public function sair()
	{
		$this->main->logout(TRUE);
	}
}
