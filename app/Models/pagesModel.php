<?php

namespace MaxBusiness\Models;

class PagesModel
{
	public function __construct( $db = false, $controller = null)
	{
		$this->db = $db;	
	}

	public function get(int $id)
	{
		$query = $this->db->query(
			"SELECT * FROM pages WHERE id = ? LIMIT 1",
			array($id)
		);
		return $query->fetch();
	}

	public function list()
	{
		$query = $this->db->query(
			"SELECT * FROM pages ORDER BY ordenation ASC"
		);
		$pages = $query->fetchAll();
		foreach ($pages as $key => $value) {
			if($value['status'] == 0){
				$pages[$key]['status'] = 'off';
			}else{
				$pages[$key]['status'] = 'on';
			}			
		}
		return $pages;
	}

	public function add(array $post)
	{
		$dados['name'] 			= $post['page_title'];
		$dados['description'] 	= $post['page_description'];
		$dados['content'] 		= $post['page_content'];
		$dados['status'] 		= 1;

		// Insere os dados na base de dados
		$query = $this->db->insert( 'pages', $dados );
	
		// if( ! $query ){
		// 	return "Erro ao cadastrar";
		// }
		return "Página cadastrada com sucesso";
	}
	
	public function changeStatus($id)
	{

		$query = $this->db->query(
			"SELECT * FROM pages WHERE id = ? LIMIT 1",
			array($id)
		);
		$page = $query->fetch();	
		

		if( $page['status'] == 0){
			$data['status'] = 1;
		}else{
			$data['status'] = 0;
		}

		//$page['status'] = $new_status;
		
		$query = $this->db->update(
			'pages', 
			'id', 
			$page['id'], 
			$data
		);
	
		return $query;
	}
}