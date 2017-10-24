<?php

namespace MaxFW\Models;

use PDO;

class DB
{  
	// Propriedades do Banco de Dados
	public $host, 	// Host da base de dados 
	       $db_name, 		// Nome do banco de dados
	       $password, 	// Senha do usuário da base de dados
	       $user,		// Usuário da base de dados
	       $charset,	// Charset da base de dados
	       $pdo,        // Nossa conexão com o BD
	       $error,      // Configura o erro
	       $debug, 		// Mostra todos os erros 
	       $last_id;    // Último ID inserido 

	public function __construct(	
		$host     = NULL,
		$db_name  = NULL,
		$password = NULL,
		$user     = NULL,
		$charset  = NULL,
		$debug    = NULL
	) {

		// Configura as propriedades novamente.
		// Pega os dados Default se não passado nada no método
		$this->host     = is_null($host) 	? HOSTNAME    : $host;
		$this->db_name  = is_null($db_name) ? DB_NAME     : $db_name;
		$this->password = is_null($password)? DB_PASSWORD : $password;
		$this->user     = is_null($user) 	? DB_USER     : $user;
		$this->charset  = is_null($charset) ? DB_CHARSET  : $charset;
		$this->debug    = is_null($debug) 	? DEBUG       : $debug;
	
		// Conecta
		$this->connect();
	}

	final protected function connect()
	{
		// Configurações do PDO
		$pdo_config  = "mysql:host={$this->host};";
		$pdo_config .= "dbname={$this->db_name};";
		$pdo_config .= "charset={$this->charset};";

		$pdo_options = array(
			PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES {$this->charset}"
		);

		// Tenta Conectar
		try{
			$this->pdo = new PDO(
				$pdo_config, 
				$this->user, 
				$this->password, 
				$pdo_options
			);

			// Verifica se devemos debugar
			if ( $this->debug === TRUE ) {
			
				// Configura o PDO ERROR MODE
				$this->pdo->setAttribute( 
					PDO::ATTR_ERRMODE, 
					PDO::ERRMODE_WARNING 
				);			
			}

			// Reseta Propriedades
			unset( $this->host,
				$this->db_name,
				$this->password,
				$this->user,
				$this->charset
			);
		} catch ( PDOException $e ){
			// Verifica se devemos debugar
			if ( $this->debug === TRUE ) {
			
				// Mostra a mensagem de erro
				echo "Erro: " . $e->getMessage();				
			}
			die();	
		}
	}

	public function query( string $stmt, array $data = null)
	{
		// Prepara a query e executa
		$query = $this->pdo->prepare( $stmt );

		$exec = $query->execute( $data );

		// Verifica a consulta
		if ( ! $exec ){
			$error = $query->errorInfo();
			$this->error = $error[2];
			return FALSE;
		}

		return $query;
	}

	public function insert( string $table )
	{
		// Prepara o array da coluna
		$cols = array();

		// Configura o valor inicial do model
		$place_holders = '(';

		// Prepara o array de valores
		$values = array();

		// Contador que assegura  que as colunas serão configuradas apenas uma vez
		$j = 1;

		// Obtém os argumentos enviados
		$data = func_get_args();

		// Confere se existe ao menos um array de chaves e valores
		if ( ! isset( $data[1] ) || ! is_array( $data[1] ) ) {
			return;
		}

		// Faz um laço nos argumentos
		for ( $i = 1; $i < count( $data ); $i++ ) {
		
			// Obtém as chaves como colunas e valores como valores
			foreach ( $data[$i] as $col => $val ) {
			
				// A primeira volta do laço configura as colunas
				if ( $i === 1 ) {
					$cols[] = "`$col`";
				}
				
				if ( $j <> $i ) {
					// Configura os divisores
					$place_holders .= '), (';
				}
				
				// Configura os place holders do PDO
				$place_holders .= '?, ';
				
				// Configura os valores que vamos enviar
				$values[] = $val;

				$j = $i;
			}
			
			// Remove os caracteres extra dos place holders
			$place_holders = substr( $place_holders, 0, strlen( $place_holders ) - 2 );
		}
		
		// Separa as colunas por vírgula
		$cols = implode(', ', $cols);
		
		// Cria a declaração para enviar ao PDO
		$stmt = "INSERT INTO `$table` ( $cols ) VALUES $place_holders) ";
		
		// Insere os valores
		$insert = $this->query( $stmt, $values );

		// Verifica se a consulta foi realizada
		if( ! $insert ){
			return;
		}

		// Verifica se temos o último ID enviado
		if ( method_exists( $this->pdo, 'lastInsertId' ) 
			&& $this->pdo->lastInsertId() 
		) {
			// Configura o último ID
			$this->last_id = $this->pdo->lastInsertId();
		}		
	}

	public function update( 
		$table, 
		$where_field, 
		$where_field_value, 
		$values ) 
	{
		// Você tem que enviar todos os parâmetros
		if ( empty($table) || empty($where_field) || empty($where_field_value)  ) {
			return;
		}
		
		// Começa a declaração
		$stmt = " UPDATE `$table` SET ";
		
		// Configura o array de valores
		$set = array();
		
		// Configura a declaração do WHERE campo=valor
		$where = " WHERE `$where_field` = ? ";
		
		// Você precisa enviar um array com valores
		if ( ! is_array( $values ) ) {
			return;
		}
		
		// Configura as colunas a atualizar
		foreach ( $values as $column => $value ) {
			$set[] = " `$column` = ?";
		}
		
		// Separa as colunas por vírgula
		$set = implode(', ', $set);
		
		// Concatena a declaração
		$stmt .= $set . $where;
		
		// Configura o valor do campo que vamos buscar
		$values[] = $where_field_value;
		
		// Garante apenas números nas chaves do array
		$values = array_values($values);
				
		// Atualiza
		$update = $this->query( $stmt, $values );
		
		// Verifica se a consulta está OK
		if ( $update ) {
			// Retorna a consulta
			return $update;
		}
		return;
	}

	public function delete( 
		$table, 
		$where_field, 
		$where_field_value ) 
	{
		// Você precisa enviar todos os parâmetros
		if ( empty($table) || empty($where_field) || empty($where_field_value)  ) {
			return;
		}
		
		// Inicia a declaração
		$stmt = " DELETE FROM `$table` ";

		// Configura a declaração WHERE campo=valor
		$where = " WHERE `$where_field` = ? ";
		
		// Concatena tudo
		$stmt .= $where;
		
		// O valor que vamos buscar para apagar
		$values = array( $where_field_value );

		// Apaga
		$delete = $this->query( $stmt, $values );
		
		// Verifica se a consulta está OK
		if ( $delete ) {
			// Retorna a consulta
			return $delete;
		}
		return;
	} // delete

}
