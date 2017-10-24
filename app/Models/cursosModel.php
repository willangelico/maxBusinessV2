<?php

namespace MaxBusiness\Models;

class CursosModel
{
	public function __construct( $db = false, $controller = null)
	{
		$this->db = $db;	
	}

	public function get(int $id)
	{
		$query = $this->db->query(
			"SELECT * FROM cursos WHERE id = ? LIMIT 1",
			array($id)
		);
		return $query->fetch();
	}

	public function list()
	{
		$query = $this->db->query(
			"SELECT * FROM cursos WHERE status = ? ORDER BY ordenation ASC",
			array( 1 )
		);
		return $query->fetchAll();
	}
	
}