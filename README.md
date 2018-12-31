# laravel-financas
Autor: Fernando Bino Machado<br>
Exemplo de Laravel - Sistema de Finanças Básico - Estudo de Caso


<h1> 1 - Clone o Projeto:  </h1>
  <p> git clone https://github.com/bino2018/laravel-financas.git </p>
  
<h1> 2 - Crie o Banco de Dados </h1>
<p>Dentro do Projeto, acesse a Pasta banco de dados e crie um banco de dados com o arquivo nomeado como financas.sql</p>

<h1>3 - Abra o projeto para edição </h1>
<p>Utilizando um editor de código sublime, vscode, notepad++ ou qualquer outro, selecione a pasta do projeto clonado e abra no editor escolhido</p>

<h1>4 - Configure a Conexão com Banco </h1>
<p>Encontre o arquivo .env e localize esse trecho de código:</p>
<br>
DB_CONNECTION=mysql<br>
DB_HOST=127.0.0.1<br>
DB_PORT=3306<br>
DB_DATABASE=<br>
DB_USERNAME=<br>
DB_PASSWORD=<br>
<br>
<p>Depois altere a conexão conforme os dados de sua conexão.</p>

<h1>5 - Crie um usuário na tabela usuario para poder acessar o sistema</h1>

<p>Atenção! A senha deve ser criptografada em sha1 para este estudo de caso.
  <br>Exemplo: 
  
  <br>use nomeDoSeuBanco;
  
  <br>insert into usuario (nmUsuario,dsSenha,dsEmail,cdPermissao,cdStatus)
values('nomeusuario','senha','email@gmail.com','1','1');

<h1>6 - Instale o projeto</h1>
<p> Usando um terminal acesse a pasta sistema e rode o comando: composer install </p>

<h1>7 - Inicie a aplicação </h1>
<p>Após concluir a instalação, ainda na mesma pasta rode o comando:<br> php artisan serve</p>

<h1>8 - Acesse a aplicação</h1>
<p>Digite na url do broswer: 127.0.0.1:8000 ou localhost:8000</p>

<h1>9 - Faça login</h1>
<p>Clique em login e faça login conforme o usuário que cadastrou na tabela usuario</p>
