<?php

namespace App\Http\Controllers;

use App\Http\Repositories\Pesquisa as Pes;
use App\Http\Repositories\Conta as Con;
use App\Http\Repositories\Saldo as Sal;
use Illuminate\Http\Request;
use DB;

class Conta{

    private $pesquisa;
    private $conta;
    private $saldo;

    public function __construct(){
        
        $this->pesquisa = new Pes();
        $this->conta = new Con();
        $this->saldo = new Sal();
    }

    /**
    * @description: retorna a view contas.index
    * @author: Fernando Bino Machado
    * @params:
    * @return: view
    */

    public function index($params=array()){
        
        //busca contas a receber
        $data['contasReceber'] = $this->conta->listarContas(['periodo'=>'120', 'tipo'=>'1', 'data'=>'0']);

        //busca contas a pagar
        $data['contasPagar'] = $this->conta->listarContas(['periodo'=>'120', 'tipo'=>'2', 'data'=>'0']);

        //define dados para calcular extrato
        $extrato = $this->saldo->dadosParaExtrato(['dias'=>'120']);

        //calcula o extrato
        $data['resumo']['saldo'] = $this->saldo->calculaExtrato($extrato);
        
        //calcula contas pendentes
        $data['resumo']['pendentes'] = $this->saldo->calculaContasPendentes();
        
        return view('conta.index')->with($data);
        
    }

    /**
    * @description: retorna a view contas.index
    * @author: Fernando Bino Machado
    * @params: Request $request
    * @return: view ou redirect
    */
    
    public function salvar(Request $request){
        //recebe os parametros
        $params = (array) $request->all();
        
        //prepara os valores
        $valores['tabela'] = 'conta';
        $valores['valores']['cdOrcamento'] = $params['orcamento'];
        $valores['valores']['dsConta'] = $params['descricao'];
        $valores['valores']['vlConta'] = $params['valor'];
        $valores['valores']['dtVencimento'] = date('Y-m-d H:i:s', strtotime($params['vencimento']));
        $valores['valores']['tpConta'] = $params['tipo'];

        //salva nova conta se ela não existir
        if( $params['codigo'] == '0' ){
            //insere conta no banco
            $acao = $this->pesquisa->salvar($valores);

            if($acao > 0){
                session(['message'=>'Conta Salva com sucesso!!']);
                session(['tipoMessage'=>'1']);
            }else{
                session(['message'=>'Não foi possível salvar a conta...']);
                session(['tipoMessage'=>'2']);
            }

            //redireciona apos ação
            return redirect('/contas');

        //altera conta já existente
        }else{
            //adiciona parametros de alteração
            $valores['campo'] = 'cdConta';
            $valores['valor'] = $params['codigo'];

            //altera conta
            $acao = $this->pesquisa->alterarCamposPorValor($valores);

            if($acao > 0){
                session(['message'=>'Conta Alterada com sucesso!!']);
                session(['tipoMessage'=>'1']);
            }else{
                session(['message'=>'Não foi possível alterar a conta...']);
                session(['tipoMessage'=>'2']);
            }

            //redireciona apos ação
            return redirect('/contas');
        }
    }

    /**
    * @description: deleta uma conta
    * @author: Fernando Bino Machado
    * @params: Request $request
    * @return: redirect
    */

    public function deletar(Request $request){
        //recebe os parametros
        $params = (array) $request->all();
        
        //verifica se pode excluir o registro
        $verifica=[];
        $verifica['tabela'] = 'lancamento';
        $verifica['campo'] = 'cdConta';
        $verifica['valor'] = $params['cd'];
        
        $registrosRelacionados = $this->pesquisa->contarRegistros($verifica);
        
        //deleta do banco
        if($registrosRelacionados < 1){
            //sobreescreve a tabela
            $verifica['tabela'] = 'conta';
            
            //deleta a categoria
            $registroDeletado = $this->pesquisa->deletarPorValor($verifica);

            //retorna json com resposta
            if($registroDeletado > 0){
                return json_encode(['message'=>'Registro excluído com sucesso', 'status'=>'1']);
            }else{
                return json_encode(['message'=>'Erro ao tentar excluir o registro...', 'status'=>'0']);
            }

        }else{
            //retorna json com resposta
            return json_encode(['message'=>'Erro ao tentar excluir o registro...', 'status'=>'0']);
        }
        
    }

    /**
    * @description: gera as contas de maneira automática
    * @author: Fernando Bino Machado
    * @params: Request $request
    * @return: 
    */

    public function gerarContas(Request $request){
        $params = (array) $request->all();

        if($params['_token']){
            $this->conta->gerarContas();
        }

        return redirect('/contas');
    }

}