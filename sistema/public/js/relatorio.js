$(function(){
    /**
     * @description: salva as urls das canvas na tabela temporária
     * @author: Fernando Bino Machado
    */

    $('#pdfDashInicio').click(function(){
        var dados = $('#formFiltro').serializeArray()
        var url = 'index-pdf'

        window.open(url,'_blank')
        
        //salvaUrls()
        return false
    })

    configsInicio()
});

/**
* @description: aplica algumas configurações iniciais
* @author: Fernando Bino Machado
* @param:
* @return:
*/

function configsInicio(){
    try{
        //json com itens para execução dessa função
        var configFunc = {
            parametros: JSON.parse( $('#parametros').val() )
        }

        // mantem os parametros do filtro caso existam
        $('#dtinicio').val(configFunc.parametros.dtinicio)
        $('#dtfinal').val(configFunc.parametros.dtfinal)
        $('#filtro-descricao').val(configFunc.parametros.descricao)
        $('#filtro-tipo').val(configFunc.parametros.tipo).trigger('change')

    }catch(e){}
}

/**
 * @description: salva as urls das canvas na tabela temporária
 * @author: Fernando Bino Machado
*/

function salvaUrls(){
    try{
        setTimeout(function(){
            var listaUrls = [];

            $('.graficos').each(function(){
                var id = $(this).attr('id')
                var canvas = document.getElementById(id)
                var url = canvas.toDataURL()
                listaUrls.push( btoa(url) )
            })
            
            send_ajax('/finance/relatorios/add-urls','post',{_token:$('#tkn').val(), urls: listaUrls},retornoAddUrls)

        }, 1000)
    }catch(e){}
}

/**
 * @description: salva as urls das canvas na tabela temporária
 * @author: Fernando Bino Machado
*/

function retornoAddUrls(resp){
    console.log(resp)
    window.open('/finance/relatorios/index-pdf')
}

/**
 * @description: gera grafico
 * @author: Fernando Bino Machado
 * 
 * 
*/

function gerarGraficoSaldo(){
    var configs = {
        recebido: $('#recebido').val(),
        gasto: $('#gasto').val(),
        saldo: $('#saldo').val(),
    }

    try{
        new Chart(
            document.getElementById('graf-saldo').getContext('2d'),
            {
                type:'pie',
                data:{
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
        
     }catch(e){console.error(e)}
}

/**
 * @description gera o grafico de categorias
 * @author Fernando Bino Machado
 * @param
 * @return
*/

function gerarGraficoCategorias(){
    try{
        //organiza parametros de entrada
        var configFunc = {
            categorias: JSON.parse( $('#categorias').val() ),
            context: document.getElementById('graf-categoria'),
            labels:[],
            datasets:[],
            label: [],
            data: [],
            backgroundColor: []
        }
        
        //monta os datasets para gerar o grafico
        configFunc.categorias.forEach(function(val,num){
            configFunc.labels.push( val.nmCategoria )
            
            configFunc.label.push( val.nmCategoria )
            configFunc.data.push( val.saldo )
            configFunc.backgroundColor.push( 'blue' )
        })

        //gera o grafico
        new Chart(
            configFunc.context,
            {
                type: 'pie',
                data: {
                    labels: configFunc.labels,
                    datasets: [
                        {
                            label: configFunc.label, data: configFunc.data, backgroundColor: configFunc.backgroundColor
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
                        text: "Categorias",
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

