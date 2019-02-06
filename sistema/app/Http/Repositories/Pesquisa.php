<?php

namespace App\Http\Repositories;

use DB;

class Pesquisa{

    /**
    * @description: conta os registros de uma tabela com base em um campo e valor recebidos
    * @author: Fernando Bino Machado
    * @param: array $params[tabela,campo,valor]
    * @return: int $registros
    */

    public function contarRegistros($params){
        $registros = DB::table($params['tabela'])
            ->select($params['campo'])
            ->where($params['campo'],$params['valor'])
            ->count();
        
        return $registros;
    }

    /**
    * @description: salva um registro em uma determinada tabela
    * @author: Fernando Bino Machado
    * @param: array $params[tabela,[valores=>campo]]
    * @return: int $registros
    */

    public function salvar($params){
        $registros = DB::table($params['tabela'])
            ->insert($params['valores']);
        
        return $registros;
    }

    /**
    * @description: altera vários campos de um registro de uma tabela pelo seu pelo campo e valor recebidos
    * @author: Fernando Bino Machado
    * @param: array $params[tabela,campo,valor,valores]
    * @return: int $registros
    */

    public function alterarCamposPorValor($params){
        $registros = DB::table($params['tabela'])
            ->where($params['campo'], $params['valor'])
            ->update($params['valores']);
        
        return $registros;
    }

    /**
    * @description: altera um registro de uma tabela pelo seu pelo campo e valor recebidos
    * @author: Fernando Bino Machado
    * @param: array $params[tabela,campo,valor,campoUpdate,valorUpdate]
    * @return: int $registros
    */

    public function alterarPorValor($params){
        $registros = DB::table($params['tabela'])
            ->where($params['campo'], $params['valor'])
            ->update([
                $params['campoUpdate'] => $params['valorUpdate']
            ]);
        
        return $registros;
    }

    /**
    * @description: deleta um registro de uma tabela pelo seu pelo campo e valor recebidos
    * @author: Fernando Bino Machado
    * @param: array $params[tabela,campo,valor]
    * @return: int $registros
    */

    public function deletarPorValor($params){
        $registros = DB::table($params['tabela'])
            ->where($params['campo'],$params['valor'])
            ->delete();
        
        return $registros;
    }

    /**
    * @description: retorna todos os registros de uma tabela, feito dessa maneira devido o tamanho do projeto, não recomendado trazer os registros dessa forma
    * @author: Fernando Bino Machado
    * @param: array $params[tabela,campos]
    * @return: array $registros
    */

    public function listarTodosRegistros($params){
        //inicia busca
        $registros = DB::table($params['tabela']);
        
        //verifica se não foram passados campos para o select
        if( isset($params['campos']) && !empty($params['campos']) && is_string($params['campos']) ){
            $registros = $registros->select($campos);
        }else{
            $registros = $registros->select();
        }

        //finaliza a busca
        $registros = $registros->get();
        
        return $registros;
    }

    /**
    * @description: retorna os registros de uma tabela where um ou mais campos forem iguais aos respectivos valores
    * @author: Fernando Bino Machado
    * @param: array $params[tabela=>tabela, wheres=>wheres]
    * @return: array $registros
    */

    public function listarWhere($params){
        
        //inicia busca
        $registros = DB::table($params['tabela'])
            ->select();
        
        //percorre todos os wheres recebidos
        foreach($params['wheres'] as $num => $val){
            if(count($val) == 3){
                $registros = $registros->where($val[0],$val[1],$val[2]);
            }
        }

        //conclui a busca
        $registros = $registros->get();

        return $registros;

    }

}