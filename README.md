# laravel-financas
Autor: Fernando Bino Machado<br>
e-mail: fernando.bino.machado@gmail.com<br>
Descrição: Exemplo de Laravel - Sistema de Finanças Básico - Estudo de Caso


<h1> 1 - Clone o Projeto:  </h1>
<p> 
    Abra um terminal e rode
    
    cd /var/www/
  
    depois rode:
    
    git clone https://github.com/devBino/laravel-financas.git 
    
</p>
  
<h1> 2 - Crie o Banco de Dados </h1>
<p>Dentro do Projeto, acesse a Pasta banco de dados e crie um banco de dados com o arquivo nomeado como financas.sql</p>

<h1>3 - Abra o projeto para edição </h1>
<p>Utilizando um editor de código sublime, vscode, notepad++ ou qualquer outro, selecione a pasta do projeto clonado e abra no editor escolhido</p>

<h1>4 - Configure a Conexão com Banco </h1>
<p>Encontre o arquivo .env e localize esse trecho de código e altere a conexão conforme os dados do seu banco criado</p>
<br>
DB_CONNECTION=mysql<br>
DB_HOST=127.0.0.1<br>
DB_PORT=3306<br>
DB_DATABASE=nomeDoSeuBancoDeDados<br>
DB_USERNAME=usuario<br>
DB_PASSWORD=senha<br>
<br>

<h1>5 - Crie um usuário na tabela usuario para poder acessar o sistema</h1>

<p>Atenção! A senha deve ser criptografada em sha1 para este estudo de caso.
  <br>Rode esse comando sql substituindo os valores para os valores do usuário que pretende criar: 
  
  <br>use nomeDoSeuBanco;
  
  <br>insert into usuario (nmUsuario,dsSenha,dsEmail,cdPermissao,cdStatus)
values('nomeusuario','senha','email@gmail.com','1','1');

<h1>6 - Instale o projeto</h1>
<p> Usando um terminal acesse a pasta laravel-financas/sistema e rode o comando: composer install 
  <br>
  Lembre-se que essa é a pasta do projeto que você clonou.
</p>
<h1>7 - Configure o Apache</h1>
 <p>
  Utilizando um terminal Edite o arquivo rodando o comando: <br>
  sudo nano /etc/apache2/sites-enabled/000-default.conf
  <br>
  
  Insira o conteúdo abaixo antes do fechamento da tag VirtualHost
  
  Suponha que você tenha clonado este projeto em /var/www
  Então:
  <br>
        
        Alias /finance /var/www/laravel-financas/sistema/public
        <Directory /var/www/laravel-financas/sistema/public>
                Options Indexes FollowSymLinks
                AllowOverride All
        </Directory>

</p>

<h1>8 - Acesse a aplicação</h1>
<p>Digite na url do broswer: http://localhost/finance/home/fazer-login</p>
