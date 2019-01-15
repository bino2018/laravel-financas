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
* 2 mantem os parametros do filtro caso existam
* 3 move o foco no campo tipo do form de lançamentos
* @author: Fernando Bino Machado
*/

function configsInicio(){
    try{
    
    //1 configura os links de paginação com parametros do filtro

        //json com itens para execução dessa função
        var configFunc = {
            parametros: JSON.parse( $('#parametros').val() ),
            contaCampos: 0
        }
        
        //configura parametros do filtro na paginação
        if( configFunc.parametros._token != undefined && configFunc.parametros._token != "" ){
            
            //percorre os links
            $('a').each(function(i){
                var classe = $(this).attr('class')

                configFunc.contaCampos = 0

                //verifica se é um link de paginação
                if( classe.indexOf('page-link') != -1 ){
                    
                    //configura url com os parametros do filtro
                    var url = $(this).attr('href')
                    var novaUrl = url


                    
                    if(configFunc.parametros.dtinicio != undefined && configFunc.parametros.dtinicio != null){
                        novaUrl += '&dtinicio='+configFunc.parametros.dtinicio
                        configFunc.contaCampos += 1
                    }
                    
                    if(configFunc.parametros.dtfinal != undefined && configFunc.parametros.dtfinal != null){
                        novaUrl += '&dtfinal='+configFunc.parametros.dtfinal
                        configFunc.contaCampos += 1
                    }

                    if(configFunc.parametros.descricao != undefined && configFunc.parametros.descricao != null){
                        novaUrl += '&descricao='+configFunc.parametros.descricao
                        configFunc.contaCampos += 1
                    }

                    if(configFunc.parametros.tipo != undefined && configFunc.parametros.tipo != null){
                        novaUrl += '&tipo='+configFunc.parametros.tipo
                        configFunc.contaCampos += 1
                    }

                    if(configFunc.contaCampos > 0){
                        novaUrl += '&_token='+configFunc.parametros._token
                    }

                    //aplica nova url no link de paginação
                    $(this).attr('href', novaUrl)
                }
            })
        

        // 2 mantem os parametros do filtro caso existam
            $('#dtinicio').val(configFunc.parametros.dtinicio)
            $('#dtfinal').val(configFunc.parametros.dtfinal)
            $('#filtro-descricao').val(configFunc.parametros.descricao)
            $('#filtro-tipo').val(configFunc.parametros.tipo).trigger('change')

        }
        
    //3 move o foco no campo tipo do form de lançamentos
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