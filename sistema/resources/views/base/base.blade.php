<html lang="pt-br">
<head>
    
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>SoftFinance - Online</title>
    @include('base.head')
    
</head>
<body>
    @include('base.nav')
    
    <div class="template bg-dark">
        <div class="sobre-template">
            @yield('content')
        </div>
    </div>

</body>
</html>