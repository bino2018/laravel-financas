<html lang="pt-br">
<head>
    
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>SoftFinance - Laravel</title>
    @include('base.head')
    
</head>
<body>

    @include('base.nav')
    
    <div class="template bg-dark">
        <div class="sobre-template">
            @yield('content')
        </div>
    </div>

    <div class="row navbar navbar-expand-sm navbar-dark bg-dark nav-rodape">
        <!-- <div class="col-sm-4">
            <h4>Soft Bino - Informática</h4>
            <h6>fernando.bino.machado@gmail.com</h6>
            <h6>programemachado@gmail.com</h6>
            <h6>programe2machado@gmail.com</h6>
        </div>
        <div class="col-sm-4">
        </div>
        <div class="col-sm-4">
            <h4>GitHub</h4>
            <h6><a href="https://github.com/bino2018" target="_blank" class="dropdown-item icone-footer"> <i class="fas fa-church"></i> Repositorios GitHub</a></h6>
        </div> -->
    </div>

</body>

@include('helper.alerts')

</html>