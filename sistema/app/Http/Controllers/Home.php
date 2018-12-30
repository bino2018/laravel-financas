<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Permissao as Perm;
use Illuminate\Http\Request;
use DB;

class Home{
    private $permissao;

    public function __construct(){
        $this->permissao = new Perm();
    }

    /**
    * @description: retorna a pagina inicial
    * @author: Fernando Bino Machado
    * @param:
    * @return: view
    */
    public function home(){
        //destroi as permissoes de sessao atual
        $this->permissao->destroiPermissoes();


        return view('base.base');
    }

    /**
    * @description: retorna a view login
    * @author: Fernando Bino Machado
    * @param:
    * @return: view
    */
    public function viewLogin(){
        //destroi as permissoes de sessao atual
        $this->permissao->destroiPermissoes();

        return view('login.login');
    }

    /**
    * @description: verifica se dados enviados pelo usuário no form login são válidos
    * @author: Fernando Bino Machado
    * @param: Request $request
    * @return: view, redirect
    */
    public function validaLogin(Request $request){
        //recebe parametros
        $params = (array) $request->all();
        
        //verifica se os dados recebidos correspondem a um usuário cadastrado
        $dados = DB::table('usuario')
            ->select()
            ->where('nmUsuario', $params['usuario'])
            ->where('dsSenha', sha1($params['senha']))
            ->get();
        
        //concede permissao ou redireciona usuário para tela de login
        if( count($dados) ){
            $criaPermissao = $this->permissao->gerarPermissoes(
                $request,
                [
                    'usuario'=>$dados[0]->nmUsuario,
                    'senha'=>$dados[0]->dsSenha,
                    'permissao'=>$dados[0]->cdPermissao
                ]
            );
            
            if($criaPermissao){
                return redirect('/sistema');
            }else{
                return redirect('/login');
            }
        }else{
            return redirect('/login');
        }

    }

    /**
    * @description: redireciona para a rota sistema
    * @author: Fernando Bino Machado
    * @param: 
    * @return: view
    */

    public function sistema(){
        return view('sistema.sistema');
    }
    
}