<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class Permissao{

    /**
    * @description: gera as permissões para o usuario e armazena em sessão
    * @author: Fernando Bino Machado
    * @param: Array $params[usuario,senha,permissao]
    * @return: boolean
    */
    public function gerarPermissoes(Request $request, $params){
    
        //prepara array para permissoes
        $permissoes=[];
        $permissoes['usuario'] = $params['usuario'];
        $permissoes['senha'] = $params['senha'];
        $permissoes['permissao'] = $params['permissao'];
        
        //cria a sessão de autenticação
        if( !$request->session()->has('autenticado') ){
            $request->session()->put('autenticado',$permissoes);
        }else{
            $request->session()->forget('autenticado');
            $request->session()->put('autenticado',$permissoes);
        }
        
        return true;
    }

    /**
    * @description: destroi a autenticação armazenada em sessão
    * @author: Fernando Bino Machado
    * @param:
    * @return:
    */
    public function destroiPermissoes(){
        //destroi autenticação atual caso ela exista
        if( session()->has('autenticado') ){
            session()->forget('autenticado');
        }
    }
}