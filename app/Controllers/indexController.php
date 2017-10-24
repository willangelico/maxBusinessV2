<?php

namespace MaxBusiness\Controllers;

use MaxFW\Controllers\MainController;
use MaxFW\Controllers\SeoController;

class IndexController extends SeoController
{
	public function __construct(MainController $main)
	{
		$this->main = $main;
	}

	public function index()
	{
		$this->modelCursos = $this->main->loadModel('Cursos');
		$cursos = $this->modelCursos->list();
		$contents['cursos'] = $cursos;
		$content = array(
			'contents' => $contents
		);

		echo $this->main->render(
			'\\pages\\index.html', 
			$content
		);
	}
}