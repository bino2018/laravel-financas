<?php
/**
 * @description: Classe para definir periodos de datas
 * Em desenvolvimento
 * @author: Fernando Bino Machado
 * 
 */
namespace App\Http\Repositories;

use DateTime;
use DateInterval;

class Periodo{
    
    //atributos auxiliares para montagem de período
    public $dias;
    public $meses;
    public $anos;
    public $data;

    /**
    * @description: parametros opcionais para quando invocar o construtor
    * $dias,$meses,$anos são utilizados para o metodo defineInterval
    * parametro $data é utilizado para o metodo defineDate
    */
    public function __construct($dados=['dias'=>null,'meses'=>null,'anos'=>null,'data'=>null,'dataInicial'=>null,'dataFinal'=>null]){
        $this->dias = $dados['dias'];
        $this->meses = $dados['meses'];
        $this->anos = $dados['anos'];
    }
    
    /**
     * @description: monta o período de datas, inicial e final
     * 
     * @author Fernando Bino Machado
     * 
     * @param: array $params
     * 
     * $params['qtdeUnidade'=>1] - quando omitio assume valor 1, o parametro é reponsável pela quantidade do periodo
     * quantos dias, meses ou anos dependendo da próxima chave do array
     * 
     * $params['formato'=>'D'] - quando omitido assume valor 'D' para formato dias, assim se a chave qtdeUnidade acima
     * por exemplo for 15 e a chave formato for = 'D' teremos um resultado de 15 dias, os valores recomendados para uso são
     * 'D' para dias,'M' para meses ou 'Y' para anos
     * 
     * $params['retorno'] - quando omitido assume 'passado', significa que a função vai montar um período por exemplo
     * dos últimos 15 dias, caso qualquer valor diferente de 'passado' seja informado, o período será dos próximos 15 dias
     * 
     * $params['data'] - quando omitido assume a data atual, esse parametro é o responsável por 
     * delimitar a data a partir da qual será montado o perído
     * 
     * @return array return ['inicial'=>'data', 'final'=>'data'];
     * 
     * @example: montaPeriodo( ['qtdeUnidade'=>15,'formato'=>'D','retorno'=>'futuro','data'=>'2019-01-11'] );
     * 
    */
    
    public function montaPeriodo( $params=['qtdeUnidade'=>1, 'formato'=>'D','retorno'=>'passado', 'data'=>null] ){
        
        //trata a qtdeUnidade recebida
        if( !isset( $params['qtdeUnidade'] ) || !is_numeric( $params['qtdeUnidade'] ) || $params['qtdeUnidade'] < 0 ){
            $unidades = (int) 1;
        }else{
            $unidades = (int) ceil( $params['qtdeUnidade'] );
        }
        
        //define o intervalo para montar o período
        $paramIntervalo = isset( $params['formato'] ) ? "P{$unidades}{$params['formato']}" : "P{$unidades}D";
        
        $intervalo = new DateInterval($paramIntervalo);
        $data = ( isset($params['data']) && !is_null($params['data']) ) ? new DateTime($params['data']) : new DateTime();
        
        //vefica o tipo de retorno
        if( isset($params['retorno']) && !empty($params['retorno']) && $params['retorno'] != 'passado' ){
            //define a data atual para se basear na montagem do perído
            $data = ( isset($params['data']) && !is_null($params['data']) ) ? new DateTime($params['data']) : new DateTime();
            $dtHoje = $data->format('Y-m-d')." 00:00:00";

            //soma e monta periodo no futuro
            $dtAdicionada = $data->add($intervalo);
            $dtFinal = $dtAdicionada->format('Y-m-d')." 23:59:59";

            return ['inicial'=>$dtHoje, 'final'=>$dtFinal];
        }else{
            //define a data atual para se basear na montagem do perído
            $data = ( isset($params['data']) && !is_null($params['data']) ) ? new DateTime($params['data']) : new DateTime();
            $dtHoje = $data->format('Y-m-d')." 23:59:59";

            //subtrai e monta periodo passado
            $dtSubtraida = $data->sub($intervalo);
            $dtInicio = $dtSubtraida->format('Y-m-d')." 00:00:00";

            return ['inicial'=>$dtInicio, 'final'=>$dtHoje];
        }
    }

    /**
    * @description: retorna diferenca entre duas datas
    * @author: Fernando Bino Machado
    * @param: array $arrDatas[0,1] - cada posição do array deve conter uma data
    * @param: $formatoDif - o formato de retorno desejado, quando omitido assume o formato em anos 'Y'
    * @return: int $diferenca, retorna a diferenca entre as datas com base no formato especificado na variável $formatoDif
    * @example: difDatas( ['2019-01-01','2019-07-25'], 'D' );
    */

    public function difDatas($arrDatas=null, $formatoDif='Y'){
        //prepara variavel para retorno
        $diferenca = 0;
        
        //faz os calculos se as datas foram enviadas
        if( !is_null($arrDatas) ){
            //define as datas
            $dt1 = new DateTime($arrDatas[0]);
            $dt2 = new DateTime($arrDatas[1]);
            
            //ordena as datas e pega a diferença entre elas
            if( $dt1->getTimestamp() < $dt2->getTimestamp() ){
                $dif = date_diff($dt1,$dt2);
            }else{
                $dif = date_diff($dt2,$dt1);
            }

            //define a diferença
            $formato = "%{$formatoDif}";
            $diferenca = $dif->format($formato);

            if($diferenca < 1){
                $diferenca = 1;
            }
        }

        return (int) $diferenca;

    }

    /**
     * @description: recebe duas datas e as retorna ordenadas e formatadas para poder fazer buscas no banco
     * @author: Fernando Bino Machado
     * @param: array $arrDatas[0,1] - cada posição do array deve conter uma data
     * @return: array $periodo[inicial,final]
    */

    public function preparaDatas($arrDatas=null){
        $periodo = null;

        if( !is_null($arrDatas) ){
            //cria as datas
            $data1 = new DateTime($arrDatas[0]);
            $data2 = new DateTime($arrDatas[1]);

            //compara e ordena
            if( $data1->getTimestamp() < $data2->getTimestamp() ){
                $periodo = [
                    'inicial'=>$data1->format('Y-m-d H:i:s'),
                    'final'=>$data2->format('Y-m-d H:i:s')
                ];
            }else{
                $periodo = [
                    'inicial'=>$data2->format('Y-m-d H:i:s'),
                    'final'=>$data1->format('Y-m-d H:i:s')
                ];
            }

            //sobreescreve a segunda data caso o formato de horas esteja zerado
            if( $data2->format('H:i:s') == "00:00:00" ){
                $periodo['final'] = $data2->format('Y-m-d')." 23:59:59";
            }
        }

        //retorna $periodo
        return $periodo;

    }


}