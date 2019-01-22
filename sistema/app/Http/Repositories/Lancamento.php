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
    * @params: array $params[periodo,descricao,tipo,data]
    * @return: stdClass $lancamentos stdClass é o resultado da pesquisa por lançamentos no banco
    */

    public function listarLancamentos($params){
        
        //busca os lançamentos
        $lancamentos = DB::table('lancamento')->select();
        
        //define o periodo, se as datas foram enviadas monta o periodo com as datas enviadas, se não monta periodo padrão
        if( isset($params['data']['dtinicio']) && !empty($params['data']['dtinicio']) && $params['data']['dtinicio'] != "null" && isset($params['data']['dtfinal']) && !empty($params['data']['dtfinal']) && $params['data']['dtfinal'] != "null" ){
            $periodo = $this->periodo->preparaDatas( [ $params['data']['dtinicio'], $params['data']['dtfinal'] ] );
        }else{
            $periodo = $this->periodo->montaPeriodo( ['qtdeUnidade'=>$params['periodo'],'formato'=>'D'] );
        }
        
        //filtra por período
        $lancamentos = $lancamentos->where('dtLancamento','>=',$periodo['inicial']);
        $lancamentos = $lancamentos->where('dtLancamento','<=',$periodo['final']);
        
        //filtra por descrição do lançamento
        if( isset($params['data']['descricao']) && !empty($params['data']['descricao']) && $params['data']['descricao'] != "null" ){
            $lancamentos = $lancamentos->where('nmLancamento', 'LIKE', '%'.$params['data']['descricao'].'%');
        }
        
        //filtra por tipo de lançamento
        if( isset($params['data']['tipo']) && !empty($params['data']['tipo']) && $params['data']['tipo'] != "null" ){
            $lancamentos = $lancamentos->where('tpLancamento',$params['data']['tipo']);
        }

        //ordena pesquisa
        $lancamentos = $lancamentos->orderBy('dtLancamento','desc');
        
        //faz a paginação
        if( isset($params['data']['page']) && !empty($params['data']['page'])){
            $lancamentos = $lancamentos->paginate(5,['*'],'page',$params['data']['page']);
        }else{
            $lancamentos = $lancamentos->paginate(5);
        }
        
        //retorna lançamentos
        return $lancamentos;
    }

    /**
     * @description: busca os valores apenas para coluna valor, faz a iteração para efetuar a soma e retorna o resultado
     * @author: Fernando Bino Machado
     * @params: array $params[periodo,descricao,tipo,data]
     * @return: float $soma
    */

    public function somarValores($params){
        //busca os lancamentos
        $lancamentos = DB::table('lancamento')->select('vlLancamento','tpLancamento');

        //define o periodo, se as datas foram enviadas monta o periodo com as datas enviadas, se não monta periodo padrão
        if( isset($params['data']['dtinicio']) && !empty($params['data']['dtinicio']) && $params['data']['dtinicio'] != "null" && isset($params['data']['dtfinal']) && !empty($params['data']['dtfinal']) && $params['data']['dtfinal'] != "null" ){
            $periodo = $this->periodo->preparaDatas( [ $params['data']['dtinicio'], $params['data']['dtfinal'] ] );
        }else{
            $periodo = $this->periodo->montaPeriodo( ['qtdeUnidade'=>$params['periodo'],'formato'=>'D'] );
        }
        
        //filtra por período
        $lancamentos = $lancamentos->where('dtLancamento','>=',$periodo['inicial']);
        $lancamentos = $lancamentos->where('dtLancamento','<=',$periodo['final']);
        
        //filtra por descrição do lançamento
        if( isset($params['data']['descricao']) && !empty($params['data']['descricao']) && $params['data']['descricao'] != "null" ){
            $lancamentos = $lancamentos->where('nmLancamento', 'LIKE', '%'.$params['data']['descricao'].'%');
        }
        
        //filtra por tipo de lançamento
        if( isset($params['data']['tipo']) && !empty($params['data']['tipo']) && $params['data']['tipo'] != "null" ){
            $lancamentos = $lancamentos->where('tpLancamento',$params['data']['tipo']);
        }

        //ordena pesquisa e finaliza a pesquisa
        $lancamentos = $lancamentos->orderBy('dtLancamento','desc')->get();

        //calcula a coluna valor
        $soma = 0;

        if( count($lancamentos) ){
            $arrDados=[];
            foreach($lancamentos as $lanc => $detalhes){
                $valorAtual = 0;
                
                if( $detalhes->tpLancamento == 1 ){
                    $valorAtual = $detalhes->vlLancamento;
                }else{
                    $valorAtual = '-'.$detalhes->vlLancamento;
                }

                $arrDados[] = (float) $valorAtual;
            }
            
            $soma = array_sum($arrDados);
        }
        
        return number_format($soma,2,'.','');

    }

    /**
     * @description: verifica tipo de ocorrencia de uma determinada conta
     * @author: Fernando Bino Machado
     * @param: int $cdConta, o cdConta a ser utilizado para fazer um join na tabela orcamento
     * @return: $tipoOcorrencia, o cdOcorrencia relacionado ao orçamento e conta correspondente
    */

    public function tipoOcorrencia($cdConta){
        if( $cdConta != '0' ){
            $dadosConta = DB::table('conta as c')
                        ->select('o.cdOcorrencia')
                        ->join('orcamento as o','c.cdOrcamento','=','o.cdOrcamento')
                        ->where('c.cdConta','=',$cdConta)
                        ->get();
            if( count($dadosConta) ){
                $tipoOcorrencia = $dadosConta[0]->cdOcorrencia;
            }else{
                $tipoOcorrencia = '1';
            }
        }else{
            $tipoOcorrencia = '1';
        }
        
        return $tipoOcorrencia;
    }

    


}