<?php

namespace MaxFW\Controllers;

class SeoController
{  

    public function friendlyUrl()
    {    
        $params = $this->main->getParams();    
   
        // Acha o id buscando na tabela $this->table do banco e retorna o id
        $model = new \MaxFW\Models\MainModel($this->main->db,$this);
        $id = $model->getIdByUrl($this->table,array($params[0]));

        if( ! $id ){
        
            return FALSE;
        }
        $this->get($id);
        return TRUE;
    }

}