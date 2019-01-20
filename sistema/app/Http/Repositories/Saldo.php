<?php

namespace App\Http\Repositories;

use DB;
use App\Http\Repositories\Periodo as Per;

class Saldo{
    private $periodo;

    public function __construct(){
        $this->periodo = new Per();
    }

    /**
    * @description: retorna lista para calcular extrato
    * @author: Fernando Bino Machado
    * @params: array $params
    * @return: array $return
    */

    public function dadosParaExtrato($params=[], $paginate=false){
        //inicia query
        $extrato = DB::table('lancamento as l')
        ->select(
            'l.cdLancamento',
            'l.cdConta',
            'l.nmLancamento',
            'l.vlLancamento',
            'l.dtLancamento',
            'l.tpLancamento',
            'c.vlConta'
        )
        ->leftJoin('conta as c','c.cdConta','=','l.cdConta');

        //configura query com parametros
        if( isset($params['dias']) && !empty($params['dias']) && !is_null($params['dias']) ){
            //definir periodo
            $periodo = $this->periodo->montaPeriodo( ['qtdeUnidade'=>$params['dias'],'formato'=>'D']);

            //aplica periodo na query
            $extrato = $extrato->where('l.dtLancamento','>=',$periodo['inicial']);
            $extrato = $extrato->where('l.dtLancamento','<=',$periodo['final']);
        }
        
        //finaliza query
        if( $paginate ){
            //faz a paginação caso tenha $paginate sera true
            if( isset($params['page']) ){
                $return = $extrato->paginate(15,['*'],'page',$params['page']);
            }else{
                $return = $extrato->paginate(15);
            }
        }else{
            $return = $extrato->get();
        }
        // dd($return);
        return $return;
    }


    /**
    * @description: retorna o extrato calculado
    * @author: Fernando Bino Machado
    * @param: stdClass $dados onde stdClass é o resultado da pesquisa por lançamentos no banco
    * @param: $tipoRetorno - quando omitido assume null, caso seja null o retorno será o array $extrato, caso seja != de null o retorno sera um float do saldo
    * @return: array $extrato ou float $saldo dependendo do valor da variavel $tipoRetorno
    */

    public function calculaExtrato($dados, $tipoRetorno=null){
        $extrato=[];

        $recebidos=0;
        $gastos = 0;
        $saldo = 0;

        foreach($dados as $num => $val){
            //agrega array
            $arrLin = (array) $val;
            $extrato['extrato'][]=$arrLin;
            
            //faz a soma para montar saldo
            if( $val->tpLancamento == '1' ){
                $recebidos += (float) $val->vlLancamento;
            }else{
                $gastos += (float) $val->vlLancamento;
            }

        }

        $saldo = $recebidos - $gastos;

        $extrato['detalhes'] = ['recebido'=>number_format($recebidos,2,'.',''), 'gasto'=>number_format($gastos,2,'.',''), 'saldo'=>number_format($saldo,2,'.',''),];

        if( is_null($tipoRetorno) ){
            return $extrato;
        }else{
            return $saldo;
        }
    }

    /**
    * @description: calcula contas pendentes(tanto recebimentos como pagamentos)
    * @author: Fernando Bino Machado
    * @params: stdClass $dados onde stdClass é o resultado da pesquisa por lançamentos no banco
    * @return: array $pendentes
    */

    public function calculaContasPendentes(){
        //buscar as contas relacionando por leftJoin entre tabelas conta e lancamento
        $dadosContas = DB::table('conta as c')
                        ->select(
                            'c.cdConta',
                            'c.vlConta',
                            'c.tpConta',
                            'l.vlLancamento'
                        )
                        ->leftJoin('lancamento as l','c.cdConta','=','l.cdConta')
                        ->get();

        $aReceber=0;
        $aPagar = 0;
        $previsao = 0;
        
        //faz o calculo com base nos dados recebidos
        foreach($dadosContas as $num => $val){
            //agrega ao calculo apenas contas não liquidadas
            if(is_null($val->vlLancamento)){
                if($val->tpConta == '1'){
                    $aReceber += (float) $val->vlConta;
                }else{
                    $aPagar += (float) $val->vlConta;
                }
            }
            
        }

        $previsao = $aReceber - $aPagar;

        $pendentes['pendentes'] = [ 'a_receber'=>number_format($aReceber,2,'.',''), 'a_pagar'=>number_format($aPagar,2,'.',''), 'previsao'=>number_format($previsao,2,'.','') ];

        return $pendentes;
    }

}