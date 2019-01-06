$(function(){
    /**
    * @description: força maiusculas no campo descrição
    * @author: Fernando Bino Machado
    * @param:
    * @return:
    */

    $('#descricao').keyup(function(){
        $(this).val( maiusculas( $(this).val() ) )
    })

    /**
    * @description: expande a sub lista de contas
    * @author: Fernando Bino Machado
    * @param:
    * @return:
    */

    $('.icone-resumo').click(function(){
        var id = $(this).attr('data-id')
        $('#'+id).fadeToggle('slow')
    })

    /**
    * @description: abre modal de contas
    * @author: Fernando Bino Machado
    * @param:
    * @return:
    */

    $('#open-modal').click(function(){
        $('.modal').show()
    })

    /**
    * @description: fecha modal de contas
    * @author: Fernando Bino Machado
    * @param:
    * @return:
    */

    $('#close-modal').click(function(){
        $('.modal').hide()
    })

    /**
    * @description: seleciona conta para preencher formulario
    * @author: Fernando Bino Machado
    * @param:
    * @return:
    */

    $('.bt-seleciona').click(function(){
        var obj = JSON.parse($(this).attr('data-obj'))

        $('#tipo').val(obj.tpConta).trigger('change')
        $('#conta').val(obj.cdConta)
        $('#descricao').val(obj.dsConta)
        $('#valor').val(obj.vlConta)

        $('.modal').hide();

        $('#valor').focus()
    })

    /**
    * @description: exclui lançamento e exibe confirmação de exclusão via função de callback
    * @author: Fernando Bino Machado
    * @param: json
    * @return: json
    */

   $('.bt-excluir').click(function(){
    if(confirm("DESEJA EXCLUIR O LANÇAMENTO SELECIONADO??")){
        //prepara parametros para requisição
        obj = {
            _token:$('#tkn').val(),
            cd: $(this).attr('data-cd')
        }

        //envia para requisição ajax com callback
        send_ajax('/deletar-lancamento','post', obj, respostaDeletaOrcamento)
    }

})

    configsInicio()
})

/**
* @description: aplica configuraçõe iniciais
* 1 configura os links de paginação com parametros do filtro
* 2 move o foco no campo tipo do form de lançamentos
* @author: Fernando Bino Machado
*/

function configsInicio(){
    try{
    
    //1 configura os links de paginação com parametros do filtro

        //json com itens para execução dessa função
        var configFunc = {
            parametros: JSON.parse( $('#parametros').val() )
        }
        
        //configura parametros do filtro na paginação
        if( configFunc.parametros != undefined && configFunc.parametros != "" ){
            
            //percorre os links
            $('a').each(function(i){
                
                //verifica se é um link de paginação
                if( $(this).attr('class') == 'page-link' ){
                    
                    //configura url com os parametros do filtro
                    var url = $(this).attr('href')
                    var novaUrl = url
                    
                    novaUrl += '&dtinicio='+configFunc.parametros.dtinicio
                    novaUrl += '&dtfinal='+configFunc.parametros.dtfinal
                    novaUrl += '&descricao='+configFunc.parametros.descricao
                    novaUrl += '&tipo='+configFunc.parametros.tipo

                    //aplica nova url no link de paginação
                    $(this).attr('href', novaUrl)
                }
            })
        }
    
    //2 move o foco no campo tipo do form de lançamentos
        $('#tipo').focus()

    }catch(e){}
}

/**
* @description: callback para exclusão de lançamento, exibe mensagem de resposta
* @author: Fernando Bino Machado
* @param: json
* @return: açao de redirecionamento ou mensagem de erro
*/

function respostaDeletaOrcamento(resp){
    try{
        var jsonResp = JSON.parse(resp)

        $('#tipo').focus()

        if(jsonResp.status == "1"){
            message('Registro excluído com sucesso!!',1)
            redireciona('/lancamentos')
        }else{
            message('Não foi possível excluir o registro',2)
        }
    }catch(e){}
}