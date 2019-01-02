$(function(){

    /**
     * @description: valida os campos antes de submeter para calculo
     * @author: Fernando Bino Machado
     * @params:
     * @return:
    */

    $('#fr-calculo').submit(function(){
        
        //array para validar os valores antes do cálculo
        var valores = [
            {valor:parseFloat( $('#valor').val() ), campo:1},
            {valor:parseFloat( $('#taxa-ano').val() ), campo:1},
            {valor:parseFloat( $('#taxa-corretora').val() ), campo:0},
            {valor:parseFloat( $('#taxa-custodia').val() ), campo:1},
            {valor:parseFloat( $('#imposto').val() ), campo:1}
        ]

        var erros = 0;

        //verifica se os campos obrigatorios tem valor válido
        valores.forEach(function(obj,num){
            if(obj.campo == 1){
                if(!obj.valor > 0){
                    erros += 1
                }
            }
        })

        //submete os dados se não houverem campos obrigatorios zerados
        if(erros == 0){
            return true
        }else{
            return false
        }
    })


    configsInicio()
})

/**
 * @description: aplica as configuraçõe de inicio
 * @author: Fernando Bino Machado
 * @params:
 * @return:
*/

function configsInicio(){
    try{
        $('#valor').focus()
    }catch(e){}
}