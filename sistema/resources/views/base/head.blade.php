@php
    //trata url
    $request_server = $_SERVER['SERVER_NAME'];
@endphp

<!-- Bootstrap -->
<link rel="stylesheet" href="{{asset('css/app.css')}}">

<!-- Geral Css -->
<link rel="stylesheet" href="{{asset('css/geral.css')}}">

<!-- Jquery -->
<script src="{{asset('js/jsquery.js')}}"></script>

<!-- Geral Js -->
<script src="{{asset('js/geral.js')}}"></script>

<!-- Select2 -->
<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/css/select2.min.css" rel="stylesheet" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/js/select2.min.js"></script>

<!-- Alert -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/css/toastr.min.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/js/toastr.min.js"></script>

@if( $request_server == '127.0.0.1' || $request_server == 'localhost' )
    
    <link rel="stylesheet" href="{{asset('css/font-awesome/css/all.css')}}">
@else
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.6.3/css/all.css" integrity="sha384-UHRtZLI+pbxtHCWp1t77Bi1L4ZtiqrqD80Kn4Z8NTSRyMA2Fd33n5dQ8lWUE00s/" crossorigin="anonymous">

@endif
