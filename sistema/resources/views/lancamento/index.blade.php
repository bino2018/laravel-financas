@extends('sistema.sistema')
@section('navigate')

<div class="col-sm-10">
    <nav class="navbar navbar-default nav-dash panel-nav">
        <h5 class="text text-secondary titulo-dash">Lançamento de Recebimentos e Gastos</h5>
    </nav>

    <div class="card card-cadastro panel-body">
        
        <div class="row">
            <div class="col-sm-7">
                <div class="row">
                    <div class="col-sm-9">
                        <h5 class="text text-secondary">Novo Lançamento</h5>
                    </div>
                    <div class="col-sm-3">
                        
                    </div>
                </div>
                
                <br>

                <div class="card card-cadastro">
                    <form action="/salvar-lancamento" method="post">
                        <input type="hidden" id="tkn" name="_token" value="{!!csrf_token()!!}">
                        <input type="hidden" id="codigo" name="codigo" value="0">
                        
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
                            </div>
                        </div>
                        
                        <input type="hidden" id="conta" name="conta" value="0">

                        <div class="row">
                            <div class="col-sm-3">
                                <label class="label-form" for="descricao">Descrição (*)</label>
                            </div>
                            <div class="col-sm-7">
                                <input type="text" id="descricao" name="descricao" class="form-control form-control-sm" maxlength="30" placeholder="Descrição do Lançamento" required>
                            </div>
                            <div class="col-sm-2">        
                            </div>
                        </div>    

                        <div class="row">
                            <div class="col-sm-3">
                                <label class="label-form" for="valor">Valor (*)</label>
                            </div>
                            <div class="col-sm-7">
                                <input type="number" id="valor" name="valor" step="0.01" class="form-control form-control-sm" placeholder="Valor do Lançamento" required>
                            </div>
                            <div class="col-sm-2">    
                            </div>
                        </div>
                        
                        <hr> 

                        <div class="row">
                            <div class="col-sm-7">
                            </div>
                            <div class="col-sm-3">
                                <span id="open-modal" class="btn btn-default bg-dark text-light btn-sm">Pesquisar Conta</span>
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
                    <div class="col-sm-12">
                        <h5 class="text text-secondary">Filtrar Lançamentos</h5>
                    </div>
                </div>

                <br>

                <div class="card card-cadastro">
                    <form action="/lancamentos" method="post">
                        <input type="hidden" id="tkn" name="_token" value="{!!csrf_token()!!}">
                        
                        <div class="row">
                            <div class="col-sm-5">
                                <label class="label-form" for="valor">Data Inicial (*)</label>
                            </div>
                            <div class="col-sm-7">
                                <input type="date" name="dtinicio" id="dtinicio" class="form-control form-control-sm">
                            </div>
                        </div>    

                        <div class="row">
                            <div class="col-sm-5">
                                <label class="label-form" for="valor">Data Final (*)</label>
                            </div>
                            <div class="col-sm-7">
                                <input type="date" name="dtfinal" id="dtfinal" class="form-control form-control-sm">
                            </div>
                        </div>    

                        <div class="row">
                        <div class="col-sm-5">
                                <label class="label-form" for="filtro-descricao">Descrição</label>
                            </div>
                            <div class="col-sm-7">
                                <input type="text" id="filtro-descricao" name="descricao" class="form-control form-control-sm" maxlength="30" placeholder="Descrição do Lançamento">
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-sm-5">
                                <label class="label-form" for="dia">Tipo (*)</label>
                            </div>
                            <div class="col-sm-7">
                                <select name="tipo" id="filtro-tipo" class="form-control form-control-sm">
                                    <option value="">Selecione</option>
                                    <option value="1">Entradas</option>
                                    <option value="2">Saidas</option>
                                </select>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-sm-5">
                            </div>
                            <div class="col-sm-7">    
                                <button class="btn btn-success btn-sm text-light form-control form-control-sm">Filtrar</button>      
                            </div>
                            
                        </div>
                    </form>
                </div>
            </div>

            </div>
        </div>
        <hr>
        <div class="row">

            @if( isset($parametros) && !empty($parametros) )
                <input type="hidden" id="parametros" value="{{$parametros}}">
            @else
                <input type="hidden" id="parametros" value="">
            @endif

            <div class="col-sm-12 panel-table">

                <div class="row">
                    <div class="col-sm-8">
                        <h5 class="text text-secondary">Ultimos Lançamentos</h5>
                    </div>
                    <div class="col-sm-4">
                        <table class="table table-sm table-bordered">
                            <tr>
                                <td>Cálculo Soma:</td>
                                <td>R$ {{$soma}}</td>
                            </tr>
                        </table>
                    </div>
                </div>

                <table class="table table-sm table-bordered">
                    <thead>
                        <tr>
                            <td>Descrição</td>
                            <td>Valor</td>
                            <td>Data</td>
                            <td>Tipo</td>
                            <td>Acão</td>
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
                                    <td>
                                        <center>
                                            <span class="btn btn-default btn-sm bg-dark text-light bt-excluir" title="Remover Lançamento" data-cd="{{$val->cdLancamento}}">X</span>
                                        </center>
                                    </td>
                                </tr>
                            @endforeach
                        @endif
                    </tbody>
                </table>
                <div class="row">
                    <div class="col-sm-12">
                        {!! $lancamentos->links() !!}
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>

@include('modal.contas')

<script src="{{asset('js/lancamento.js')}}"></script>

@stop