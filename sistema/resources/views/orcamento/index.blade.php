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
                    <form action="{!! route('orcamento.salvar') !!}" method="post">
                        <div class="row">
                            <div class="col-sm-10">
                                <h5 class="text text-secondary">Dados</h5>
                            </div>
                            <div class="col-sm-2">
                                <button class="btn btn-success btn-sm text-light">Salvar Item</button>  
                            </div>
                        </div><hr>

                        <input type="hidden" id="tkn" name="_token" value="{!!csrf_token()!!}">
                        <input type="hidden" id="codigo" name="codigo" value="0">

                        <div class="row">
                            <div class="col-sm-6">
                                <div class="row">
                                    <div class="col-sm-5">
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
                                </div>            
                            </div>
                            <div class="col-sm-6">
                                <div class="row">
                                    <div class="col-sm-5">
                                        <label class="label-form" for="descricao">Descrição (*)</label>
                                    </div>
                                    <div class="col-sm-7">
                                        <input type="text" id="descricao" name="descricao" class="form-control form-control-sm" maxlength="30" placeholder="Descrição do Orçamento" required>
                                    </div>
                                </div>    
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-sm-6">
                                <div class="row">
                                    <div class="col-sm-5">
                                        <label class="label-form" for="valor">Valor (*)</label>
                                    </div>
                                    <div class="col-sm-7">
                                        <input type="number" id="valor" name="valor" step="0.01" class="form-control form-control-sm" placeholder="Valor Aproximando" required>
                                    </div>
                                </div>    
                            </div>
                            <div class="col-sm-6">
                                <div class="row">
                                    <div class="col-sm-5">
                                        <label class="label-form" for="dia">Dia Ref. (*)</label>
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
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-sm-6">
                                <div class="row">
                                    <div class="col-sm-5">
                                        <label class="label-form" for="dia">Validade</label>
                                    </div>
                                    <div class="col-sm-7">
                                        <input type="date" name="validade" id="validade" class="form-control form-control-sm">
                                    </div>
                                </div> 
                            </div>
                            <div class="col-sm-6">
                                <div class="row">
                                    <div class="col-sm-5">
                                        <label class="label-form" for="dia">Tipo (*)</label>
                                    </div>
                                    <div class="col-sm-7">
                                        <select name="tipo" id="tipo" class="form-control form-control-sm" required>
                                            <option value="">Selecione</option>
                                            <option value="1">Entradas</option>
                                            <option value="2">Saidas</option>
                                        </select>
                                    </div>
                                </div>    
                            </div>
                        </div>
                
                        <div class="row">
                            <div class="col-sm-6">
                                <div class="row">
                                    <div class="col-sm-5">
                                        <label class="label-form" for="ocorrencia">Ocorrência (*)</label>
                                    </div>
                                    <div class="col-sm-7">
                                        <select name="ocorrencia" id="ocorrencia" class="form-control form-control-sm" required>
                                            <option value="">Selecione</option>
                                            <option value="1">Apenas um lançamento no período</option>
                                            <option value="2">Varios lançamentos no período</option>
                                        </select>
                                    </div>
                                </div>    
                            </div>
                            <div class="col-sm-6">
                                <div class="row">
                                    <div class="col-sm-5">        
                                    </div>
                                    <div class="col-sm-7">      
                                        
                                    </div>
                                </div>    
                            </div>
                        </div>

                          
                    </form>
                </div>
            </div>

            <div class="col-sm-4">
                @if( isset($receita) )
                    <h5 class="text text-secondary">Receitas</h5>    

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

                    <br>
                    <form action="{!! route('conta.gerar') !!}" method="post">
                        <input type="hidden" name="_token" value="{!!csrf_token()!!}">
                        <button class="btn btn-default btn-sm bg-dark text-light form-control form-control-sm">Clique para Gerar/Atualizar Contas</button>
                    </form>
                    
                @endif
            </div>

        </div>
        <hr>
        <div class="row">
            <div class="col-sm-6 panel-table">
                
                <div class="row">
                    <div class="col-sm-7">
                        <h5 class="text text-secondary">Lista Itens Orçamento de Entradas</h5>
                    </div>
                    <div class="col-sm-5">
                        {!! $orcamentosEntradas->links() !!}
                    </div>
                </div>
                
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
                
                <div class="row">
                    <div class="col-sm-7">
                        <h5 class="text text-secondary">Lista Itens Orçamento de Saídas</h5>
                    </div>
                    <div class="col-sm-5">
                        {!! $orcamentosSaidas->links() !!}
                    </div>
                </div>

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

<input type="hidden" id="route-marcar" value="{!! route('orcamento.marcar') !!}">
<input type="hidden" id="route-excluir" value="{!! route('orcamento.deletar') !!}">

<script src="{{asset('finance/js/orcamento.js')}}"></script>

@stop