<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Repositories\Saldo as Sal;
use App\Http\Repositories\Lancamento as Lan;
use DB;
use PDF;

class Relatorio{
    private $saldo;
    private $lancamento;

    public function __construct(){
        $this->saldo = new Sal();
        $this->lancamento = new Lan();
    }

    /**
     * @description: retorna a view index de relatórios
     * @author: Fernando Bino Machado
     * @param: Request $request
     * @return: view
    */

    public function index(Request $request){
        $params = $request->all();
        
        //busca ultimos lançamentos
        $data['lancamentos'] = $this->lancamento->listarLancamentos(['periodo'=>'150', 'data'=>$params], true);
        
        //mantem os paramtros da busca
        $data['parametros'] = json_encode($params);
        
        return view('relatorio.index')->with($data);
        
    }

    /**
     * @description: retorna download do pdf da view index
     * @author: Fernando Bino Machado
     * @param: 
     * @return: 
    */

    public function indexPdf(Request $request){
        $params = $request->all();
        
        //busca ultimos lançamentos
        $data['lancamentos'] = $this->lancamento->listarLancamentos(['periodo'=>'150', 'data'=>$params], true);

        //$data['urls'] = DB::table('TEMP_URLS')->select()->get()->toArray();
        
        //return view('relatorio.dash-index')->with($data);
        $html = view('relatorio.dash-index')->with($data)->render();

        return \PDF::loadHtml($html)
                ->setPaper('a4','portrait')
                ->stream('relatorio.pdf');
        
    }

    /**
     * @description: adiciona url das imagens na tabela temporária
     * @author: Fernando Bino Machado
     * @param: Request $request
     * @return:
    */

    public function addUrls(Request $request){
        
        $params = $request->all();
        
        DB::table('TEMP_URLS')->delete();

        if( isset($params['urls']) && !empty($params['urls']) && count($params['urls']) ){
            foreach( $params['urls'] as $num => $val ){
                
                DB::table('TEMP_URLS')
                    ->insert([
                        'dsUrl'=> base64_decode($val)
                    ]);
            }
        }       
    }

    /**
     * @description: gera as categorias contadas
     * @author Fernando Bino Machado
     * @param
     * @return
    */

    public function relatorioCategorias($params=[]){
        //busca as categorias cadastradas
        $todasCategorias = DB::table('categoria')->select()->get();
        $categorias=[];

        //se existirem categorias
        if( count($todasCategorias) ){
            foreach( $todasCategorias as $num => $val ){
                
                //soma os lançamentos de cada categoria
                $lancamentosCategoria = DB::table('orcamento as o')
                        ->select('l.vlLancamento','o.cdCategoria','l.tpLancamento')
                        ->join('conta as c','c.cdOrcamento','=','o.cdOrcamento')
                        ->join('lancamento as l','c.cdConta','=','l.cdConta')
                        ->where('o.cdCategoria',$val->cdCategoria)
                        ->get();
                
                //calcula o saldo da categoria
                $saldoCategoria = (float) 0;
                $todasCategorias[$num]->saldo = number_format($saldoCategoria, 2, '.','');

                if( count($lancamentosCategoria) ){
                    foreach( $lancamentosCategoria as $cat => $dados ){
                        //verifica que tipo de operação deve fazer
                        if( $dados->tpLancamento == '1' ){
                            $saldoCategoria += (float) $dados->vlLancamento;
                        }else{
                            $saldoCategoria -= (float) $dados->vlLancamento;
                        }                     
                    }
                }

                $categorias[] = [
                    'cdCategoria' => $val->cdCategoria,
                    'nmCategoria' => $val->nmCategoria,
                    'saldo' => $saldoCategoria
                ];
            }
        }

        return json_encode($categorias);
    }

}