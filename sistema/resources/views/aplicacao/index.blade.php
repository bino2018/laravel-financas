@extends('sistema.sistema')
@section('navigate')

<div class="col-sm-10">
    <nav class="navbar navbar-default nav-dash panel-nav">
        <h5 class="text text-secondary titulo-dash">Cálculo de Resgate de Aplicações</h5>
    </nav>

    <div class="card card-cadastro panel-body">
        
        <div class="row">
            <div class="col-sm-10">
                <h5 class="text text-secondary">Dados da Aplicação</h5>
                <div class="card card-cadastro">
                    <form id="fr-calculo" action="/calcular-aplicacao" method="post">
                        <input type="hidden" id="tkn" name="_token" value="{!!csrf_token()!!}">
                        <input type="hidden" id="codigo" name="codigo" value="0">

                        <div class="row">
                            <div class="col-sm-3">
                                <label class="label-form" for="valor">Capital Inicial (*)</label>
                            </div>
                            <div class="col-sm-7">
                                <input type="number" id="valor" name="valor" step="0.01" class="form-control form-control-sm" placeholder="" required>
                            </div>
                            <div class="col-sm-2">        
                                
                            </div>
                        </div>    

                        <div class="row">
                            <div class="col-sm-3">
                                <label class="label-form" for="dtinicio">Data Inicial</label>
                            </div>
                            <div class="col-sm-7">
                                <input type="date" name="dtinicio" id="dtinicio" class="form-control form-control-sm" required>
                            </div>
                            <div class="col-sm-2">      
                            </div>
                        </div> 

                        <div class="row">
                            <div class="col-sm-3">
                                <label class="label-form" for="dtfinal">Data Final</label>
                            </div>
                            <div class="col-sm-7">
                                <input type="date" name="dtfinal" id="dtfinal" class="form-control form-control-sm" required>
                            </div>
                            <div class="col-sm-2">      
                            </div>
                        </div> 

                        <div class="row">
                            <div class="col-sm-3">
                                <label class="label-form" for="taxa-ano">Taxa ao Ano (*)</label>
                            </div>
                            <div class="col-sm-7">
                                <input type="number" id="taxa-ano" name="taxa-ano" step="0.01" class="form-control form-control-sm" placeholder="% a.a" required>
                            </div>
                            <div class="col-sm-2">
                            </div>
                        </div>    

                        <div class="row">
                            <div class="col-sm-3">
                                <label class="label-form" for="taxa-corretora">Taxa Corretora (*)</label>
                            </div>
                            <div class="col-sm-7">
                                <input type="number" id="taxa-corretora" name="taxa-corretora" step="0.01" class="form-control form-control-sm" placeholder="%" required>
                            </div>
                            <div class="col-sm-2">        
                                
                            </div>
                        </div>    

                        <div class="row">
                            <div class="col-sm-3">
                                <label class="label-form" for="taxa-custodia">Taxa de Custódia (*)</label>
                            </div>
                            <div class="col-sm-7">
                                <input type="number" id="taxa-custodia" name="taxa-custodia" step="0.01" class="form-control form-control-sm" placeholder="%" required>
                            </div>
                            <div class="col-sm-2">        
                                
                            </div>
                        </div>    

                        <div class="row">
                            <div class="col-sm-3">
                                <label class="label-form" for="imposto">Imposto de Renda (*)</label>
                            </div>
                            <div class="col-sm-7">
                                <input type="number" id="imposto" name="imposto" step="0.01" class="form-control form-control-sm" placeholder="%" required>
                            </div>
                            <div class="col-sm-2">        
                                <button class="btn btn-success btn-sm text-light">Calcular</button>  
                            </div>
                        </div>    

                    </form>
                </div>
            </div>

            <div class="col-sm-2">
                
            </div>
        </div>
        <hr>
        
        <div class="row">
            <div class="col-sm-12 panel-table">
                <h5 class="text text-secondary">Detalhamento</h5>
                @if( isset($liquido) )
                    <table class="table table-sm table-bordered">
                        <tr>
                            <td>Valor Investido: </td>
                            <td>R$ {{$valor}}</td>
                        </tr>
                        <tr>
                            <td>Retorno Bruto: </td>
                            <td>R$ {{$bruto}}</td>
                        </tr>
                        <tr>
                            <td>Taxa de Custódia: </td>
                            <td>R$ {{$custodia}}</td>
                        </tr>
                        <tr>
                            <td>Taxa da Corretora: </td>
                            <td>R$ {{$corretora}}</td>
                        </tr>
                        <tr>
                            <td>Imposto de Renda: </td>
                            <td>R$ {{$imposto}}</td>
                        </tr>
                        <tr>
                            <td>Valor Liquido do Resgate: </td>
                            <td>R$ {{$liquido}}</td>
                        </tr>
                    </table>
                @endif
            </div>
        </div>
    </div>
</div>

<script src="{{asset('js/aplicacao.js')}}"></script>

@stop