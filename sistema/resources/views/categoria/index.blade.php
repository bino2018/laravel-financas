@extends('sistema.sistema')
@section('navigate')

<div class="col-sm-10">
    <nav class="navbar navbar-default nav-dash panel-nav">
        <h5 class="text text-secondary titulo-dash">Categorias</h5>
    </nav>

    <div class="card card-cadastro panel-body">
        
        <div class="row">
            <div class="col-sm-7">
                <h5 class="text text-secondary">Cadastro</h5>
                <div class="card card-cadastro">
                    <form action="/salvar-categoria" method="post">
                        <input type="hidden" id="tkn" name="_token" value="{!!csrf_token()!!}">
                        <input type="hidden" id="codigo" name="codigo" value="0">

                        <div class="row">
                            <div class="col-sm-3">
                                <label class="label-form" for="categoria">Categoria (*)</label>
                            </div>
                            <div class="col-sm-7">
                                <input type="text" id="categoria" name="categoria" class="form-control form-control-sm" placeholder="Nome da Categoria" required>
                            </div>
                            <div class="col-sm-2">
                                <button class="btn btn-success btn-sm text-light">Salvar</button>
                            </div>
                        </div>            
                    </form>
                </div>
            </div>

            <div class="col-sm-5">
                <div class="row">
                    <div class="col-sm-8">
                        <!--<h5 class="text text-secondary">Total Categorias: count($data['categorias'])</h5>-->
                    </div>
                    <div class="col-sm-4">
                        <a href="/orcamentos" class="btn btn-default btn-sm bg-dark text-light">Orçamentos</a>
                    </div>
                </div>    
            </div>
        </div>

        <!-- <hr>
        <input type="hidden" id="dados" value="{{json_encode($data['categorias'])}}">
        <div class="row">
            <div class="col-sm-12">
                <canvas id="grafico">

                </canvas>
            </div>
        </div> -->
        
        <hr>
        <div class="row">
            <div class="col-sm-12 panel-table">
                <h5 class="text text-secondary">Listagem</h5>

                <table class="table table-sm table-bordered">
                    <thead>
                        <tr>
                            <td>Categoria</td>
                            <td>Recebido</td>
                            <td>Gasto</td>
                            <td>Saldo</td>
                            <td>Status</td>
                            <td>Ações</td>
                        </tr>
                    </thead>
                    <tbody>
                        @if( isset($data['categorias']) )
                            @foreach( $data['categorias'] as $num => $val )
                                @if( $val['cdCategoria'] == 0 )
                                    <tr class='dados-info'>
                                @else
                                    <tr>
                                @endif
                                    <td>{{$val['nmCategoria']}}</td>
                                    @php
                                        $checado = "";
                                        $dataStatus = "2";
                                        $classe = "lb-status-off";
                                        $title = "Altera o status para On";
                                        $text = "Off";

                                        if($val['cdStatus'] == "1"){
                                            $checado = "checked";
                                            $dataStatus = "1";
                                            $classe = "lb-status-on";
                                            $title = "Altera o status para Off";
                                            $text = "On";
                                        }
                                    @endphp

                                    <td>{{$val['entradas']}}</td>
                                    <td>{{$val['saidas']}}</td>
                                    <td>{{$val['saldo']}}</td>

                                    <td>
                                        @if( $val['cdCategoria'] != 0 )
                                            <center>
                                                <input id="check{{$num}}" type="checkbox" class="checks" data-cd="{{$val['cdCategoria']}}" data-st="{{$dataStatus}}" {{$checado}} style="display: none;">
                                                <label id="lab{{$num}}" class="{{$classe}}" for="check{{$num}}" title="{{$title}}">{{$text}}</label>
                                            </center>
                                        @endif
                                    </td>
                                    <td>
                                        @if( $val['cdCategoria'] != 0 )
                                            <center>
                                                <span class="btn btn-default btn-sm bg-dark text-light bt-editar" title="Editar Categoria" data-cd="{{$val['cdCategoria']}}" data-nm="{{$val['nmCategoria']}}">Editar</span>
                                                <span class="btn btn-default btn-sm bg-dark text-light bt-excluir" title="Remover Categoria" data-cd="{{$val['cdCategoria']}}">X</span>
                                            </center>
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
</div>

<script src="{{asset('js/Chart.js')}}"></script>
<script src="{{asset('js/categoria.js')}}"></script>

@stop