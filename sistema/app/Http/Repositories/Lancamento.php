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
    * @return: stdClass $lancamentos stdClass é o resultado da pesquisa por lançamentos no banco
    */

    public function listarLancamentos($params){
        
        //busca os lançamentos
        $lancamentos = DB::table('lancamento')->select();
        
        //define o periodo, se as datas foram enviadas monta o periodo com as datas enviadas, se não monta periodo padrão
        if( isset($params['data']['dtinicio']) && !empty($params['data']['dtinicio']) && isset($params['data']['dtfinal']) && !empty($params['data']['dtfinal']) ){
            $periodo = $this->periodo->preparaDatas($params['data']['dtinicio'], $params['data']['dtfinal']);
        }else{
            $periodo = $this->periodo->definePeriodoUltimosDias($params['periodo']);
        }
        
        //filtra por período
        $lancamentos = $lancamentos->where('dtLancamento','>=',$periodo['inicial']);
        $lancamentos = $lancamentos->where('dtLancamento','<=',$periodo['final']);
        
        //filtra por descrição do lançamento
        if( isset($params['data']['descricao']) && !empty($params['data']['descricao']) ){
            $lancamentos = $lancamentos->where('nmLancamento', 'LIKE', '%'.$params['data']['descricao'].'%');
        }
        
        //filtra por tipo de lançamento
        if( isset($params['data']['tipo']) && !empty($params['data']['tipo']) ){
            $lancamentos = $lancamentos->where('tpLancamento',$params['data']['tipo']);
        }

        //finaliza pesquisa
        $lancamentos = $lancamentos->orderBy('dtLancamento','desc');
        $lancamentos = $lancamentos->paginate(5);
        
        
        //retorna lançamentos
        return $lancamentos;
    }


}