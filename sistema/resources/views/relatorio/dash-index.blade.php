<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Relatorio</title>
    
    <!-- Bootstrap -->
    <link rel="stylesheet" href="{{asset('/finance/css/app.css')}}">

    <!-- Geral Css -->
    <link rel="stylesheet" href="{{asset('/finance/css/geral.css')}}">

    <!-- Jquery -->
    <script src="{{asset('/finance/js/jsquery.js')}}"></script>

</head>
<body>
    <nav class="navbar navbar-default nav-dash panel-nav">
        <h5 class="text text-secondary titulo-dash">Relatório Lançamentos</h5>
    </nav>

    <div class="card card-cadastro panel-body">
        <div class="row">
            <div class="col-sm-12 panel-table">
                <table class="table table-sm table-bordered">
                    <thead>
                        <tr>
                            <td>Descrição</td>
                            <td>Valor</td>
                            <td>Data</td>
                            <td>Tipo</td>
                        </tr>
                    </thead>
                    <tbody>
                        @if( isset($lancamentos) && count($lancamentos) )
                            @foreach($lancamentos as $num => $val)
                                <tr>
                                    <td>{{$val->nmLancamento}}</td>
                                    <td>{{number_format($val->vlLancamento,2,'.','')}}</td>
                                    <td>{{date('d-m-Y', strtotime($val->dtLancamento))}}</td>
                                    <td>
                                        @if($val->tpLancamento == '1')
                                            Recebimento
                                        @else
                                            Pagamento
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        @endif
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</body>
</html>