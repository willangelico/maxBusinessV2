<?php

namespace MaxBusiness\Controllers;

use MaxFW\Controllers\MainController;
use MaxFW\Controllers\SeoController;

class PagesController extends SeoController
{

	public function __construct(MainController $main)
	{
		$this->main = $main;
		$params = $this->main->getParams();
		$this->table = "pages";
		$this->model = $this->main->loadModel('Pages');
		//$this->model = new CursosModel;
	}

	public function index()
	{
		// Listas todos os cursos	
		$pages = $this->model->list();		
		echo $this->main->twig->render('\\pages\\index.html', array('pages' => $pages));
	}

	public function get($id)
	{
		// Pega curso pelo ID
		$page = $this->model->get($id);
		echo $this->main->twig->render('\\pages\\page.html', $page);	
		
		
	}

	
}