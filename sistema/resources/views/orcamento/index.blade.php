@extends('sistema.sistema')
@section('navigate')

<div class="col-sm-10">
    <nav class="navbar navbar-default nav-dash panel-nav">
        <h5 class="text text-secondary titulo-dash">Orçamento Mensal</h5>
    </nav>

    <div class="card card-cadastro panel-body">
        
        <div class="row">
            <div class="col-sm-8">
                <h5 class="text text-secondary">Cadastro</h5>
                <div class="card card-cadastro">
                    <form action="/salvar-orcamento" method="post">
                        <input type="hidden" id="tkn" name="_token" value="{!!csrf_token()!!}">
                        <input type="hidden" id="codigo" name="codigo" value="0">

                        <div class="row">
                            <div class="col-sm-3">
                                <label class="label-form" for="categoria">Categoria (*)</label>
                            </div>
                            <div class="col-sm-7">
                                <select name="categoria" id="categoria" class="form-control form-control-sm" required>
                                    <option value="">Selecione</option>
                                    @if( isset($categorias) && count($categorias) )
                                        @foreach( $categorias as $num => $val )
                                            <option value="{{$val->cdCategoria}}">{{$val->nmCategoria}}</option>
                                        @endforeach
                                    @endif
                                </select>    
                            </div>
                            <div class="col-sm-2">        
                            </div>
                        </div>            

                        <div class="row">
                            <div class="col-sm-3">
                                <label class="label-form" for="descricao">Descrição (*)</label>
                            </div>
                            <div class="col-sm-7">
                                <input type="text" id="descricao" name="descricao" class="form-control form-control-sm" maxlength="30" placeholder="Descrição do Orçamento" required>
                            </div>
                            <div class="col-sm-2">        
                            </div>
                        </div>    

                        <div class="row">
                            <div class="col-sm-3">
                                <label class="label-form" for="valor">Valor (*)</label>
                            </div>
                            <div class="col-sm-7">
                                <input type="number" id="valor" name="valor" step="0.01" class="form-control form-control-sm" placeholder="Valor Aproximando" required>
                            </div>
                            <div class="col-sm-2">        
                            </div>
                        </div>    

                        <div class="row">
                            <div class="col-sm-3">
                                <label class="label-form" for="dia">Dia Referencia (*)</label>
                            </div>
                            <div class="col-sm-7">
                                <select name="dia" id="dia" class="form-control form-control-sm" required>
                                    <option value="">Selecione</option>
                                    @if( isset($dias) && count($dias) )
                                        @foreach( $dias as $dia => $val )
                                            <option value="{{$val}}">{{$val}}</option>
                                        @endforeach
                                    @endif
                                </select>
                            </div>
                            <div class="col-sm-2">      
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-sm-3">
                                <label class="label-form" for="dia">Data de Validade</label>
                            </div>
                            <div class="col-sm-7">
                                <input type="date" name="validade" id="validade" class="form-control form-control-sm">
                            </div>
                            <div class="col-sm-2">      
                            </div>
                        </div> 

                        <div class="row">
                            <div class="col-sm-3">
                                <label class="label-form" for="dia">Tipo (*)</label>
                            </div>
                            <div class="col-sm-7">
                                <select name="tipo" id="tipo" class="form-control form-control-sm" required>
                                    <option value="">Selecione</option>
                                    <option value="1">Entradas</option>
                                    <option value="2">Saidas</option>
                                </select>
                            </div>
                            <div class="col-sm-2">      
                                <button class="btn btn-success btn-sm text-light">Salvar</button>  
                            </div>
                        </div>    
                    </form>
                </div>
            </div>

            <div class="col-sm-4">
                @if( isset($receita) )
                    
                    <div class="row">
                        <div class="col-sm-7">
                            <h5 class="text text-secondary">Receita Mensal</h5>
                        </div>
                        <div class="col-sm-5">
                            <form action="/gerar-contas" method="post">
                                <input type="hidden" name="_token" value="{!!csrf_token()!!}">
                                <button class="btn btn-default btn-sm bg-dark text-light">Gerar Contas</button>  
                            </form>
                        </div>
                    </div>    

                    <div class="card card-cadastro">
                        <table class="table table-bordered table-sm">
                            <tr>
                                <td>Entradas: </td>
                                <td>R$ {{$receita['entradas']}}</td>
                            </tr>
                            <tr>
                                <td>Saídas: </td>
                                <td>R$ {{$receita['saidas']}}</td>
                            </tr>
                            <tr>
                                <td>Saldo: </td>
                                <td>R$ {{$receita['saldo']}}</td>
                            </tr>
                        </table>
                    </div>
                @endif
            </div>
        </div>
        <hr>
        <div class="row">
            <div class="col-sm-6 panel-table">
                <h5 class="text text-secondary">Lista Itens Orçamento de Entradas</h5>

                <table class="table table-sm table-bordered">
                    <thead>
                        <tr>
                            <td>Descrição - Dia</td>
                            <td>Valor</td>
                            <td>Status</td>
                            <td>Ações</td>
                        </tr>
                    </thead>
                    <tbody>
                        @if( isset($orcamentosEntradas) && count($orcamentosEntradas) )                        
                            @foreach( $orcamentosEntradas as $num => $val )
                                <tr>
                                    <td>{{$val->nmOrcamento}} - {{$val->dia}}</td>
                                    <td>{{$val->vlOrcamento}}</td>

                                    @php
                                        $checado = "";
                                        $dataStatus = "2";
                                        $classe = "lb-status-off";
                                        $title = "Altera o status para On";
                                        $text = "Off";

                                        if($val->cdStatus == "1"){
                                            $checado = "checked";
                                            $dataStatus = "1";
                                            $classe = "lb-status-on";
                                            $title = "Altera o status para Off";
                                            $text = "On";
                                        }
                                    @endphp
                                    <td>
                                        <center>
                                            <input id="check{{$val->cdOrcamento}}" type="checkbox" class="checks" data-cd="{{$val->cdOrcamento}}" data-st="{{$dataStatus}}" {{$checado}} style="display: none;">
                                            <label id="lab{{$val->cdOrcamento}}" class="{{$classe}}" for="check{{$val->cdOrcamento}}" title="{{$title}}">{{$text}}</label>
                                        </center>
                                    </td>
                                    
                                    @php
                                        $jsonObj = json_encode($val);
                                    @endphp

                                    <td>
                                        <center>
                                            <span class="btn btn-default btn-sm bg-dark text-light bt-editar" title="Editar Orcamento" data-cd="{{$val->cdOrcamento}}" data-obj="{{$jsonObj}}">V</span>
                                            <span class="btn btn-default btn-sm bg-dark text-light bt-excluir" title="Remover Orcamento" data-cd="{{$val->cdOrcamento}}">X</span>
                                        </center>
                                    </td>
                                </tr>
                            @endforeach
                        @endif
                    </tbody>
                </table>
            </div>

            <div class="col-sm-6 panel-table">
                <h5 class="text text-secondary">Lista Itens Orçamento de Saídas</h5>

                <table class="table table-sm table-bordered">
                    <thead>
                        <tr>
                            <td>Descrição - Dia</td>
                            <td>Valor</td>
                            <td>Status</td>
                            <td>Ações</td>
                        </tr>
                    </thead>
                    <tbody>
                        @if( isset($orcamentosSaidas) && count($orcamentosSaidas) )
                            @foreach( $orcamentosSaidas as $num => $val )
                                <tr>
                                    <td>{{$val->nmOrcamento}} - {{$val->dia}}</td>
                                    <td>{{$val->vlOrcamento}}</td>

                                    @php
                                        $checado = "";
                                        $dataStatus = "2";
                                        $classe = "lb-status-off";
                                        $title = "Altera o status para On";
                                        $text = "Off";

                                        if($val->cdStatus == "1"){
                                            $checado = "checked";
                                            $dataStatus = "1";
                                            $classe = "lb-status-on";
                                            $title = "Altera o status para Off";
                                            $text = "On";
                                        }
                                    @endphp
                                    <td>
                                        <center>
                                            <input id="check{{$val->cdOrcamento}}" type="checkbox" class="checks" data-cd="{{$val->cdOrcamento}}" data-st="{{$dataStatus}}" {{$checado}} style="display: none;">
                                            <label id="lab{{$val->cdOrcamento}}" class="{{$classe}}" for="check{{$val->cdOrcamento}}" title="{{$title}}">{{$text}}</label>
                                        </center>
                                    </td>
                                    
                                    @php
                                        $jsonObj = json_encode($val);
                                    @endphp

                                    <td>
                                        <center>
                                            <span class="btn btn-default btn-sm bg-dark text-light bt-editar" title="Editar Orcamento" data-cd="{{$val->cdOrcamento}}" data-obj="{{$jsonObj}}">V</span>
                                            <span class="btn btn-default btn-sm bg-dark text-light bt-excluir" title="Remover Orcamento" data-cd="{{$val->cdOrcamento}}">X</span>
                                        </center>
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

<script src="{{asset('js/orcamento.js')}}"></script>

@stop