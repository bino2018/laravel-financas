@extends('sistema.sistema')
@section('navigate')

<div class="col-sm-10">
    <nav class="navbar navbar-default nav-dash panel-nav">
        <h5 class="text text-secondary titulo-dash">Planejamento de Contas</h5>
    </nav>

    <div class="card card-cadastro panel-body">
        
        <div class="row">
            <div class="col-sm-8">
                <h5 class="text text-secondary">Cadastro Manual</h5>
                <div class="card card-cadastro">
                    <form action="/salvar-conta" method="post">
                        <input type="hidden" id="tkn" name="_token" value="{!!csrf_token()!!}">
                        <input type="hidden" id="codigo" name="codigo" value="0">

                        <div class="row">
                            <div class="col-sm-3">
                                <label class="label-form" for="dia">Tipo (*)</label>
                            </div>
                            <div class="col-sm-7">
                                <select name="tipo" id="tipo" class="form-control form-control-sm" required>
                                    <option value="">Selecione</option>
                                    <option value="1">Recebimento</option>
                                    <option value="2">Pagamento</option>
                                </select>
                            </div>
                            <div class="col-sm-2">      
                            </div>
                        </div>    

                        <input type="hidden" id="orcamento" name="orcamento" value="0">

                        <div class="row">
                            <div class="col-sm-3">
                                <label class="label-form" for="descricao">Descrição (*)</label>
                            </div>
                            <div class="col-sm-7">
                                <input type="text" id="descricao" name="descricao" class="form-control form-control-sm" placeholder="Descrição da Conta" required>
                            </div>
                            <div class="col-sm-2">        
                            </div>
                        </div>    

                        <div class="row">
                            <div class="col-sm-3">
                                <label class="label-form" for="valor">Valor (*)</label>
                            </div>
                            <div class="col-sm-7">
                                <input type="number" id="valor" name="valor" step="0.01" class="form-control form-control-sm" placeholder="Valor" required>
                            </div>
                            <div class="col-sm-2">        
                            </div>
                        </div>    

                        <div class="row">
                            <div class="col-sm-3">
                                <label class="label-form" for="vencimento">Vencimento (*)</label>
                            </div>
                            <div class="col-sm-7">
                                <input type="date" name="vencimento" id="vencimento" class="form-control form-control-sm" required>
                            </div>
                            <div class="col-sm-2">      
                                <button class="btn btn-success btn-sm text-light">Salvar</button>  
                            </div>
                        </div>            

                    </form>
                </div>
            </div>

            <div class="col-sm-4">
                <div class="row">
                    <div class="col-sm-4">
                        <h5 class="text text-secondary">Resumo</h5>
                    </div>
                    <div class="col-sm-8">
                        <div class="row">
                            <div class="col-sm-5">
                                <form action="/gerar-contas" method="post">
                                    <input type="hidden" name="_token" value="{!!csrf_token()!!}">
                                    <button class="btn btn-default btn-sm bg-dark text-light">Gerar Contas</button>  
                                </form>
                            </div>
                            <div class="col-sm-5">
                                <a href="/lancamentos" class="btn btn-default btn-sm bg-dark text-light">Lançamentos</a>
                            </div>
                        </div>
                    </div>
                </div> 
                <br>
                <div class="row">
                    <div class="col-sm-12">
                        <table class="table table-sm tb-dados">
                            @php
                                $valorPrevisao = $resumo['pendentes']['pendentes']['previsao'] + $resumo['saldo']['detalhes']['saldo'];
                                $valorPrevisao = number_format($valorPrevisao,2,'.','')
                            @endphp
                            <tr>
                                <td>Total Recebido: </td>
                                <td>R$ {{$resumo['saldo']['detalhes']['recebido']}}</td>
                            </tr>
                            <tr>
                                <td>Total Gasto: </td>
                                <td>R$ {{$resumo['saldo']['detalhes']['gasto']}}</td>
                            </tr>
                            <tr>
                                <td>Saldo Geral: </td>
                                <td>R$ {{$resumo['saldo']['detalhes']['saldo']}}</td>
                            </tr>
                            {{--<tr>
                                <td>Total A Receber: </td>
                                <td>R$ {{$resumo['pendentes']['pendentes']['a_receber']}}</td>
                            </tr>
                            <tr>
                                <td>Total A Pagar: </td>
                                <td>R$ {{$resumo['pendentes']['pendentes']['a_pagar']}}</td>
                            </tr>
                            <tr>
                                <td>Previsão de Saldo: </td>
                                <td>R$ {{$valorPrevisao}}</td>
                            </tr>--}}
                        </table>
                    </div>
                </div>

            </div>
        </div>
        <hr>
        <div class="row">
            <div class="col-sm-6 panel-table">
                <h5 class="text text-secondary">Lista de Recebimentos</h5><br>

                @if( isset($contasReceber) && count($contasReceber) )
                    @foreach( $contasReceber as $mes => $detalhes )
                        <div id="tbreceber{{$mes}}" class="lista-contas">
                            <table class="table table-bordered table-sm">
                                <tbody>
                                    <tr class="table-secondary">
                                        <td class="marca-mes"><b>{{$mes}}</b></td>
                                        
                                        @if( isset($detalhes['total']) )
                                            <td>Nº Total: {{$detalhes['total']}}</td>
                                        @else
                                            <td>Nº Total: 0</td>
                                        @endif

                                        @if( isset($detalhes['ok']) )
                                            <td>Recebido: {{$detalhes['ok']}}</td>
                                        @else
                                            <td>Recebido: 0</td>
                                        @endif

                                        @if( isset($detalhes['pendente']) )
                                            <td>A Receber: {{$detalhes['pendente']}}</td>
                                        @else
                                            <td>A Receber: 0</td>
                                        @endif
                                        

                                        <td style="width: 40px;">
                                            <center><i class="icone-resumo" data-id="rc{{$mes}}{{$detalhes['ano']}}">+</i></center>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>

                            <div id="rc{{$mes}}{{$detalhes['ano']}}" class="sub-lista-contas" style="display: none;">
                                <table class="table table-sm table-bordered">
                                    <thead>
                                        <tr>
                                            <td>Descrição</td>
                                            <td>Valor Orçado</td>
                                            <td>Valor Lançado</td>
                                            <td>Vencimento</td>
                                            <td>Status</td>
                                            <td> </td>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach( $contasReceber[$mes] as $conta => $valores )
                                            @if( is_numeric($conta) )
                                                <tr>
                                                    <td>{{$valores['dsConta']}}</td>
                                                    <td>{{$valores['valor']}}</td>
                                                    <td>{{$valores['valorLancado']}}</td>
                                                    <td>{{$valores['vencimento']}}</td>
                                                    <td>{{$valores['situacao']}}</td>
                                                    <td>
                                                        <center>
                                                            <span class="btn btn-default btn-sm bg-dark text-light bt-excluir" title="Remover Conta" data-cd="{{$valores['cdConta']}}">X</span>
                                                        </center>
                                                    </td>
                                                </tr>
                                            @endif
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    @endforeach
                @endif
                
            </div>

            <div class="col-sm-6 panel-table">
                <h5 class="text text-secondary">Lista de Pagamentos</h5><br>

                @if( isset($contasPagar) && count($contasPagar) )
                    @foreach( $contasPagar as $mes => $detalhes )
                        <div id="tbpagar{{$mes}}" class="lista-contas">
                            <table class="table table-bordered table-sm">
                                <tbody>
                                    <tr class="table-secondary">
                                        <td class="marca-mes"><b>{{$mes}}</b></td>
                                        
                                        @if( isset($detalhes['total']) )
                                            <td>Nº Total: {{$detalhes['total']}}</td>
                                        @else
                                            <td>Nº Total: 0</td>
                                        @endif

                                        @if( isset($detalhes['ok']) )
                                            <td>Pago: {{$detalhes['ok']}}</td>
                                        @else
                                            <td>Pago: 0</td>
                                        @endif

                                        @if( isset($detalhes['pendente']) )
                                            <td>A Pagar: {{$detalhes['pendente']}}</td>
                                        @else
                                            <td>A Pagar: 0</td>
                                        @endif

                                        <td style="width: 40px;">
                                            <center><i class="icone-resumo" data-id="pg{{$mes}}{{$detalhes['ano']}}">+</i></center>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>

                            <div id="pg{{$mes}}{{$detalhes['ano']}}" class="sub-lista-contas" style="display: none;">
                                <table class="table table-sm table-bordered">
                                    <thead>
                                        <tr>
                                            <td>Descrição</td>
                                            <td>Valor Orçado</td>
                                            <td>Valor Lançado</td>
                                            <td>Vencimento</td>
                                            <td>Status</td>
                                            <td> </td>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach( $contasPagar[$mes] as $conta => $valores )
                                            @if( is_numeric($conta) )
                                                <tr>
                                                    <td>{{$valores['dsConta']}}</td>
                                                    <td>{{$valores['valor']}}</td>
                                                    <td>{{$valores['valorLancado']}}</td>
                                                    <td>{{$valores['vencimento']}}</td>
                                                    <td>{{$valores['situacao']}}</td>
                                                    <td>
                                                        <center>
                                                            <span class="btn btn-default btn-sm bg-dark text-light bt-excluir" title="Remover Conta" data-cd="{{$valores['cdConta']}}">X</span>
                                                        </center>
                                                    </td>
                                                </tr>
                                            @endif
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    @endforeach
                @endif
            </div>
        </div>
    </div>
</div>

<script src="{{asset('js/conta.js')}}"></script>

@stop