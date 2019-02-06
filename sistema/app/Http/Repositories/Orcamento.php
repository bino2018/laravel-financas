<?php

namespace App\Http\Repositories;
use DB;

class Orcamento{
    
    /**
    * @description: calcula a receita mensal com base nas categorias, e itens de orçamento ativos
    * @author: Fernando Bino Machado
    * @param: 
    * @return: array $receita
    */

    public function calculaReceita(){
        //soma entradas
        $entradas = DB::table('orcamento as o')
                            ->select(
                                'o.vlOrcamento'
                            )
                            ->join('categoria as c','c.cdCategoria','=','o.cdCategoria')
                            ->where('c.cdStatus','=','1')
                            ->where('o.tpOrcamento','=','1')
                            ->where('o.cdStatus','=','1')
                            ->orderBy('o.dia')
                            ->sum('vlOrcamento');
        
        //soma saidas
        $saidas = DB::table('orcamento as o')
                            ->select(
                                'o.vlOrcamento'
                            )
                            ->join('categoria as c','c.cdCategoria','=','o.cdCategoria')
                            ->where('c.cdStatus','=','1')
                            ->where('o.tpOrcamento','=','2')
                            ->where('o.cdStatus','=','1')
                            ->orderBy('o.dia')
                            ->sum('vlOrcamento');

        //calcula saldo
        $saldo = (float) $entradas - (float) $saidas;
        $saldo = number_format($saldo,2,".","");

        //dados da receita para retorno
        $receita=[];
        $receita['entradas'] = $entradas;
        $receita['saidas'] = $saidas;
        $receita['saldo'] = $saldo;

        return $receita;
    }

    
}