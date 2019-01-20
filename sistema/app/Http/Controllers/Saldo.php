<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Repositories\Saldo as Sal;

class Saldo{
    
    private $saldo;

    public function __construct(){
        $this->saldo = new Sal();
    }

    /**
    * @description: retorna a view saldo.index
    * @author: Fernando Bino Machado
    * @params: Request $request
    * @return: view
    */

    public function index(Request $request){
        //recebe params
        $params = (array) $request->all();
     
        //define lista com paginação
        $lancamentos = $this->saldo->dadosParaExtrato($params, true);

        //define dados para calcular extrato
        $extrato = $this->saldo->dadosParaExtrato($params);

        //calcula extrato
        $data['extrato'] = $this->saldo->calculaExtrato($extrato);
        $data['lancamentos'] = $lancamentos;
        $data['totalPage'] = $lancamentos->lastPage();
        $data['pageAtual'] = $lancamentos->currentPage();
        $data['dias'] = isset($params['dias']) ? $params['dias'] : '';

        
        return view('saldo.index')->with($data);
    }

    /**
    * @description: faz a paginação dos lançamentos
    * @author: Fernando Bino Machado
    * @params: Request $request
    * @return: view
    */

    public function paginarLancamentos(Request $request){
        $params = (array) $request->all();
        $data['lancamentos'] = $this->saldo->dadosParaExtrato($params, true);
        
        $html = view('saldo.lancamentos')->with($data)->render();
        
        $response = [
            'html'=>$html,'page'=>$data['lancamentos']->currentPage()
        ];
        
        return $response;
    }

}