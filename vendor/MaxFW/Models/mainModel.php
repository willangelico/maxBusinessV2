<?php

namespace MaxFW\Models;

class MainModel
{
	public function __construct( $db = false, $controller = null)
	{
		$this->db = $db;	
	}

	public function getIdByUrl(string $table, array $url)
	{		

		$query = $this->db->query(
			"SELECT * FROM {$table} WHERE alias = ? LIMIT 1",
			$url
		);
		return $query->fetch()['id'];
	}
	
}