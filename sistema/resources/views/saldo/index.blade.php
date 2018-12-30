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
                            <input type="number" id="dias" name="dias" min="1" max="365" class="form-control form-control-sm" placeholder="ultimos dias" required>
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
                        <h5 class="text text-secondary">Transações</h5>
                    </div>
                    <div class="col-sm-7">
                        <table class="table table-sm table-bordered">
                            <tr>
                                <td>Recebidos: {{$detalhes['recebido']}}</td>
                                <td>Gastos: {{$detalhes['gasto']}}</td>
                                <td>Saldo: {{$detalhes['saldo']}}</td>
                            </tr>
                        </table>
                    </div>
                </div>
                

                <table class="table table-sm table-bordered">
                    <thead>
                        <tr>
                            <td>Data</td>
                            <td>Valor</td>
                            <td>Descrição</td>
                            <td>Operação</td>
                        </tr>
                    </thead>
                    <tbody>
                        @if( isset($extrato) && count($extrato) )
                            @foreach( $extrato as $num => $val )
                                <tr>
                                    <td>{{ date('d-m-Y', strtotime($val['dtLancamento'])) }}</td>
                                    <td>{{ number_format($val['vlLancamento'],2,'.','') }}</td>
                                    <td>{{ $val['nmLancamento'] }}</td>
                                    <td>
                                        @if( $val['tpLancamento'] == '1' )
                                            <span class="text text-success"><i>Recebimento</i></span>
                                        @else
                                            <span class="text text-danger"><i>Pagamento</i></span>
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

@include('modal.contas')

<script src="{{asset('js/saldo.js')}}"></script>

@stop