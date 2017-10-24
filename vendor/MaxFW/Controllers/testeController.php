<?php

namespace MaxFW\Controllers;

use Phpass\Hash;

class TesteController
{  

    
   public function callPass(){
   		$senha = "will123";
   		$phpassHash = new hash;
   		$passwordHash = $phpassHash->hashPassword($senha);
   		echo "a senha {$senha} virou {$passwordHash} <br>";
   		$senha_confere = "will123";
		if ($phpassHash->checkPassword($senha_confere, $passwordHash)) {
		    echo "Senha certa";
		} else {
		    echo "Senha errada";
		}
   }

    public function testePass()
    {

    }

}