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
    * @descript: retorna lista para calcular extrato
    * @author: Fernando Bino Machado
    * @params: array $params
    * @return: array $extrato
    */

    public function dadosParaExtrato($params){
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
        if( isset($params) && count($params) ){
            //definir periodo
            $periodo = $this->periodo->definePeriodoUltimosDias($params['dias']);

            //aplica periodo na query
            $extrato = $extrato->where('l.dtLancamento','>=',$periodo['inicial']);
            $extrato = $extrato->where('l.dtLancamento','<=',$periodo['final']);
        }

        //finaliza query
        $extrato = $extrato->get();
        
        return $extrato;
    }


    /**
    * @descript: retorna o extrato calculado
    * @author: Fernando Bino Machado
    * @params: stdClass $dados
    * @return: array $extrato
    */

    public function calculaExtrato($dados){
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

        return $extrato;
    }

    /**
    * @descript: calcula contas pendentes(tanto recebimentos como pagamentos)
    * @author: Fernando Bino Machado
    * @params: stdClass $dados
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