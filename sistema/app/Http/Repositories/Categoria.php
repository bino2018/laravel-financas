<?php

namespace App\Http\Repositories;

use DB;
use App\Http\Repositories\Pesquisa as Pes;
use App\Http\Repositories\Saldo as Sal;

class Categoria{
    private $pesquisa;
    private $saldo;

    public function __construct(){
        $this->pesquisa = new Pes();
        $this->saldo = new Sal();
    }

    /**
     * @description: faz a agregação de valores por categoria, retorna um array com as categorias e as os tres itens de calculo, entradas,saidas,saldo
     * @author: Fernando Bino Machado
     * @params: stdClass onde stdClass é o resultado da pesquisa de categorias no banco
     * @return: array $calculo
    */

    public function calculoCategorias($params){
        //armazena o array de categorias calculadas
        $dadosCalculo=[];
        
        //acumuladores de Saldo
        $recebidoGeral=0;
        $gastoGeral=0;
        $saldoGeral=0;

        foreach( $params as $num => $val){
            //cria array para categoria atual
            $arrCategoria = (array) $val;

            //busca valores
            $valores['entradas'] = $this->somaLancamentos('1',$arrCategoria['cdCategoria']);
            $valores['saidas'] = $this->somaLancamentos('2',$arrCategoria['cdCategoria']);
            $valores['saldo'] = $valores['entradas'] - $valores['saidas'];
            
            //adiciona a categoria atual os valores de entradas,saidas e saldo
            $arrCategoria['entradas'] = number_format($valores['entradas'],2,'.','');
            $arrCategoria['saidas'] = number_format($valores['saidas'],2,'.','');
            $arrCategoria['saldo'] = number_format($valores['saldo'],2,'.','');

            //armazena arrCategoria no array final dadosCalculo
            $dadosCalculo[] = $arrCategoria;

            //agrega os valores para calculo
            $recebidoGeral += (float) $valores['entradas'];
            $gastoGeral += (float) $valores['saidas'];
            
        }

        $saldoGeral = $recebidoGeral - $gastoGeral;

        //define dados para calcular saldo geral
        $extrato = $this->saldo->dadosParaExtrato([]);
        
        //calcula o saldo geral
        $dadosSaldo = $this->saldo->calculaExtrato($extrato);

        //define comparativo de gastos não vinculados a categoria
        $comparaEntradas = $dadosSaldo['detalhes']['recebido'] - $recebidoGeral;
        $comparaSaidas = $dadosSaldo['detalhes']['gasto'] - $gastoGeral;
        $comparaSaldo = $dadosSaldo['detalhes']['saldo'] - $saldoGeral; 

        $dadosCalculo[] = [
            'cdCategoria'=>'0',
            'nmCategoria'=>'INDEFINIDO',
            'cdStatus'=>'1',
            'entradas' => number_format($comparaEntradas,2,'.',''),
            'saidas' => number_format($comparaSaidas,2,'.',''),
            'saldo' => number_format($comparaSaldo,2,'.','')
        ];

        //define comparativo de categorias planejadas
        $dadosCalculo[] = [
            'cdCategoria'=>'0',
            'nmCategoria'=>'PLANEJADO',
            'cdStatus'=>'1',
            'entradas' => number_format($recebidoGeral,2,'.',''),
            'saidas' => number_format($gastoGeral,2,'.',''),
            'saldo' => number_format($saldoGeral,2,'.','')
        ];

        //define valores gerais
        $dadosCalculo[] = [
            'cdCategoria'=>'0',
            'nmCategoria'=>'TOTAL',
            'cdStatus'=>'1',
            'entradas' => $dadosSaldo['detalhes']['recebido'],
            'saidas' => $dadosSaldo['detalhes']['gasto'],
            'saldo' => $dadosSaldo['detalhes']['saldo']
        ];
        
        return $dadosCalculo;
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