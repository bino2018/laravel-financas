# laravel-financas
Autor: Fernando Bino Machado
Exemplo de Laravel - Sistema de Finanças Básico - Estudo de Caso


1 - Clone o Projeto: 
  git clone https://github.com/bino2018/laravel-financas.git
  
2 - Dentro do Projeto, acesse a Pasta banco de dados e crie um banco de dados com o arquivo nomeado como financas.sql

3 - Abra o projeto no seu editor de textos sublime vscode notepad++ ou qualquer outro

5 - Encontre o arquivo .env e localize esse trecho de código:

DB_CONNECTION=mysql\n
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=
DB_USERNAME=
DB_PASSWORD=

Depois altere a conexão conforme os dados de sua conexão.

4 - Crie um usuário na tabela usuario para poder acessar o sistema
  Atenção! A senha deve ser criptografada em sha1 para este estudo de caso.

5 - Usando um terminal acesse a pasta sistema e rode o comando: composer install

6 - Após concluir a instalação, ainda na mesma pasta rode o comando php artisan serve

7 - Digite na url do broswer: 127.0.0.1:8000 ou localhost:8000

8 - Clique em login e faça login conforme o usuário que cadastrou na tabela usuario
