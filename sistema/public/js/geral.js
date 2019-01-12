$(function(){
    configGeral();
})

/**
 * @author Fernando Bino Machado
 * @description aplica configurações universais que poderão ocorrer em todas as interfaces
*/

function configGeral(){
    try{
        //altera as classes das paginaçãoes default do laravel
        $('ul.pagination').each(function(i){
            if( $(this).html() != undefined && $(this).html() != "" ){
                $(this).addClass(' pagination-sm')
            }
        })

        $('ul.pagination a').addClass('text text-secondary')
        $('ul.pagination li.active span.page-link').css('border','1px solid #ffffff')
        $('ul.pagination li.active span.page-link').addClass('bg-dark')

    }catch(e){}
}

/**
 * @author Fernando Bino Machado
 * @description função modelo para requisições ajax
 * @param {*} url - url que será requisitda
 * @param {*} tipo - tipo pode ser get ou post
 * @param {*} dados - quando o parametro tipo for get o tipo é um json vazio, quando for post o tipo é um json com os dados a serem enviados
 * @param {*} callback - função utilizada para pegar o retorno do ajax
*/

function send_ajax(url, tipo, dados, callback){
    $.ajax({
        url: url,
        type: tipo,
        data: dados,
        success:function(data){
            console.log(data)
            callback(data)
        },
        error:function(e){
            console.log(e)
            alert("Erro ao concluir ação...")
        }
    })
}

/**
 * @author Fernando Bino Machado
 * @description redireciona para uma determinada pagina
 * @param {*} param - endereço para o qual sera o navegador será redirecionado
*/

function redireciona(param){
    setTimeout(function(){
        window.location.href=param
    }, 1000)
}

/**
 * @author Fernando Bino Machado
 * @description recebe uma classe, percorre essa classe, monta um array com os respectivos ids
 *  obs: a classe deve ser referente a elementos input de formulários
 * @param {*} classe - classe que será iterada
 * @return array lista
*/

function listaIdsCampos(classe){
    var lista = []
    
    $('.'+classe).each(function(lin){
        lista.push($(this).attr('id'))
    })
    
    return lista
}

/**
 * @author Fernando Bino Machado
 * @description percorre uma determinada lista de ids e verifica se existem valores vazios
 *  obs: os ids devem ser referente a elementos input de formulários
 * @param {*} lista - lista de ids
 */

function camposVazios(lista){
    var vazio = false

    lista.forEach(function(idCampo, num){
        if( $("#"+idCampo).val()  == "" ){
            vazio = true
        }
    })

    return vazio
}

/**
 * @author Fernando Bino Machado
 * @description converte uma string para maiusculas, util para campos de texto em formulários
 * @param {*} str 
 * @return conversão de str para maiusculas
 */

function maiusculas(str){
    return str.toUpperCase()
}

/**
 * @author Fernando Bino Machado
 * @description verifica se um cadeia de caracteres equivale a um cpf
 * @param {*} cpf 
 * @return boolean valido - retorna um booleano que caracteriza o cpf como verdadeiro ou falso
*/

function validaCpf(cpf){
    //variavel de retorno
    valido = false

    //define lista prévia de cpfs inválidos, feito dessa maneira para evitar fazer assim: cpfsInvalidos = ['11111111111',...]
    //a verificação é necessária pois a sequencia de 11 números iguais ira retornar um cpf 11111111111
    //como verdadeiro quando na verdade é falso
    var cpfsInvalidos=[]

    for(var lt=0;lt<10;lt++){
        cpfsInvalidos.push(String(lt).repeat(11))
    }

    //agora verifica se o cpf informado não se encontra na lista prévia de cpfs inválidos
    //caso sim, a função para por aqui e retorna a variavel valido que ainda é false
    if(cpfsInvalidos.indexOf(cpf) == -1){

        //prepara para iniciar a validação do primeiro digito verificador
        validacao = cpf.substr(0,cpf.length - 2)
        calculo=0
        contador=10
        
        //percorre os caracters do cpf multiplicanco pelo contador que esta sendo decrescido de um em um
        for(var i=0; i<validacao.length; i++){
            calculo += validacao[i] * contador
            contador -= 1
        }
        
        //pega o resto da divisao do calculo multiplicado por 10 e atribui ao digitoVerificador
        divisao = (calculo * 10) % 11
        digitoVerificador = divisao
        
        //caso a divisão seja igual a 10 força o digito verificador a ser 0
        if(divisao == 10){
            digitoVerificador=0		
        }

        //agora verifica se o primeiro digito verificado equivale ao corresponte no cpf original passado como parametro
        //caso seja diferente a função para por aqui retornando a variavel valido que ainda é false
        if(digitoVerificador == cpf[9]){
            
            //sobreescreve as variveis para iniciar verificação do segundo digito validador
            calculo=0
            contador=11

            for(var j=0; j<validacao.length + 1; j++){
                calculo += cpf[j] * contador
                contador -= 1
            }
            
            //sobresreve a divisão 
            divisao = (calculo * 10) % 11
            digitoVerificador = divisao
            
            //caso a divisão seja igual a 10 força o digito verificador a ser 0
            if(divisao == 10){
                digitoVerificador=0
            }

            //finalmente após ter verificado e validado o segundo digito validador
            //altera a var valido para true para função poder retornar true para o cpf
            if(digitoVerificador == cpf[10]){
                valido=true
            }
        }
    }

    return valido
}



