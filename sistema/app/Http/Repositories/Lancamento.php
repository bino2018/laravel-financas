<?php

namespace App\Http\Repositories;

use DB;
use App\Http\Repositories\Periodo as Per;

class Lancamento{
    private $periodo;

    public function __construct(){
        $this->periodo = new Per();
    }

    /**
    * @description: retorna os ultimos lançamentos em um período
    * @author: Fernando Bino Machado
    * @params: array $params[periodo,tipo,data]
    * @return: stdClass $lancamentos
    */

    public function listarLancamentos($params){
        //definir o período para pesquisa
        $periodo = $this->periodo->definePeriodoUltimosDias($params['periodo']);
        
        //busca os lançamentos
        $lancamentos = DB::table('lancamento')->select();

        //verifica se foi enviado algum tipo para pesquisa dos lançamentos
        if( isset($params['tipo']) && !empty($params['tipo']) ){
            $lancamentos = $lancamentos->where('tpLancamento',$params['tipo']);
        }

        //finaliza pesquisa
        $lancamentos = $lancamentos->orderBy('dtLancamento','desc')->get();

        //retorna lançamentos
        return $lancamentos;
    }


}