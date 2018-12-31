$(function(){
    
})

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

function redireciona(param){
    setTimeout(function(){
        window.location.href=param
    }, 1000)
}

function listaIdsCampos(){
    var lista = []
    
    $(".campo").each(function(lin){
        lista.push($(this).attr('id'))
    })
    
    return lista
}

function montaObj(){
    var obj = []

    $(".campo").each(function(lin){
        obj.push($(this).val())
    })
    
    return obj
}

function camposVazios(lista){
    var vazio = false

    lista.forEach(function(idCampo, num){
        if( $("#"+idCampo).val()  == "" ){
            vazio = true
        }
    })

    return vazio
}

function maiusculas(str){
    return str.toUpperCase()
}

function validaCpf(cpf){
    valido = false

    var cpfsInvalidos=[]

    for(var lt=0;lt<10;lt++){
        cpfsInvalidos.push(String(lt).repeat(11))
    }

    if(cpfsInvalidos.indexOf(cpf) == -1){

        op1 = cpf.substr(0,cpf.length - 2)
        soma1=0
        cont1=10
        
        for(var i=0; i<op1.length; i++){
            soma1 += op1[i] * cont1
            cont1 -= 1
        }
        
        ver1 = (soma1 * 10) % 11
        dg1 = ver1
        
        if(ver1 == 10){
            dg1=0		
        }
        if(dg1 == cpf[9]){
            soma2=0
            cont2=11
            for(var j=0; j<op1.length + 1; j++){
                soma2 += cpf[j] * cont2
                cont2 -= 1
            }
            ver2 = (soma2 * 10) % 11
            
            dg2 = ver2
            
            if(ver2 == 10){
                dg2=0
            }
            if(dg2 == cpf[10]){
                valido=true
            }
        }
    }

    return valido
}

function numeroDecimal(strNum){
    var num = NaN
    
    try{
        var floatNum = parseFloat(strNum)
        
        num = floatNum

    }catch(e){
        
    }
    
    return num

}

function testeNumeroDecimal(num){
    var teste = false

    try{
        var num = num / 10

        var testeNum = parseInt(num)
        
        if(testeNum > 0){
            teste = true
        }else{
            teste = false
        }

    }catch(e){
        teste = false
    }

    return teste
}

function numero(strnum){
    var nums=["0","1","2","3","4","5","6","7","8","9"]
    var strFiltro=""
    for(var i=0;i<strnum.length;i++){
        if(nums.indexOf(strnum[i]) != -1){
            strFiltro += strnum[i]
        }
    }
    return strFiltro
}

function recebeCep(dados){
    if(dados.logradouro != undefined && dados.logradouro != ""){
        $("#cplogradouro").val(dados.logradouro)
        $("#cpbairro").val(dados.bairro)
        $("#cpcidade").val(dados.localidade)
        $("#cpestado").val(dados.uf)
        $("#cpnumero").focus()
    }else{
        alert("Não foi possível localizar o Cep informado")
    }
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

function bloquear(lista, foc){
    $(".campo").prop("disabled", false)
    $("button").prop("disabled", false)

    lista.forEach(function(campo, num){
        $(campo).prop("disabled", true)
    })

    $(foc).focus()
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
            
    }catch(e){

    }
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

    }catch(e){

    }
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
    }catch(e){

    }
}

function smokeAlert(texto,tipo){
    try{
        $.smkAlert({
            text: texto,
            type: tipo,
            position: 'top-right',
            time: 3
        });
    }catch(e){}
}