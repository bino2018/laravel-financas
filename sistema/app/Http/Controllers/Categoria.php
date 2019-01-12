<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Repositories\Pesquisa as Pes;
use App\Http\Repositories\Categoria as Cat;
use DB;

class Categoria{
    
    private $pesquisa;
    private $categoria;

    public function __construct(){
        $this->pesquisa = new Pes();
        $this->categoria = new Cat();
    }

    /**
    * @description: retorna a view categorias.index
    * @author: Fernando Bino Machado
    * @param:
    * @return: view
    */
    public function index(){
        $listaCategorias = $this->pesquisa->listarTodosRegistros(['tabela'=>'categoria']);
        $calculoCategorias = $this->categoria->calculoCategorias($listaCategorias);
        
        $data['data']['categorias'] = $calculoCategorias;
        
        return view('categoria.index')->with($data);
        
    }

    /**
    * @description: insere categoria no banco ou atualiza se ja existir
    * @author: Fernando Bino Machado
    * @param: Request $request 
    * @return: view
    */
    public function salvar(Request $request){
        //recebe os parametros 
        $params = (array) $request->all();
        
        //salva nova categoria se ela não existir
        if( $params['codigo'] == "0"){
            $valores=[];
            $valores['tabela']='categoria';
            $valores['valores']['nmCategoria']=$params['categoria'];
            $valores['valores']['cdStatus']='1';
            
            //salva categoria
            $acao=$this->pesquisa->salvar($valores);
            
            if($acao > 0){
                session(['message'=>'Categoria Salva Com Sucesso!!']);
                session(['tipoMessage'=>'1']);
            }else{
                session(['message'=>'Não foi possível salvar a Categoria']);
                session(['tipoMessage'=>'2']);
            }
            
            //redireciona apos acao
            return redirect('/categorias');
            
        
        //altera categoria existente
        }else{
            //prepara parametros para alteração
            $valores=[];
            $valores['tabela']='categoria';
            $valores['campo']='cdCategoria';
            $valores['valor']=$params['codigo'];
            $valores['campoUpdate']='nmCategoria';
            $valores['valorUpdate']=$params['categoria'];

            //altera a categoria
            $acao = $this->pesquisa->alterarPorValor($valores);

            if($acao > 0){
                session(['message'=>'Categoria Alterada Com Sucesso!!']);
                session(['tipoMessage'=>'1']);
            }else{
                session(['message'=>'Não foi possível alterar a Categoria']);
                session(['tipoMessage'=>'2']);
            }

            //redireciona apos acao
            return redirect('/categorias');
        }

    }

    /**
    * @description: deleta categoria do banco
    * @author: Fernando Bino Machado
    * @param: Request $request 
    * @return: json
    */
    public function deletar(Request $request){
        //recebe os parametros
        $params = (array) $request->all();
        
        //verifica se pode excluir o registro
        $verifica=[];
        $verifica['tabela'] = 'orcamento';
        $verifica['campo'] = 'cdCategoria';
        $verifica['valor'] = $params['cd'];
        
        $registrosRelacionados = $this->pesquisa->contarRegistros($verifica);
        
        //deleta do banco
        if($registrosRelacionados < 1){
            //sobreescreve a tabela
            $verifica['tabela'] = 'categoria';
            
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
    * @description: marca o status de uma categoria
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

        //altera o status da categoria
        $acao = DB::table('categoria')
            ->where('cdCategoria',$params['cd'])
            ->update(['cdStatus'=>$status]);
        
        //retorna json com resposta
        if( $acao > 0 ){
            return json_encode(
                [
                    'message'=>'Status da Categoria Alterado com sucesso',
                    'status'=>'1',
                    'check'=>$params['check'],
                    'paramStatus'=>$status,
                    'checado'=>$checado
                ]);
        }else{
            return json_encode(
                    [
                    'message'=>'Erro ao tentar alterar status da categoria',
                    'status'=>'0',
                    'check'=>$params['check'],
                    'paramStatus'=>$status,
                    'checaco'=>$checado
                    ]
                );
        }
    }
}