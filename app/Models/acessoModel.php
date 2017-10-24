<?php

namespace MaxBusiness\Models;

use Phpass\Hash;
use MaxFW\Controllers\emailController;

class AcessoModel
{
	public function __construct( $db = false, $controller = null)
	{
		$this->db = $db;	
	}

	public function recoverPass(string $email)
	{
		// Busca o email do usuario. 
		$query = $this->db->query(
			'SELECT * FROM acesso WHERE email = ? LIMIT 1',
			array($email)
		);
		$fetch = $query->fetch();
		// Se usuário não existe
		if(count($fetch) < 1){
			return "Login '{$email}' não existe. Verifique novamente ou entre em contato com o administrador do sistema";
		}
		// Gera nova senha
		$new_pass = rand(000000, 999999);
		$this->phpassHash = new hash;
		$data['token_code'] = $this->phpassHash->hashPassword($new_pass);
		$data['token_date'] = date("Y-m-d H:i:s");

		//Atualiza o Banco de dados
		$query = $this->db->update('acesso', 'id', $fetch['id'], $data);

		// Se erro no banco
		if( !$query){
			return INTERNAL_ERROR. " Tente novamente";
		}
		
		// Dados a serem enviados no e-mail
		$data['email']['to']['email'] = $fetch['email_contact'];
		$data['email']['to']['name'] = $fetch['nome'];

		$data['email']['content']['subject'] = "Nova senha de acesso - ".NAME;
		$data['email']['content']['body'] = "<p>Você solicitou uma nova senha de acesso no site ".NAME.".</p> <p>Sua nova senha é <strong>{$new_pass}</strong></p>. <p><a href='www.projuriscursos.com.br/acesso/redefined/{$fetch['id']}/{$data['token_code']}'>Acesse o site novamente!</a></p>" ;
		$data['email']['content']['alt'] = "Você solicitou uma nova senha de acesso no site ".NAME.". Sua nova senha é {$new_pass}. Acesse www.projuriscursos.com.br/acesso/redefined/{$fetch['id']}/{$data['token_code']}";

		//Envia E-mail
		$this->email = new emailController;		
		$send = $this->email->sendEmail($data['email']['to'], $data['email']['content']);
		//Se e-mail não enviado
		if(! $send){
			return INTERNAL_ERROR." Não conseguimos enviar a senha para seu e-mail. Tente novamente ou entre em contato com o administrador do sistema.";
		}
		// Se enviado retorna
		return "Nova Senha enviada no seu e-mail '{$fetch['email_contact']}'. Verifique e acesse novamente.";

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