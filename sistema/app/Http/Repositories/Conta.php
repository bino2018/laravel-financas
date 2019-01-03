<?php

namespace App\Http\Repositories;

use App\Http\Repositories\Pesquisa as Pes;
use App\Http\Repositories\Periodo as Per;
use DB;
use DateTime;
use DateInterval;

class Conta{

    private $pesquisa;
    private $periodo;

    public function __construct(){
        $this->pesquisa = new Pes();
        $this->periodo = new Per();
    }

    /**
    * @description: Retorna listagem de contas a pagar ou a receber
    * @author: Fernando Bino Machado
    * @params: array $parans[periodo, tipo]
    * @return: array $mesesContas
    */

    public function listarContas($params){
        //define o periodo com datas informadas
        if( isset($params['data']) && is_array($params['data']) ){
            $dtInicio = date('Y-m-d H:i:s', time());
            $dtFinal = date('Y-m-d H:i:s', time());
            $datas = ['inicial'=>$dtInicio, 'final'=>$dtFinal];
        
        //define periodo padrão
        }else{
            //trata numero de dias para evitar erros
            $restoDivisao = $params['periodo'] % 2;
            $numDias = $params['periodo'] - $restoDivisao;

            //estabelece período conforme os dias recebidos
            $dias = (int) $numDias / 2;
            $datas = [
                'inicial'=>$this->periodo->definePeriodoUltimosDias($dias),
                'final'=>$this->periodo->definePeriodoProximosDias($dias)
            ];
        }
        
        //monta as condições para pesquisa
        $wheres = [
            ['c.dtVencimento','>=',$datas['inicial']['inicial']],
            ['c.dtVencimento','<=',$datas['final']['final']],
            ['c.tpConta','=',$params['tipo']]
        ];

        //inicia pesquisa pelas contas no banco
        $result = DB::table('conta as c')
            ->select(
                'c.cdConta',
                'c.cdOrcamento',
                'c.dsConta',
                'c.vlConta',
                'c.dtVencimento',
                'c.tpConta',
                'l.vlLancamento',
                'l.dtLancamento'
            )
            ->leftJoin('lancamento as l','l.cdConta','=','c.cdConta');
        
        //aplica os wheres
        foreach($wheres as $num => $val){
            $result = $result->where($val[0],$val[1],$val[2]);
        }

        //ordena pesquisa por cdConta
        $result = $result->orderBy('c.dtVencimento');

        //conclui pesquisa
        $result = $result->get();
        
        //organiza as contas em meses
        $mesesContas = $this->organizaContasEmMeses($result);
        
        return $mesesContas;
    }

    /**
    * @description: Organiza as contas em meses
    * @author: Fernando Bino Machado
    * @params: stdClass $result
    * @return: array $meses
    */

    public function organizaContasEmMeses($result){
        //define os meses
        $meses = [
            '','JANEIRO','FEVEREIRO','MARÇO','ABRIL','MAIO','JUNHO',
            'JULHO','AGOSTO','SETEMBRO','OUTUBRO','NOVEMBRO','DEZEMBRO'
        ];

        //agrega os valores
        $arrMeses=[];

        //faz validações
        foreach( $result as $num => $val ){
            
            //verifica qual mes corresponde o registro atual
            $formatoMes = date('m', strtotime($val->dtVencimento));
            $numMes = $formatoMes;
            
            //retira o zero da frente se o numMes for menor que 10
            if( (int) $numMes < 10){
                $numMes = substr($numMes, 1,1);
            }
            
            //define o mes
            $mes = $meses[$numMes];

            $detalhesConta = (array) $val;

            //verifica se existe valor lançado para a conta atual
            if( isset($detalhesConta['vlLancamento']) && !empty($detalhesConta['vlLancamento']) ){
                //define contador
                if( isset($arrMeses[$mes]['ok'])){
                    $arrMeses[$mes]['ok'] += 1;
                }else{
                    $arrMeses[$mes]['ok'] = 1;
                }

                //define status de recebido
                $recebidoEm = 'Recebido em: '.date('d-m-Y', strtotime($detalhesConta['dtLancamento']));
                
                //define status de pago
                $pagoEm = 'Pago em: '.date('d-m-Y', strtotime($detalhesConta['dtLancamento']));
                
                //verifica tipo de conta e aplica status
                $statusConta = ($detalhesConta['tpConta'] == '1') ? $recebidoEm : $pagoEm;
                
            }else{
                //define contador
                if( isset($arrMeses[$mes]['pendente'])){
                    $arrMeses[$mes]['pendente'] += 1;
                }else{
                    $arrMeses[$mes]['pendente'] = 1;
                }

                //verifica tipo de conta e aplica status
                $statusConta = ($detalhesConta['tpConta'] == '1') ? 'A Receber' : 'A Pagar';
            }

            //define informações para montar tabela no frontend
            $detalhesConta['valor'] = number_format($detalhesConta['vlConta'], 2, '.', '');
            $detalhesConta['vencimento'] = date('d-m-Y', strtotime($detalhesConta['dtVencimento']));
            $detalhesConta['situacao'] = $statusConta;

            //define contador total
            if( isset($arrMeses[$mes]['total'])){
                $arrMeses[$mes]['total'] += 1;
            }else{
                $arrMeses[$mes]['total'] = 1;
            }

            //define ano conforme data de vencimento
            $arrMeses[$mes]['ano'] = date('Y', strtotime($detalhesConta['dtVencimento']));


            $arrMeses[$mes][] = $detalhesConta;

        }

        
        return $arrMeses;
    }

