#  Introdução

O projeto como objetivo a criação de uma API Rest para realizar a inscrição de candidatos a uma oportunidade de emprego.

# Tecnologias

Para execução do projeto, foram empregadas as seguintes tecnologias: framework Laravel, a linguagem de programação PHP, um banco de dados relacional, o escolhido foi o MYSQL, além de Redis para realizar cache, e o PHPUnit para execução de testes automatizados.


# Modelagem de Dados

O banco de dados possuem algumas tabelas que são geradas de forma padrão nas migrations do próprio framework. As tabelas que compõem o core do sistema e da execução do projeto são 6.

- Tabela de Usuários
![alt text](image.png)

- Tabela de Vagas
![alt text](image-1.png)

- Tabela de Candidatos
![alt text](image-2.png)

- Tabela de Temperatura
![alt text](image-3.png)

- Tabela de Aplicações
![alt text](image-4.png)

- Tabela de Jobs
![alt text](image-5.png)

# Rotas

As rotas disponíveis da aplicação são:

GET: /users => Retorna todos os usuários cadastrados, com paginação de 20 usuários.

POST: /users/store => Responsável pela criação de um usuário e persistir no banco de dados.

BODY: {
	fullname: string
	email: string,
	level: integer,
	password: string
}


Para executar o projeto de teste, basta ter o docker-compose. Então ir na pasta do projeto que foi clonado e utilizar o comando docker-compose up -d.

