<?php
/**
 * @description: Classe para definir periodos de datas
 * @author: Fernando Bino Machado
 */
namespace App\Http\Repositories;

use DateTime;
use DateInterval;

class Periodo{
    /**
     * Foi necessário criar uma classe específica para definição de períodos, que ocorre em mais de um momento no projeto,
     * Essa classe por sua vez será melhorada com o tempo à medida que o projeto cresça
     */

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
    * @description: define período de ultimos dias para poder fazer operações com datas com base nos dias recebidos
    * @author: Fernando Bino Machado
    * @params:  int $dias
    * @return: array $periodo[inicial,final]
    */

    public function definePeriodoUltimosDias($dias){
        if( !is_numeric($dias) || $dias < 0 ){
            $dias=1;
        }
        
        $data = new DateTime();

        $dtHoje = $data->format('Y-m-d H:i:s');

        $numDias = (int) ceil($dias);
        
        $intervalo = new DateInterval('P'.$numDias.'D');

        $dtSubtraida = $data->sub($intervalo);

        $dtInicio = $dtSubtraida->format('Y-m-d H:i:s');

        return ['inicial'=>$dtInicio, 'final'=>$dtHoje];
    }

    /**
    * @description: define período de próximos dias para poder fazer operações com datas com base nos dias recebidos
    * @author: Fernando Bino Machado
    * @params:  int $dias
    * @return: array $periodo[inicial,final]
    */

    public function definePeriodoProximosDias($dias){
        if( !is_numeric($dias) || $dias < 0 ){
            $dias=1;
        }

        $data = new DateTime();

        $dtHoje = $data->format('Y-m-d H:i:s');

        $numDias = (int) ceil($dias);
        
        $intervalo = new DateInterval('P'.$numDias.'D');

        $dtAdicionada = $data->add($intervalo);

        $dtFinal = $dtAdicionada->format('Y-m-d H:i:s');

        return ['inicial'=>$dtHoje, 'final'=>$dtFinal];
    }
}