    /**
    * @description: Gera contas a pagar e receber com base nos orçamentos ativos e validos
    * @author: Fernando Bino Machado
    * @params: 
    * @return: 
    */

    public function gerarContas(){
        //instancia data atual
        $objDataAtual = new DateTime();

        //define o mes atual
        $mesAtual = $objDataAtual->format('m');

        //defina o ano atual
        $anoAtual = $objDataAtual->format('Y');

        //define data atual para buscar orçamentos
        $dataAtual = $objDataAtual->format('Y-m-d H:i:s');

        //faz a busca
        $orcamentos = DB::table('orcamento')
                        ->select()
                        ->where('cdStatus','=','1')
                        ->where('validade','>',$dataAtual)
                        ->orderBy('dia')
                        ->get();
        
        $novasContas = 0;

        //verifica em cada orçamento se é permitido o cadastro
        foreach( $orcamentos as $num => $val ){
            
            //busca a ultima conta cadastrada relacionada ao orçamento atual
            $contas = DB::table('conta')->select()->where('cdOrcamento',$val->cdOrcamento)->orderBy('dtVencimento','desc')->limit('1')->get();
            
            //se existirem contas relacionadas valida se pode cadastrar
            if( count($contas) ){
                
                $diaOrcamento = $val->dia;

                if( (int) $val->dia < 10 ){
                    $diaOrcamento = "0{$val->dia}";
                }

                $dataConta = "{$anoAtual}-{$mesAtual}-{$diaOrcamento} 00:00:00";
                
                if($dataConta != $contas[0]->dtVencimento){
                    
                    $valores['tabela'] = 'conta';
                    $valores['valores']['cdOrcamento'] = $val->cdOrcamento;
                    $valores['valores']['dsConta'] = $val->nmOrcamento;
                    $valores['valores']['vlConta'] = $val->vlOrcamento;
                    $valores['valores']['dtVencimento'] = $dataConta;
                    $valores['valores']['tpConta'] = $val->tpOrcamento;
                    
                    $novaConta = $this->pesquisa->salvar($valores);

                    if($novaConta > 0){
                        $novasContas += 1;
                    }
                }


            //cadastra pela primeira vez se não existir nenhuma ocorrencia
            }else{
                $diaOrcamento = $val->dia;

                if( (int) $val->dia < 10 ){
                    $diaOrcamento = "0{$val->dia}";
                }

                $valores['tabela'] = 'conta';
                $valores['valores']['cdOrcamento'] = $val->cdOrcamento;
                $valores['valores']['dsConta'] = $val->nmOrcamento;
                $valores['valores']['vlConta'] = $val->vlOrcamento;
                $valores['valores']['dtVencimento'] = "{$anoAtual}-{$mesAtual}-{$diaOrcamento} 00:00:00";
                $valores['valores']['tpConta'] = $val->tpOrcamento;
                
                $novaConta = $this->pesquisa->salvar($valores);

                if($novaConta > 0){
                    $novasContas += 1;
                }
            }
        }
        
        session(['message'=>'Processo de Geração de Contas Concluído: '.$novasContas.' conta(s) gerada(s) !!']);
        session(['tipoMessage'=>'1']);

        return true;
        
    }


}