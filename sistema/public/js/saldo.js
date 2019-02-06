
$(function(){
    
    /**
     * @description: faz a automática dos lançamentos
     * @author: Fernando Bino Machado
    */

    $('#verMais').on('inview', function(event, isInView) {
        if( parseInt( $('#pageAtual').val() ) < parseInt( $('#totalPage').val() ) ){
            if(isInView){
                $('#loader').show()
                setTimeout(function(){
                    paginarLancamentos()
                },2000)
            }
        }
    })

    configsInicio()
})

/**
* @description: aplica configurações iniciais
* @author: Fernando Bino Machado
* @params: 
* @return: 
*/

function configsInicio(){
    try{
        gerarPizza()
    }catch(e){}
}


/**
 * @description: gera grafico
 * @author: Fernando Bino Machado
*/

function gerarPizza(){
    var configs = {
        recebido: $('#recebido').val(),
        gasto: $('#gasto').val(),
        saldo: $('#saldo').val(),
    }

    try{
        new Chart(
            document.getElementById('graf').getContext('2d'),
            {
                type:'pie',
                data:{
                    // labels: ['Recebido R$ '+configs.recebido,'Gasto R$ '+configs.gasto,'Saldo R$ '+configs.saldo],
                    labels:['Recebido','Gasto','Saldo'],
                    datasets: [
                        {
                            label:['Recebido','Gasto','Saldo'],
                            data:[configs.recebido,configs.gasto,configs.saldo],
                            backgroundColor:['blue','red','green']
                        }
                        
                    ]
                },
                options:{
                    cutoutPercentage: 50,
                    animation: {
                        animateScale: true
                    },
                    title:{
                        display: true,
                        fontSize: 20,
                        text: "Composição de Saldo",
                        position: "top"
                    },
                    legend:{
                        position: "right"
                    }
                    
                }
            }
        )
        
     }catch(e){}
}

/**
 * @description: recebe a resposta da paginação dos lançamentos
 * @author: Fernando Bino Machado
 * @params: json resp
*/

function respostaPaginacaoLancamentos(resp){
    
    $('#pageAtual').val(resp.page)
    $('#lancamentos').append(resp.html)

    $('#loader').hide()
}

/**
 * @description: monta os dados para paginação
 * @author: Fernando Bino Machado
*/

function paginarLancamentos(){
    try{
        let rota = $('#route-paginacao').val()

        //monta os dados
        var page = parseInt($('#pageAtual').val()) + 1

        var dados = {
            _token: $('#tkn').val(),
            dias: $('#dias').val(),
            page: page
        }

        //faz a paginação
        if( parseInt(dados.page) <= parseInt( $('#totalPage').val() ) ){
            send_ajax(rota,'post',dados,respostaPaginacaoLancamentos)
        }else{
            $('#loader').hide()
        }
    }catch(e){}
}