<?php

namespace App\Http\Repositories;

use DB;
use App\Http\Repositories\Pesquisa as Pes;

class Categoria{
    private $pesquisa;

    public function __construct(){
        $this->pesquisa = new Pes();
    }

    /**
     * @description: faz a agregação de valores por categoria, retorna um array com as categorias e as os tres itens de calculo, entradas,saidas,saldo
     * @author: Fernando Bino Machado
     * @params: stdClass
     * @return: array $calculo
    */

    public function calculoCategorias($params){
        //armazena o array de categorias calculadas
        $calculo=[];
        
        foreach( $params as $num => $val){
            //busca valores
            $valores['entradas'] = $this->somaLancamentos('1',$val->cdCategoria);
            $valores['saidas'] = $this->somaLancamentos('2',$val->cdCategoria);
            $valores['saldo'] = $valores['entradas'] - $valores['saidas'];
            
            $params[$num]->entradas = number_format($valores['entradas'],2,'.','');
            $params[$num]->saidas = number_format($valores['saidas'],2,'.','');
            $params[$num]->saldo = number_format($valores['saldo'],2,'.','');
            
        }
        
        return $params;
    }

    /**
     * @description: faz a soma de lancamentos vinculados a uma categoria conforme o tipo recebido
     * @author: Fernando Bino Machado
     * @param: $tipo - tipo de lançamento
     * @param: $cdCategoria - categoria a ser somada
     * @return: float $soma
    */

    public function somaLancamentos($tipo,$cdCategoria){
        //inicia pesquisa
        $return = DB::table('categoria as c')
            ->select('l.vlLancamento')
            ->join('orcamento as o','c.cdCategoria','=','o.cdCategoria')
            ->join('conta as ct', 'o.cdOrcamento','=','ct.cdOrcamento')
            ->join('lancamento as l','ct.cdConta','=','l.cdConta')
            ->where('c.cdCategoria','=',$cdCategoria)
            ->where('l.tpLancamento','=',$tipo)
            ->sum('l.vlLancamento');
        
        return $return;
    }
}