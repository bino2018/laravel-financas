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
    * @description: preenche o select tipo conforme a opção informada
    * @author: Fernando Bino Machado
    * @param:
    * @return:
    */

    $('#tipo').change(function(){
        var tipo = $(this).val()
        var options = ''

        if(tipo == '1'){
            options = $('#dv-entradas').html()
        }else if(tipo == '2'){
            options = $('#dv-saidas').html()
        }else{
            options = '<option value="0">Selecione</options>'
        }

        $('#orcamento').html(options)
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
    * @description: preenche o select tipo conforme a opção informada
    * @author: Fernando Bino Machado
    * @param:
    * @return:
    */

    $('.bt-excluir').click(function(){
        if(confirm("DESEJA REMOVER A CONTA SELECIONADA?")){
            var obj = {
                _token: $('#tkn').val(), cd: $(this).attr('data-cd')
            }

            send_ajax('/deletar-conta','post', obj, respostaDeletaConta)
        }
    })

    configsInicio()
})

/**
* @description: aplica algumas configurações iniciais
* @author: Fernando Bino Machado
* @param:
* @return:
*/

function configsInicio(){
    $('#tipo').focus()
}

/**
* @description: callback para exclusão de conta, exibe mensagem de resposta
* @author: Fernando Bino Machado
* @param: json
* @return: açao de redirecionamento ou mensagem de erro
*/

function respostaDeletaConta(resp){
    try{
        var jsonResp = JSON.parse(resp)

        alert(jsonResp.message)

        if(jsonResp.status == "1"){
            redireciona('/contas')
        }
    }catch(e){}
}