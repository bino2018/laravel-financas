@extends('sistema.sistema')
@section('navigate')

<div class="col-sm-10">
    <nav class="navbar navbar-default nav-dash panel-nav">
        <h5 class="text text-secondary titulo-dash">Relatório Lançamentos</h5>
        <a id="pdfDashInicio" href="{!! route('relatorio.index-pdf') !!}" target="_blank" class="btn btn-default btn-sm text text-secondary pull-right"><i class="fas fa-file-pdf"></i> <b>Pdf</b></a>
    </nav>
    
    <input type="hidden" id="tkn" name="_token" value="{!!csrf_token()!!}">

    @if( isset($parametros) && !empty($parametros) )
        <input type="hidden" id="parametros" value="{{$parametros}}">
    @else
        <input type="hidden" id="parametros" value="">
    @endif

    <div class="card card-cadastro panel-body">
        <div class="row">
            <div class="col-sm-10 panel-table">
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
            <div class="col-sm-2 menu-filtro">
                <form id="formFiltro" action="{!! route('relatorio.index') !!}" method="post">
                    <input type="hidden" id="tkn" name="_token" value="{!!csrf_token()!!}">
                    
                    <div class="row">
                        <div class="col-sm-12">
                            <label for="valor">Data Inicial (*)</label>
                            <input type="date" name="dtinicio" id="dtinicio" class="form-control form-control-sm">
                        </div>
                    </div>    

                    <div class="row">
                        <div class="col-sm-12">
                            <label for="valor">Data Final (*)</label>
                            <input type="date" name="dtfinal" id="dtfinal" class="form-control form-control-sm">
                        </div>
                    </div>    

                    <div class="row">
                        <div class="col-sm-12">
                            <label for="filtro-descricao">Descrição</label>
                            <input type="text" id="filtro-descricao" name="descricao" class="form-control form-control-sm" maxlength="30" placeholder="Descrição do Lançamento">
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-sm-12">
                            <label for="dia">Tipo (*)</label>
                            <select name="tipo" id="filtro-tipo" class="form-control form-control-sm">
                                <option value="">Selecione</option>
                                <option value="1">Entradas</option>
                                <option value="2">Saidas</option>
                            </select>
                        </div>
                    </div>
                    <br>
                    <div class="row">
                        <div class="col-sm-12">    
                            <button class="btn btn-success btn-sm text-light form-control form-control-sm">Filtrar</button>      
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script src="{{asset('finance/js/FileSaver.js')}}"></script>
<script src="{{asset('finance/js/Chart.js')}}"></script>
<script src="{{asset('finance/js/relatorio.js')}}"></script>

@stop