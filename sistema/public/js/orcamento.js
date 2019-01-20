$(function(){
    /**
    * @description: força os caracteres a serem maiusculos
    * @author: Fernando Bino Machado
    * @param: String
    * @return:
    */

    $('#descricao').keyup(function(){
        $(this).val( maiusculas( $(this).val() ) )
    })

    /**
    * @description: seleciona orçamento para ser alterado
    * @author: Fernando Bino Machado
    * @param: 
    * @return: 
    */
    
   $('.bt-editar').click(function(){
        //monta os parametros
        var obj = {
            id: $(this).attr('data-cd'),
            dados: JSON.parse($(this).attr('data-obj'))
        }

        //preenche o formulario
        $('#codigo').val(obj.id)
        $('#categoria').val(obj.dados.cdCategoria).trigger('change')
        $('#descricao').val(obj.dados.nmOrcamento)
        $('#valor').val(obj.dados.vlOrcamento)
        $('#dia').val(obj.dados.dia).trigger('change')
        $('#tipo').val(obj.dados.tpOrcamento).trigger('change')
        $('#ocorrencia').val(obj.dados.cdOcorrencia).trigger('change')

        //validade necessita de verificação
        if( obj.dados.validade != '2050-12-31 23:59:59' ){
            $('#validade').val(obj.dados.validade.substr(0,10))
        }else{
            $('#validade').val('')
        }

        $('#categoria').focus()
    })

    /**
    * @description: faz requisição ajax para marcar o status do orçamento,
    *  depois com função de callback dependendo da resposta do backend altera as propriedades dos botões de marcação
    * @author: Fernando Bino Machado
    * @param: Json
    * @return: Json
    */

   $('.checks').click(function(){
        //prepara parametros para requisição
        var obj = {
            _token: $('#tkn').val(),
            cd: $(this).attr('data-cd'),
            status: $(this).attr('data-st'),
            check: $(this).attr('id')
        }
        
        //envia para requisição com callback
        send_ajax('/marcar-orcamento', 'post', obj, respostaMarcaOrcamento)

    })

    /**
    * @description: exclui orçamento e exibe confirmação de exclusão via função de callback
    * @author: Fernando Bino Machado
    * @param: json
    * @return: json
    */

   $('.bt-excluir').click(function(){
        if(confirm("DESEJA EXCLUIR O ORÇAMENTO SELECIONADO??")){
            //prepara parametros para requisição
            obj = {
                _token:$('#tkn').val(),
                cd: $(this).attr('data-cd')
            }

            //envia para requisição ajax com callback
            send_ajax('/deletar-orcamento','post', obj, respostaDeletaOrcamento)
        }
    })

    /**
    * @description: recebe confirmação do usuário para gerar as contas
    * @author: Fernando Bino Machado
    * @param: 
    * @return: 
    */

    $('#form-contas').submit(function(){
        if(confirm("CONFIRMA A GERAÇÃO DE CONTAS PARA O PRÓXIMO MÊS???")){
            return true
        }else{
            return false
        }
    })

    configsInicio()
})

/**
* @description: reune algumas ações que devem ocorrer inicialmente
* @author: Fernando Bino Machado
* @param:
* @return:
*/

function configsInicio(){
    try{
        $('#categoria').select2({
            placeholder: 'Selecione...'
        })
        $('#categoria').focus()
    }catch(e){}
}

/**
* @description: callback para exclusão de orçamento, exibe mensagem de resposta
* @author: Fernando Bino Machado
* @param: json
* @return: açao de redirecionamento ou mensagem de erro
*/

function respostaDeletaOrcamento(resp){
    try{
        var jsonResp = JSON.parse(resp)

        $('#categoria').focus()

        if(jsonResp.status == "1"){
            message('Registro exluído com sucesso!!',1)
            redireciona('/orcamentos')
        }else{
            message('Não foi possível excluir o registro...')
        }
    }catch(e){}
}

/**
* @description: callback para marcação de status do orçamento, altera propriedades do botão de marcação
* conforme a resposta do backend
* @author: Fernando Bino Machado
* @param: json
* @return: 
*/

function respostaMarcaOrcamento(resp){
    try{
        //pega resposta
        var jsonResp = JSON.parse(resp)
        
        //define classes, title, e text do botao
        var classes = ['','lb-status-on','lb-status-off']
        var titulos = ['','Altera o status para Off','Altera o status para On']
        var texts = ['','On','Off']

        //altera propriedades do botão somente se a marcação tenha ocorrido com sucessso
        if(jsonResp.status == "1"){
            //necessário fazer dessa forma para recalcular a receita
            setTimeout(()=>{
                window.location.href = '/orcamentos';
            },750)
            
        }
    }catch(e){}
}