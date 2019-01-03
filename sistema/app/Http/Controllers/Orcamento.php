<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Repositories\Pesquisa as Pes;
use App\Http\Repositories\Orcamento as Orc;
use DB;

class Orcamento{
    private $pesquisa;
    private $orcamento;

    public function __construct(){
        $this->pesquisa = new Pes();
        $this->orcamento = new Orc();
    }

    /**
    * @description: retorna a view orcamentos.index
    * @author: Fernando Bino Machado
    * @param:
    * @return: view
    */

    public function index(){
        $data=[];
        
        //define data para ver validade dos orçamentos
        $validade = date('Y-m-d H:i:s', time());

        //monta os wheres para busca do repositorio
        $wheres=[
            ['cdStatus','=','1']
        ];

        $parametrosPesquisa = [
            'tabela'=>'categoria',
            'wheres' =>$wheres
        ];

        //pega lista de categorias
        $data['categorias'] = $this->pesquisa->listarWhere($parametrosPesquisa);
        
        //pega lista de orçamentos entradas
        $data['orcamentosEntradas'] = DB::table('orcamento as o')
                            ->select(
                                'o.cdOrcamento',
                                'o.cdCategoria',
                                'c.nmCategoria',
                                'o.nmOrcamento',
                                'o.vlOrcamento',
                                'o.dia',
                                'o.validade',
                                'o.tpOrcamento',
                                'o.cdStatus'
                            )
                            ->join('categoria as c','c.cdCategoria','=','o.cdCategoria')
                            ->where('o.validade','>',$validade)
                            ->where('c.cdStatus','=','1')
                            ->where('o.tpOrcamento','=','1')
                            ->orderBy('o.dia')
                            ->get();
        
        //pega lista de orçamentos entradas
        $data['orcamentosSaidas'] = DB::table('orcamento as o')
                            ->select(
                                'o.cdOrcamento',
                                'o.cdCategoria',
                                'c.nmCategoria',
                                'o.nmOrcamento',
                                'o.vlOrcamento',
                                'o.dia',
                                'o.validade',
                                'o.tpOrcamento',
                                'o.cdStatus'
                            )
                            ->join('categoria as c','c.cdCategoria','=','o.cdCategoria')
                            ->where('o.validade','>',$validade)
                            ->where('c.cdStatus','=','1')
                            ->where('o.tpOrcamento','=','2')
                            ->orderBy('o.dia')
                            ->get();

        //monta os dias de referencia
        $data['dias']=[];

        for($i=1;$i<29;$i++){
            $data['dias'][] = $i;
        }
        
        //calcula receita mensal
        $data['receita'] = $this->orcamento->calculaReceita();
        
        //retorna a view
        return view('orcamento.index')->with($data);
    }

    /**
    * @description: salva um orçamento ou altera se já existir
    * @author: Fernando Bino Machado
    * @param: Request $request
    * @return: view
    */

