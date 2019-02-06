@extends('base.base')
@section('content')
<!-- Tela de Login -->
    
    <div id="dv-login" class="row">
        <div class="col-sm-4 col-xs-1">
        </div>
        <div class="col-sm-4 col-xs-10">
            <form class="form border" action="{!! route('home.valida-login') !!}" method="post">
                <input type="hidden" name="_token" value="{!!csrf_token()!!}">
                <div class="row">
                    <div class="col-sm-12 col-xs-12">
                        <label>Usuário</label>
                        <input type="text" name="usuario" class="form-control" required="" placeholder="Nome do Usuário">
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-12 col-xs-12">
                        <label>Senha</label>
                        <input type="password" name="senha" class="form-control" required="" placeholder="Senha do Usuário">
                    </div>
                </div>
                <br>
                <div class="row">
                    <div class="col-sm-12 col-xs-12">
                        <button class="btn btn-defaul btn-sm form-control bg-dark text-light" style="font-size: 16pt;">
                            Acessar Sistema
                            <i class="fas fa-key" style="font-size: 20pt; float: right; margin-top 0pt;"></i>
                        </button>
                    </div>
                </div>
            </form>
        </div>
        <div class="col-sm-1 col-xs-1">
        </div>
    </div>
        
@stop
