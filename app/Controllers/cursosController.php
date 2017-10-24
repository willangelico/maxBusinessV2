<?php

namespace MaxBusiness\Controllers;

use MaxFW\Controllers\MainController;
use MaxFW\Controllers\SeoController;
//use MaxBusiness\Models\CursosModel;

class CursosController extends SeoController
{

	public function __construct(MainController $main)
	{
		$this->main = $main;
		$params = $this->main->getParams();
		$this->table = "cursos";
		$this->model = $this->main->loadModel('Cursos');
		//$this->model = new CursosModel;
	}

	public function index()
	{
		// Listas todos os cursos	
		$cursos = $this->model->list();		
		echo $this->main->twig->render('\\cursos\\index.html', array('cursos' => $cursos));
	}

	public function get($id)
	{
		// Pega curso pelo ID
		$curso = $this->model->get($id);
		echo $this->main->twig->render('\\cursos\\curso.html', $curso);	
		
		
	}

	
}