<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Repositories\Periodo as Per;
use DateTime;
use DateInterval;

class Aplicacao{
    
    private $periodo;

    public function __construct(){
        $this->periodo = new Per();
    }

    /**
     * @description: retorna a view aplicacao.index
     * @author: Fernando Bino Machado
     * @params:
     * @return:
    */

    public function index(){
        return view('aplicacao.index');
    }

    /**
     * @description: calcula o retorno de uma aplicação com base no retorno passado
     * @author: Fernando Bino Machado
     * @params:
     * @return:
    */

    public function calcular(Request $request){

        //formula para calculo
        //  M = P * (1+I) elevado a N

        //recebe os parametros
        $params = (array) $request->all();
        
        //trata as datas recebidas


        //define periodo em anos
        $anos = $this->periodo->difDias($params['dtinicio'], $params['dtfinal']);
        
        //define taxa ao dia
        $txProvento = (float) $params['taxa-ano'];
        
        //potencia proventos
        $potenciaProventos = pow( (1 + ($txProvento / 100) ), $anos);

        //calcula o montante bruto
        $montante = $params['valor'] * $potenciaProventos;
        
        $bruto = $montante - $params['valor'];
        $custodia = ($bruto / 100) * $params['taxa-custodia'];
        $imposto = ($bruto / 100) * $params['imposto'];
        $corretora = ($bruto / 100) * $params['taxa-corretora'];
        
        $bruto = number_format($bruto,2,'.','');
        $custodia = number_format($custodia,2,'.','');
        $imposto = number_format($imposto,2,'.','');
        $corretora = number_format($corretora,2,'.','');
        $montante = number_format( ($montante - ($imposto + $custodia + $corretora))  ,2,'.','');
        $valorInicial = number_format($params['valor'],2,'.','');

        $dados['bruto'] = $montante;
        $dados['custodia'] = $custodia;
        $dados['corretora'] = $corretora;
        $dados['imposto'] = $imposto;
        $dados['liquido'] = $montante;
        $dados['valor'] = $valorInicial;

        return view('aplicacao.index')->with($dados);
    }
    

}