    public function salvar(Request $request){
        //recebe parametros
        $params = (array) $request->all();
        
        //trata validade
        if( !empty($params['validade']) && !is_null($params['validade'])){
            //se a data é maior que hoje
            if( strtotime($params['validade']) > time() ){
                $validade = $params['validade'];
            }else{
                $validade = "2050-12-31 23:59:59";    
            }
        }else{
            $validade = "2050-12-31 23:59:59";
        }

        //salva novo orcamento se ele não existir
        if( $params['codigo'] == "0" ){

            //prepara campos
            $valores = [];
            $valores['tabela'] = 'orcamento';
            $valores['valores']['cdCategoria'] = $params['categoria'];
            $valores['valores']['nmOrcamento'] = $params['descricao'];
            $valores['valores']['vlOrcamento'] = $params['valor'];
            $valores['valores']['dia'] = $params['dia'];
            $valores['valores']['validade'] = $validade;
            $valores['valores']['tpOrcamento'] = $params['tipo'];
            $valores['valores']['cdStatus'] = "1";

            //salva orcamento
            $acao = $this->pesquisa->salvar($valores);

            if($acao > 0){
                session(['message'=>'Item de Orçamento Salvo com Sucesso!!']);
                session(['tipoMessage'=>'1']);
            }else{
                session(['message'=>'Não foi possível salvar o item de orçamento']);
                session(['tipoMessage'=>'2']);
            }

            //redireciona apos ação
            return redirect('/orcamentos');
            
        
        //altera orçamento caso já exista
        }else{
            //prepara campos
            $valores = [];
            $valores['tabela'] = 'orcamento';
            $valores['valores']['cdCategoria'] = $params['categoria'];
            $valores['valores']['nmOrcamento'] = $params['descricao'];
            $valores['valores']['vlOrcamento'] = $params['valor'];
            $valores['valores']['dia'] = $params['dia'];
            $valores['valores']['validade'] = $validade;
            $valores['campo']='cdOrcamento';
            $valores['valor']=$params['codigo'];
            
            //altera orçamento
            $acao = $this->pesquisa->alterarCamposPorValor($valores);

            if($acao > 0){
                session(['message'=>'Item de Orçamento Alterado com Sucesso!!']);
                session(['tipoMessage'=>'1']);
            }else{
                session(['message'=>'Não foi possível alterar o item de orçamento']);
                session(['tipoMessage'=>'2']);
            }

            //redireciona apos ação
            return redirect('/orcamentos');
        }
    }

    /**
    * @description: deleta orçamento do banco
    * @author: Fernando Bino Machado
    * @param: Request $request 
    * @return: json
    */
    public function deletar(Request $request){
        //recebe os parametros
        $params = (array) $request->all();
        
        //verifica se pode excluir o registro
        $verifica=[];
        $verifica['tabela'] = 'conta';
        $verifica['campo'] = 'cdOrcamento';
        $verifica['valor'] = $params['cd'];
        
        $registrosRelacionados = $this->pesquisa->contarRegistros($verifica);
        
        //deleta do banco
        if($registrosRelacionados < 1){
            //sobreescreve a tabela
            $verifica['tabela'] = 'orcamento';
            
            //deleta orçamento
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
    * @description: marca o status de um orçamento
    * @author: Fernando Bino Machado
    * @param: Request $params
    * @return: json
    */

    public function marcar(Request $request){
        //recebe os parametros
        $params = (array) $request->all();
        
        //define o status de acordo com status recebido
        $status = 1;
        $checado = true;

        if($params['status'] == "1"){
            $status = 2;
            $checado = false;
        }

        //altera o status do orçamento
        $acao = DB::table('orcamento')
            ->where('cdOrcamento',$params['cd'])
            ->update(['cdStatus'=>$status]);
        
        //retorna json com resposta
        if( $acao > 0 ){
            return json_encode(
                [
                    'message'=>'Status do Orçamento Alterado com sucesso',
                    'status'=>'1',
                    'check'=>$params['check'],
                    'paramStatus'=>$status,
                    'checado'=>$checado
                ]);
        }else{
            return json_encode(
                [
                    'message'=>'Erro ao tentar alterar status do orçamento',
                    'status'=>'0',
                    'check'=>$params['check'],
                    'paramStatus'=>$status,
                    'checaco'=>$checado
                    ]);
        }
    }

    /**
    * @description: pesquisa detalhes de um orçamento
    * @author: Fernando Bino Machado
    * @param: Request $params
    * @return: json $jsonResposta
    */

    public function detalhes(Request $request){
        //recebe parametros
        $params = (array) $request->all();
        
        //monta parametros para busca
        $wheres = [
            ['cdOrcamento','=',$params['orcamento']]
        ];

        $parametrosPesquisa=[
            'tabela'=>'orcamento',
            'wheres'=>$wheres
        ];

        //busca detalhes
        $dados = $this->pesquisa->listarWhere($parametrosPesquisa);

        //monta resposta
        $resposta['orcamento'] = $dados[0]->nmOrcamento;
        $resposta['valor'] = $dados[0]->vlOrcamento;

        return json_encode($resposta);
    }
}