function validaCnpj(cnpj){
    var valido = false

    var cnpjsInvalidos=[]

    for(var lt=0;lt<10;lt++){
        cnpjsInvalidos.push( String(lt).repeat(14) )
    }

    if( cnpjsInvalidos.indexOf(cnpj) == -1 ){

        var listaNum1 = [5,4,3,2,9,8,7,6,5,4,3,2]
        var listaNum2 = [6,5,4,3,2,9,8,7,6,5,4,3,2]
        var digito1 = ""
        var digito2 = ""
        var numCnpjInicial = cnpj.substr(0, (cnpj.length) - 2)
        var numCnpjAposEtapa1 = ""
        var numCnpjVerificado = ""
        var soma = 0
        
        // validando primeiro digito verificador
        for(var i=0;i<listaNum1.length;i++){
            soma += listaNum1[i] * parseInt(numCnpjInicial[i])
            
        }
        
        if( (soma % 11) < 2){
            digito1 = 0
        }else{
            digito1 = 11 - (soma % 11)
        }

        soma = 0

        numCnpjAposEtapa1 = numCnpjInicial + String(digito1)

        // validando segundo digito verificador
        for(var j=0;j<listaNum2.length;j++){
            soma += listaNum2[j] * parseInt(numCnpjAposEtapa1[j])
        }

        if( (soma % 11) < 2 ){
            digito2 = 0
        }else{
            digito2 = 11 - (soma % 11)
        }

        numCnpjVerificado = numCnpjAposEtapa1 + String(digito2)

        if( numCnpjVerificado == cnpj ){
            valido=true
        }

    }

    return valido

}

function validaEmail(email) {
    var regex = /^([\w-]+(?:\.[\w-]+)*)@((?:[\w-]+\.)*\w[\w-]{0,66})\.([a-z]{2,6}(?:\.[a-z]{2})?)$/i
    return regex.test(email)
}

function gerarGraficoPadrao(strTipo, strTitulo, arrLabels, arrDatasets, idCanvas){
    try{

        var ctx = document.getElementById(idCanvas).getContext("2d")

        if(strTipo=="pie"){
            var options = {
                cutoutPercentage: 50,
                animation: {
                    animateScale: true
                },
                title:{
                    display: true,
                    fontSize: 20,
                    text: "Parcial Rol de Membros"
                },
                legend:{
                    position: "right"
                }
                
            }
        }else{
            var options={
                title:{
                    display: true,
                    text: strTitulo,
                    fontSize: 15
                }
            }
        }

        new Chart(
            ctx,
            {
                type: strTipo,
                data: {
                    labels: arrLabels,
                    datasets: arrDatasets
                },
                options: options
            }
        )

        $("#"+idCanvas)
            .css("width","100%")
            .css("background-color","#f1f1f1")
            
    }catch(e){}
}

function gerarGraficoLine(strTitulo, arrLabels, arrDatasets, idCanvas){
    try{
        var ctx = document.getElementById(idCanvas).getContext("2d")

        new Chart(
            ctx,
            {
                type: "line",
                data: {
                    labels: arrLabels,
                    datasets: arrDatasets
                },
                options:{
                    title:{
                        display: true,
                        text: strTitulo,
                        fontSize: 20
                    }
                }
            }
        )

        $("#"+idCanvas)
            .css("width","100%")
            .css("height","100%")
            .css("margin-top","-19px")
            .css("background-color","#f1f1f1")

    }catch(e){}
}

function limparCampos(strClass, strFocus){
    
    try{
        $("."+strClass).each(function(i){
            if( $(this).attr("type") == "select" ){
                $(this).val("").trigger("change")
            }else{
                $(this).val("")
            }
        })

        $("#"+strFocus).focus()
    }catch(e){}
}

/**
 * @description exibe mensagens padronizadas conforme a biblioteca toastr.js
 * @author Fernando Bino Machado
 * @param {*} msg - da mensagem que será exibida, quando omitido assume "" por defualt
 * @param {*} tipo - tipo de mensagem pode ser 1 para success 2 para warning 3 para info, quando omitido assume 3 por default
 * @param {*} tempo - para cada segundo utilize 1000, quando omitido assume 5000 por default
 */

function message(msg="Acao bem sucedida!!", tipo=3, tempo=5000){
    try{
        
        switch(tipo){
            case 1:
                toastr.success(msg,'Aviso',{timeOut:tempo})
                break;
            case 2:
                toastr.warning(msg,'Aviso',{timeOut:tempo})
                break;
            case 3:
                toastr.info(msg,'Informação',{timeOut:tempo})
                break;
            default:
                break;

        }

    }catch(e){}
}