<div class="modal" style="display:none;">
    <div class="panel-modal">
        <div class="row">
            <div class="col-sm-11">
                <h5 class="text text-secondary titulo-dash">Distribuição de Contas</h5>
            </div>
            <div class="col-sm-1">
                <center><span id="close-modal" class="btn btn-default btn-sm bg-dark text-light">X</span></center>
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
                                            <td>Valor</td>
                                            <td>Vencimento</td>
                                            <td>Status</td>
                                            <td>Selecionar</td>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach( $contasReceber[$mes] as $conta => $valores )
                                            @if( is_numeric($conta) )
                                                @if( !isset($valores['vlLancamento']) || is_null($valores['vlLancamento']) )
                                           
                                                    @php
                                                        $jsonObj = json_encode($valores);
                                                    @endphp

                                                    <tr>
                                                        <td>{{$valores['dsConta']}}</td>
                                                        <td>{{$valores['valor']}}</td>
                                                        <td>{{$valores['vencimento']}}</td>
                                                        <td>{{$valores['situacao']}}</td>
                                                        <td>
                                                            <center>
                                                                <span class="btn btn-default btn-sm bg-dark text-light bt-seleciona" title="Selecionar Conta" data-cd="{{$valores['cdConta']}}" data-obj="{{$jsonObj}}">V</span>
                                                            </center>
                                                        </td>
                                                    </tr>
                                                    
                                                @endif
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
                                            <td>Valor</td>
                                            <td>Vencimento</td>
                                            <td>Status</td>
                                            <td>Selecionar</td>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach( $contasPagar[$mes] as $conta => $valores )
                                            @if( is_numeric($conta) )
                                                @if( !isset($valores['vlLancamento']) || is_null($valores['vlLancamento']) )
                                           
                                                    @php
                                                        $jsonObj = json_encode($valores);
                                                    @endphp

                                                    <tr>
                                                        <td>{{$valores['dsConta']}}</td>
                                                        <td>{{$valores['valor']}}</td>
                                                        <td>{{$valores['vencimento']}}</td>
                                                        <td>{{$valores['situacao']}}</td>
                                                        <td>
                                                            <center>
                                                                <span class="btn btn-default btn-sm bg-dark text-light bt-seleciona" title="Selecionar Conta" data-cd="{{$valores['cdConta']}}" data-obj="{{$jsonObj}}">V</span>
                                                            </center>
                                                        </td>
                                                    </tr>
                                                    
                                                @endif
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