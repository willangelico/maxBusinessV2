<?php

namespace MaxFW\Functions;

class Helpers
{
	public function generatePass(){

	}

	public function teste(string $teste) :string
	{
		return $teste;
	}

	public function checkArray ( $array, $key ) {
		// Verifica se a chave existe no array
		if ( isset( $array[ $key ] ) && ! empty( $array[ $key ] ) ) {
			// Retorna o valor da chave
			return $array[ $key ];
		}		
		// Retorna nulo por padrão
		return null;
	} // checkArray
}