#  Teste para candidatos à vaga de Desenvolvedor PHP/N8N Automação Estech

Olá caro desenvolvedor, nesse teste analisaremos seu conhecimento geral e inclusive velocidade de desenvolvimento. Abaixo explicaremos tudo o que será necessário.

##  Instruções

O desafio consiste em implementar uma aplicação API Rest utilizando o framework PHP Laravel, um banco de dados relacional (Mysql), que terá como finalidade a ingestão de dados e metrificação de uma base de pokemons.
Use a API pública https://pokeapi.co/ para ingestão de dados.

Sua aplicação deve possuir:

- Um Command de ingest de dados:
	- Que consuma uma API e popule o banco de dados MYSQL.  	

- Uma rota onde eu consiga ver métricas.
	- Devo poder escolher a métrica que quero analisar: Ex: hp|attack|defense|special_attack|special_defense|speed|total|height|weight|order
	- Devo poder informar o limite de itens
	- Devo poder trazer apenas uma atributo especifico no top (como nome por exemplo)
  	- Devo poder ordenar por melhores ou piores
  	- Obs: Todos os parametros devem ser opcionais.
   
##  Banco de dados

- O banco de dados deve ser criado utilizando Migrations do framework Laravel.

##  Tecnologias a serem utilizadas

Devem ser utilizadas as seguintes tecnologias:
	- PHP
	- Framework Laravel
	- Docker (construção do ambiente de desenvolvimento)
	- Mysql

##  Entrega

- Para iniciar o teste, faça um fork deste repositório; **Se você apenas clonar o repositório não vai conseguir fazer push.**

- Crie uma branch com o seu nome completo;
- Altere o arquivo teste-pratico.md com as informações necessárias para executar o seu teste (comandos, migrations, seeds, etc);

- Depois de finalizado, envie-nos o pull request;

##  Bônus

- Implementar autenticação de usuário na aplicação usando sanctum.

##  O que será analisado?

- Criação e automação do ambiente com Docker;
- Transformação de dados;
- Organização do código;
- Raciocínio lógico;
- Performance e otimização de consultas;

###  Boa sorte!
