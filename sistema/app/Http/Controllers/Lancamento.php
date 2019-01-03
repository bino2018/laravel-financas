<?php

namespace App\Http\Controllers;

use App\Http\Repositories\Pesquisa as Pes;
use App\Http\Repositories\Conta as Con;
use App\Http\Repositories\Lancamento as Lan;
use Illuminate\Http\Request;
use DB;

class Lancamento{

    private $pesquisa;
    private $conta;
    private $lancamento;

    public function __construct(){
        $this->pesquisa = new Pes();
        $this->conta = new Con();
        $this->lancamento = new Lan();
    }

    /**
    * @description: retorna a view lancamento.index
    * @author: Fernando Bino Machado
    * @params: 
    * @return: view
    */

    public function index(){
        
        //busca contas a receber
        $data['contasReceber'] = $this->conta->listarContas(['periodo'=>'300', 'tipo'=>'1', 'data'=>'0']);

        //busca contas a pagar
        $data['contasPagar'] = $this->conta->listarContas(['periodo'=>'300', 'tipo'=>'2', 'data'=>'0']);

        //busca ultimos lançamentos
        $data['lancamentos'] = $this->lancamento->listarLancamentos(['periodo'=>'150', 'tipo'=>'', 'data'=>'0']);
        
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

        //prepara os valores
        $valores['tabela'] = 'lancamento';
        $valores['valores']['cdConta'] = $params['conta'];
        $valores['valores']['nmLancamento'] = $params['descricao'];
        $valores['valores']['vlLancamento'] = $params['valor'];
        $valores['valores']['dtLancamento'] = date('Y-m-d H:i:s', time());
        $valores['valores']['tpLancamento'] = $params['tipo'];

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