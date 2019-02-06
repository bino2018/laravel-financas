<?php

//Devido ao tamanho do projeto as rotas foram feitas dessa maneira
//Porém recomenda-se criar as rotas em arquivos separados conforme a necessidade do projeto

//HOME
    Route::group(['as'=>'home.', 'prefix'=>'finance/home'], function(){
        //inicio
        Route::get('login', ['as'=>'login','uses'=>'Home@home']);
        //View Login
        Route::get('fazer-login', ['as'=>'ir-login','uses'=>'Home@viewLogin']);
        //Valida login
        Route::post('valida-login',['as'=>'valida-login','uses'=>'Home@validaLogin']);
    });

//SISTEMA
    Route::group(['as'=>'panel.', 'prefix'=>'finance/panel'], function(){
        //acessa o sistema
        Route::get('menu', ['as'=>'ir-panel','uses'=>'Home@sistema'])->middleware(['validar']);
    });

//CATEGORIAS
    Route::group(['as'=>'cat.', 'prefix'=>'finance/categorias'], function(){
        //acessa o sistema
        Route::get('index', ['as'=>'index','uses'=>'Categoria@index'])->middleware(['validar']);
        //Salva Categorias
        Route::post('salvar-categoria', ['as'=>'salvar', 'uses'=>'Categoria@salvar'])->middleware(['validar']);
        //Deleta Categorias
        Route::post('deletar-categoria', ['as'=>'deletar', 'uses'=>'Categoria@deletar'])->middleware(['validar']);
        //Marca Status de Categoria
        Route::post('marcar-categoria', ['as'=>'marcar','uses'=>'Categoria@marcar'])->middleware(['validar']);
    });
    

//ORÇAMENTOS
    Route::group(['as'=>'orcamento.', 'prefix'=>'finance/orcamentos'], function(){
        //acessa o sistema
        Route::get('index', ['as'=>'index','uses'=>'Orcamento@index'])->middleware(['validar']);
        //Salva Orçamento
        Route::post('salvar-orcamento', ['as'=>'salvar','uses'=>'Orcamento@salvar'])->middleware(['validar']);

        //Deleta Orçamento
        Route::post('deletar-orcamento', ['as'=>'deletar','uses'=>'Orcamento@deletar'])->middleware(['validar']);

        //Marca Status do Orçamento
        Route::post('marcar-orcamento', ['as'=>'marcar','uses'=>'Orcamento@marcar'])->middleware(['validar']);

        //Pesquisa detalhes do orçamento
        Route::post('orcamento-detalhes', ['as'=>'detalhes','uses'=>'Orcamento@detalhes'])->middleware(['validar']);    
    });

//CONTAS
    Route::group(['as'=>'conta.', 'prefix'=>'finance/contas'], function(){
        //gera as contas automacamente
        Route::post('gerar-contas', ['as'=>'gerar','uses'=>'Conta@gerarContas'])->middleware(['validar']);
        //Cadastro de contas
        Route::get('index', ['as'=>'index','uses'=>'Conta@index'])->middleware(['validar']);
        //Salva conta
        Route::post('salvar',['as'=>'salvar','uses'=>'Conta@salvar'])->middleware(['validar']);

        //Deleta uma conta
        Route::post('deletar-conta',['as'=>'deletar','uses'=>'Conta@deletar'])->middleware(['validar']);
    });

    
//LANÇAMENTOS
    Route::group(['as'=>'lancamento.', 'prefix'=>'finance/lancamentos'], function(){
        //View Lançamentos
        Route::get('index',['as'=>'index','uses'=>'Lancamento@index'])->middleware(['validar']);

        //View Lancamentos com filtro
        Route::post('index',['as'=>'index','uses'=>'Lancamento@index'])->middleware(['validar']);

        //Salva um lançamento
        Route::post('salvar-lancamento',['as'=>'salvar','uses'=>'Lancamento@salvar'])->middleware(['validar']);

        //Deleta um orçamento
        Route::post('deletar-lancamento', ['as'=>'deletar','uses'=>'Lancamento@deletar'])->middleware(['validar']);

        //Panição dos lançamentos
        Route::post('paginar',['as'=>'paginar', 'uses'=>'Saldo@paginarLancamentos'])->middleware(['validar']);
    });
    

//SALDO
    Route::group(['as'=>'saldo.', 'prefix'=>'finance/saldo'], function(){
        //View Saldo
        Route::get('index', ['as'=>'index','uses'=>'Saldo@index'])->middleware(['validar']);

        //Pesquisa Periodo saldo em dias
        Route::post('index', ['as'=>'index', 'uses'=>'Saldo@index'])->middleware(['validar']);

    });
    

//APLICAÇÕES
    Route::group(['as'=>'aplicacao.', 'prefix'=>'finance/aplicacao'], function(){
        //View aplicações
        Route::get('index', ['as'=>'index', 'uses'=>'Aplicacao@index'])->middleware(['validar']);

        //calcula aplicação
        Route::post('calculo',['as'=>'calculo', 'uses'=>'Aplicacao@calcular'])->middleware(['validar']);
    });

//RELATÓRIOS
    Route::group(['as'=>'relatorio.', 'prefix'=>'finance/relatorios'], function(){
        //view index
        Route::get('index', ['as'=>'index','uses'=>'Relatorio@index'])->middleware(['validar']);

        //rota para os filtros de relatório
        Route::post('index', ['as'=>'index', 'uses'=>'Relatorio@index'])->middleware(['validar']);

        //gera pdf
        Route::get('index-pdf', ['as'=>'index-pdf','uses'=>'Relatorio@indexPdf'])->middleware(['validar']);

        //add urls
        Route::post('add-urls', ['as'=>'add-urls','uses'=>'Relatorio@addUrls'])->middleware(['validar']);

    });