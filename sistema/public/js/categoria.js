$(function(){

    /**
    * @description: força os caracteres a serem maiusculos
    * @author: Fernando Bino Machado
    * @param: String
    * @return:
    */

    $('#categoria').keyup(function(){
        $(this).val( maiusculas( $(this).val() ) )
    })


    /**
    * @description: faz requisição ajax para marcar o status da categoria,
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
        send_ajax('/marcar-categoria', 'post', obj, respostaMarcaCategoria)

    })

    /**
    * @description: seleciona categoria para ser alterada
    * @author: Fernando Bino Machado
    * @param: 
    * @return: 
    */
    
    $('.bt-editar').click(function(){
        //monta os parametros
        var obj = {
            id: $(this).attr('data-cd'),
            nome: $(this).attr('data-nm')
        }

        //preenche o formulario
        $('#codigo').val(obj.id)
        $('#categoria').val(obj.nome)
        
        $('#categoria').focus()
    })

    /**
    * @description: exclui categoria e exibe confirmação de exclusão via função de callback
    * @author: Fernando Bino Machado
    * @param: json
    * @return: json
    */

    $('.bt-excluir').click(function(){
        
        if(confirm("DESEJA EXCLUIR A CATEGORIA SELECIONADA??")){
            //prepara parametros para requisição
            obj = {
                _token:$('#tkn').val(),
                cd: $(this).attr('data-cd')
            }

            //envia para requisição ajax com callback
            send_ajax('/deletar-categoria','post', obj, respostaDeletaCategoria)
        }
    })

    //configurações iniciais
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
        //gera o grefico
        grafico()

        $('#categoria').focus()
    }catch(e){}
}

/**
* @description: callback para exclusão de categoria, exibe mensagem de resposta
* @author: Fernando Bino Machado
* @param: json
* @return: açao de redirecionamento ou mensagem de erro
*/

function respostaDeletaCategoria(resp){
    try{
        var jsonResp = JSON.parse(resp)

        alert(jsonResp.message)

        if(jsonResp.status == "1"){
            redireciona('/categorias')
        }
    }catch(e){}
}

/**
* @description: callback para marcação de status da categoria, altera propriedades do botão de marcação
* conforme a resposta do backend
* @author: Fernando Bino Machado
* @param: json
* @return: 
*/

function respostaMarcaCategoria(resp){
    try{
        //pega resposta
        var jsonResp = JSON.parse(resp)
        
        //define classes, title, e text do botao
        var classes = ['','lb-status-on','lb-status-off']
        var titulos = ['','Altera o status para Off','Altera o status para On']
        var texts = ['','On','Off']

        //altera propriedades do botão somente se a marcação tenha ocorrido com sucessso
        if(jsonResp.status == "1"){
            var obj = {
                label: '#lab'+jsonResp.check.replace('check',''),
                checkbox: '#check'+jsonResp.check.replace('check','')
            }
            
            //altera as propriedades
            $(obj.label).attr('class',classes[jsonResp.paramStatus])
            $(obj.label).text(texts[jsonResp.paramStatus])
            $(obj.checkbox).attr('data-st',jsonResp.paramStatus)
            $(obj.checkbox).prop('checked', jsonResp.checado)
            $(obj.label).attr('title', titulos[jsonResp.paramStatus])
        }
    }catch(e){}
}

/**
 * @description: gera o grafico de categorias
 * @author: Fernando Bino Machado
 * @param:
 * @return
*/

function grafico(){
    try{
        //configs para executar a função
        var configs = {
            dados:JSON.parse($('#dados').val()),
            canvas: document.getElementById('grafico').getContext('2d'),
            labels: [],
            datasets: [],
            entradas: {
                label:'Recebidos',data:[],backgroundColor:[]
            },
            saidas: {
                label:'Gastos',data:[], backgroundColor:[]
            },
            saldo: {
                label:'Saldo', data:[], backgroundColor:[]
            }
        }

        //monta datasets e labels
        configs.dados.forEach(function(val,num){
            //agrega label
            configs.labels.push(val.nmCategoria)

            //define datasets entradas
            configs.entradas.data.push(val.entradas)
            configs.entradas.backgroundColor.push('blue')

            //define datasets saidas
            configs.saidas.data.push(val.saidas)
            configs.saidas.backgroundColor.push('red')

            //define datasets saldo
            configs.saldo.data.push(val.saldo)
            configs.saldo.backgroundColor.push('green')
        })

        //adiciona ao dataset
        configs.datasets.push(configs.entradas)
        configs.datasets.push(configs.saidas)
        configs.datasets.push(configs.saldo)

        //cria o grafico
        new Chart(
            configs.canvas,
            {
                type: 'bar',
                data: {
                    labels: configs.labels,
                    datasets: configs.datasets
                },
                options: {
                    animation: {
                        animateScale: true
                    },
                    title:{
                        display: true,
                        fontSize: 20,
                        text: "Parcial por Categorias"
                    }
                }
            }
        )

        
    }catch(e){console.log(e)}
}