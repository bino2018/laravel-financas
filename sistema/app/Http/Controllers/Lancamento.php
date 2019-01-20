<?php

namespace App\Http\Controllers;

use App\Http\Repositories\Pesquisa as Pes;
use App\Http\Repositories\Conta as Con;
use App\Http\Repositories\Lancamento as Lan;
use App\Http\Repositories\Saldo as Sal;
use Illuminate\Http\Request;
use DB;

class Lancamento{

    private $pesquisa;
    private $conta;
    private $lancamento;
    private $saldo;

    public function __construct(){
        $this->pesquisa = new Pes();
        $this->conta = new Con();
        $this->lancamento = new Lan();
        $this->saldo = new Sal();
    }

    /**
    * @description: retorna a view lancamento.index
    * @author: Fernando Bino Machado
    * @params: 
    * @return: view
    */

    public function index(Request  $request){
        //recebe os parametros se existirem
        $params = $request->all();
        
        //busca contas a receber
        $data['contasReceber'] = $this->conta->listarContas(['periodo'=>'300', 'tipo'=>'1', 'data'=>'0']);

        //busca contas a pagar
        $data['contasPagar'] = $this->conta->listarContas(['periodo'=>'300', 'tipo'=>'2', 'data'=>'0']);

        //busca ultimos lançamentos
        $data['lancamentos'] = $this->lancamento->listarLancamentos(['periodo'=>'150', 'data'=>$params]);

        //define o valor de soma para coluna vlLancamento
        $data['soma'] = $this->lancamento->somarValores(['periodo'=>'150', 'data'=>$params]);
        
        //verifica se existem lançamentos e exibe mensagem se necessário
        if( !count($data['lancamentos']) ){
            session(['message'=>'Nenhum Lançamento encontrado...']);
            session(['tipoMessage'=>'3']);
        }

        //mantem os paramtros da busca
        $data['parametros'] = json_encode($params);

        return view('lancamento.index')->with($data);
        
    }

    /**
    * @description: salva um lançamento
    * @author: Fernando Bino Machado
    * @params: Request $request
    * @return: redirect
    */

    public function salvar(Request $request){
        
        //recebe parametros
        $params = (array) $request->all();

        //verifica se o parametro valor é um numero
        if( !isset($params['valor']) || empty($params['valor']) || !is_numeric($params['valor']) ){
            session(['message'=>'O valor do lançamento deve ser um número...']);
            session(['tipoMessage'=>'2']);
            
            return back();
        }

        //busca dados auxiliares ligados a conta

        //busca valor orçamento
        $valorOrcamento = DB::table('conta')
        ->select('vlConta')
        ->where('cdConta','=',$params['conta'])
        ->sum('vlConta');

        //busca valor total lançado para essa conta
        $totalLancado = DB::table('lancamento')
                ->select('vlLancamento')
                ->where('cdConta','=',$params['conta'])
                ->sum('vlLancamento');

        //verifica se o tipo de ocorrencia da conta é único, caso sim retorna erro
        if( $params['conta'] != '0' && $this->lancamento->tipoOcorrencia($params['conta']) == "1" && (float) $totalLancado > 0 ){
            session(['message'=>'Conta não pode ser lançada novamente!!']);
            session(['tipoMessage'=>'2']);

            return back();
        }

        //verifica se o tipo de lançamento é uma saída e se o saldo é suficiente
        if( $params['tipo'] == 2){
            //busca o extrato omitindo parametos para poder pegar o saldo atual geral
            $extrato = $this->saldo->dadosParaExtrato([]);

            //calcula extrato
            $valorSaldo = $this->saldo->calculaExtrato($extrato,1);

            //verifica se saldo disponível é >= ao valor enviado
            $saldoOk = ( (float) $valorSaldo >= (float) $params['valor'] ) ? true : false;
            
            if( !$saldoOk ){
                session(['message'=>'Saldo insuficiente, o saldo atual é de: R$ '.$valorSaldo]);
                session(['tipoMessage'=>'2']);
                
                return back();
            }
        }

        //prepara os valores
        $valores['tabela'] = 'lancamento';
        $valores['valores']['cdConta'] = $params['conta'];
        $valores['valores']['nmLancamento'] = $params['descricao'];
        $valores['valores']['vlLancamento'] = $params['valor'];
        $valores['valores']['dtLancamento'] = date('Y-m-d H:i:s', time());
        $valores['valores']['tpLancamento'] = $params['tipo'];
        $valores['valores']['cdPlanejado'] = '1';
        
        //verifica limites do orçamento
        if( $params['conta'] != '0' ){
            //verifica se o valor de orcamento foi ultrapassado, caso sim seta $valores['valores']['cdPlanejado'] como 2 de não planejado
            if( (float) $valorOrcamento >= ( (float) $totalLancado ) + (float) $params['valor'] ){
                $valores['valores']['cdPlanejado'] = '1';
            }else{
                $valores['valores']['cdPlanejado'] = '2';
            }
        }else{
            $valores['valores']['cdPlanejado'] = '2';
        }

        //salva o lançamento
        $acao = $this->pesquisa->salvar($valores);

        if($acao > 0){
            session(['message'=>'Lançamento Efetuado com Sucesso!!']);
            session(['tipoMessage'=>'1']);
        }else{
            session(['message'=>'Não foi possível efetuar o lançamento...']);
            session(['tipoMessage'=>'2']);
        }

        //retorna apos acao
        return redirect('/lancamentos');
    }

    /**
    * @description: deleta um lançamento
    * @author: Fernando Bino Machado
    * @params: Request $request
    * @return: redirect
    */

    public function deletar(Request $request){
        //recebe os parametros
        $params = (array) $request->all();
        
        //prepara campos para exclusão
        $verifica['tabela'] = 'lancamento';
        $verifica['campo'] = 'cdLancamento';
        $verifica['valor'] = $params['cd'];

        //deleta o lancamento
        $registroDeletado = $this->pesquisa->deletarPorValor($verifica);
        
        //retorna json com resposta
        if($registroDeletado > 0){
            return json_encode(['message'=>'Registro excluído com sucesso', 'status'=>'1']);
        }else{
            return json_encode(['message'=>'Erro ao tentar excluir o registro...', 'status'=>'0']);
        }

    }
}