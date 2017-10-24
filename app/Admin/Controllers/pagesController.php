<?php

namespace MaxBusiness\Admin\Controllers;

use MaxFW\Controllers\MainController;

class PagesController 
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
		$this->modelPages = $this->main->loadModel('Pages');			
		$pages = $this->modelPages->list();

		$content = array( 
			"user" => 'user',
			"pages" => $pages);

		echo $this->main->render(
			'\\pages\\list.html', 
			$content
		);
	}	

	public function add(){
		if($_POST){
			$this->model = $this->main->loadModel('Pages');
			$add_message = $this->model->add($_POST);

			$ret = array(
				'message' => $add_message
			);

			//Exibe o form
			echo $this->main->render(
				'\\pages\\add.html', 
				$ret
			);
			return;
		}
		$content = array( "user" => 'user');
		echo $this->main->render(
			'\\pages\\add.html', 
			$content
		);
	}	

	public function edit(){
		$content = array( "user" => 'user');
		echo $this->main->render(
			'\\pages\\edit.html', 
			$content
		);
	}	

	public function delete(){
		$params = $this->main->getParams();

		$this->model = $this->main->loadModel('Pages');
		
		if( ! isset($params[2]) || $params[2] != 'confirm'){
			$ret = array(
				'message' => "Deseja mesmo excluir? <a href='/admin/pages/delete/{$params[1]}/confirm'>Sim</a>"
			);			
			echo $this->main->render(
				'\\pages\\delete.html', 
				$ret
			);
			return;
		}
		$del_message = "Apagado";
		//$del_message = $this->model->del($params);

		$ret = array(
			'message' => $del_message
		);

		
		//Exibe o form
		echo $this->main->render(
			'\\pages\\list.html', 
			$ret
		);
		return;

		$content = array( "user" => 'user');
		echo $this->main->render(
			'\\pages\\delete.html', 
			$content
		);
	}	

	public function status()
	{
		// Pega os Parametros
		$params = $this->main->getParams();
		// Seta o Id
		$id = $params[2];
		
		// Chama o Model
		$this->modelPages = $this->main->loadModel('Pages');			
		// Altera

		$change = $this->modelPages->changeStatus($id);
		if( ! $change ){
			echo json_encode(
				array(
					"ret" => FALSE, 
					"msg" => "Erro ao alterar status. Contacte o Administrador do sistema"
				)
			);
			return;
		}
		echo json_encode(
			array(
				"ret" => TRUE
			)
		);
		return;

	}

	public function sair()
	{
		$this->main->logout(TRUE);
	}
}
