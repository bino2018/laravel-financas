<?php

//Devido ao tamanho do projeto as rotas foram feitas dessa maneira
//Porém recomenda-se criar as rotas em arquivos separados conforme a necessidade do projeto

//HOME
    
    //inicio
    Route::get('/', 'Home@home');

    //View Login
    Route::get('/login', 'Home@viewLogin');

    //Valida login
    Route::post('/valida-login','Home@validaLogin');

    //retorna o sistema
    Route::get('/sistema', 'Home@sistema')->middleware(['validar']);

//CATEGORIAS

    //Cadastro de Categorias
    Route::get('/categorias', 'Categoria@index')->middleware(['validar']);

    //Salva Categorias
    Route::post('/salvar-categoria', 'Categoria@salvar')->middleware(['validar']);

    //Deleta Categorias
    Route::post('/deletar-categoria','Categoria@deletar')->middleware(['validar']);

    //Marca Status de Categoria
    Route::post('/marcar-categoria', 'Categoria@marcar')->middleware(['validar']);

//ORÇAMENTOS
    
    //Cadastro de orçamentos
    Route::get('/orcamentos','Orcamento@index')->middleware(['validar']);

    //Salva Orçamento
    Route::post('/salvar-orcamento', 'Orcamento@salvar')->middleware(['validar']);

    //Deleta Orçamento
    Route::post('/deletar-orcamento', 'Orcamento@deletar')->middleware(['validar']);

    //Marca Status do Orçamento
    Route::post('/marcar-orcamento', 'Orcamento@marcar')->middleware(['validar']);

    //Pesquisa detalhes do orçamento
    Route::post('/orcamento-detalhes', 'Orcamento@detalhes')->middleware(['validar']);

    //Gera as contas
    Route::post('/gerar-contas', 'Conta@gerarContas')->middleware(['validar']);

//CONTAS
    
    //Cadastro de Contas
    Route::get('/contas','Conta@index')->middleware(['validar'])->middleware(['validar']);

    //Salva conta
    Route::post('/salvar-conta','Conta@salvar')->middleware(['validar']);

    //Deleta uma conta
    Route::post('/deletar-conta','Conta@deletar')->middleware(['validar']);

//LANÇAMENTOS

    //View lançamentos
    Route::get('/lancamentos','Lancamento@index')->middleware(['validar']);

    //Salva um lançamento
    Route::post('/salvar-lancamento', 'Lancamento@salvar')->middleware(['validar']);

    //Deleta um orçamento
    Route::post('/deletar-lancamento', 'Lancamento@deletar')->middleware(['validar']);

//SALDO
    //View Saldo
    Route::get('/saldo', 'Saldo@index')->middleware(['validar']);

    //Pesquisa Periodo saldo em dias
    Route::post('saldo', 'Saldo@index')->middleware(['validar']);

//RELATORIOS
    