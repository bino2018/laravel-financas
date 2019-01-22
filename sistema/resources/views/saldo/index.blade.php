@extends('sistema.sistema')
@section('navigate')

<div class="col-sm-10">
    <nav class="navbar navbar-default nav-dash panel-nav">
        <h5 class="text text-secondary titulo-dash">Extrato/ Saldo</h5>
    </nav>

    <div class="card card-cadastro panel-body">
        
        <div class="row">
            <div class="col-sm-8">
                <form action="/saldo" method="post">
                    <input type="hidden" id="tkn" name="_token" value="{!!csrf_token()!!}">
                    <div class="row">
                        <div class="col-sm-5">
                            <label class="label-form" for="valor">Informe o Período de Últimos Dias: </label>
                        </div>
                        <div class="col-sm-5">
                            <input type="number" id="dias" name="dias" min="1" max="365" class="form-control form-control-sm" value="{{$dias}}" placeholder="ultimos dias" required>
                        </div>
                        <div class="col-sm-2">    
                            <button class="btn btn-success btn-sm text-light">Consultar</button>      
                        </div>
                    </div>    
                </form>
            </div>
            <div class="col-sm-5">
                
            </div>
        </div>
        <hr>
        <div class="row">
            <div class="col-sm-12 panel-table">
                <div class="row">
                    <div class="col-sm-5">
                        <h5 class="text text-secondary">Transações <span class="badge badge-primary badge-sm">{{count($extrato['extrato'])}}</span></h5>
                    </div>
                    <div class="col-sm-7">
                        <h5 class="text text-secondary">Saldo</h5>
                    </div>
                </div>

                <div class="row">
                    <div class="col-sm-5" id="containerLancamentos">
                    
                        <div id="lancamentos">
                            @if( isset( $lancamentos ) && count($lancamentos) )
                                @include('saldo.lancamentos')
                            @endif
                        </div>
                    
                        <input type="hidden" id="totalPage" value="{{$totalPage}}">
                        <input type="hidden" id="pageAtual" value="{{$pageAtual}}">
                        <div class="row" id="verMais"></div>
                        
                        <div id="loader" class="row" style="display: none;">
                            <div class="col-sm-12">
                                <center><i class="fas fa-spinner fa-spin"></i></center>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-7">
                        
                        <table class="table table-sm table-bordered">
                            <input type="hidden" id="recebido" value="{{$extrato['detalhes']['recebido']}}">
                            <input type="hidden" id="gasto" value="{{$extrato['detalhes']['gasto']}}">
                            <input type="hidden" id="saldo" value="{{$extrato['detalhes']['saldo']}}">

                            <tr>
                                <td>Recebidos: {{$extrato['detalhes']['recebido']}}</td>
                                <td>Gastos: {{$extrato['detalhes']['gasto']}}</td>
                                <td>Saldo: {{$extrato['detalhes']['saldo']}}</td>
                            </tr>
                        </table>
                        <div class="row">
                            <div class="col-sm-12">
                                <canvas id="graf"></canvas>
                            </div>
                        </div>
                        
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>

@include('modal.contas')

<script src="{{asset('js/inview.js')}}"></script>
<script src="{{asset('js/Chart.js')}}"></script>
<script src="{{asset('js/saldo.js')}}"></script>

@stop