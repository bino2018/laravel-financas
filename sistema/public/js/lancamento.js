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
* @author: Fernando Bino Machado
* @params: 
* @return:
*/

function configsInicio(){
    try{
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

        alert(jsonResp.message)

        if(jsonResp.status == "1"){
            redireciona('/lancamentos')
        }
    }catch(e){}
}