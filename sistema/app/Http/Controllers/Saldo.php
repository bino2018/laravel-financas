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
        
        //define dados para calcular extrato
        $extrato = $this->saldo->dadosParaExtrato($params);

        //calcula extrato
        $data = $this->saldo->calculaExtrato($extrato);
        
        return view('saldo.index')->with($data);
    }



}