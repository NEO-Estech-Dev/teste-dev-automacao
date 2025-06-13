#  Teste para candidatos à vaga de Desenvolvedor PHP Estech

Olá caro desenvolvedor, nesse teste analisaremos seu conhecimento geral e inclusive velocidade de desenvolvimento. Abaixo explicaremos tudo o que será necessário.

##  Instruções

O desafio consiste em implementar uma aplicação API Rest utilizando o framework PHP Laravel, um banco de dados relacional (Mysql), que terá como finalidade a inscrição de candidatos a uma oportunidade de emprego.

Sua aplicação deve possuir:

- CRUD de usuários:
	- Criar, editar, excluir e listar usuários.
	- O usuário pode ser Recrutador ou Candidato.

- CRUD de vagas:
	- Criar, editar, excluir e listar vagas.
	- A vaga pode ser CLT, Pessoa Jurídica ou Freelancer.

- CRUD de candidatos:
	- Criar, editar, excluir e listar candidatos.
	- Um candidato pode se inscrever em uma ou mais vagas.
	- Deve ser ser possível "pausar" a vaga, evitando a inscrição de candidatos (Somente Recrutador).

- Cada CRUD:
	- Deve ser filtrável e ordenável por qualquer campo, e possuir paginação de 20 itens.
	- Deve implementar SoftDelete.
	- Implementar validações de campos obrigatórios e tipos de dados.
	- Implementar Cache utilizando Redis.
	- Testes automatizados.

- Criar um comando para importação de dados a partir [deste arquivo](/example.csv) csv:
	- Deve ser implementado de maneira assincrona, utilizando Jobs. 	
	- Criar a tabela para armazenar os dados a serem importados.		 	
	- Criar um endpoint para retornar uma análise dos dados importados e executar os seguintes cálculos:
		- Separar os cálculos pelo dia da data registrada.
		- Média.
		- Mediana.
		- Valor mínimo.
		- Valor máximo.
		- % acima de 10.
		- % abaixo de -10.
		- % entre -10 e 10.

##  Banco de dados

- O banco de dados deve ser criado utilizando Migrations do framework Laravel, e também utilizar Seeds e Factorys para popular as informações no banco de dados.

##  Tecnologias a serem utilizadas

Devem ser utilizadas as seguintes tecnologias:
	- PHP
	- Framework Laravel
	- Docker (construção do ambiente de desenvolvimento)
	- Mysql
	- Redis
 	- Supervisor

##  Entrega

- Para iniciar o teste, faça um fork deste repositório; **Se você apenas clonar o repositório não vai conseguir fazer push.**

- Crie uma branch com o seu nome completo;
- Altere o arquivo teste-pratico.md com as informações necessárias para executar o seu teste (comandos, migrations, seeds, etc);

- Depois de finalizado, envie-nos o pull request;

##  Bônus

- Permitir deleção em massa de itens nos CRUDs.
- Permitir que o número de itens por página possa ser definido.
- Implementar autenticação de usuário na aplicação usando sanctum.

##  O que será analisado?

- Organização do código;
- Aplicação de design patterns;
- Raciocínio lógico;
- Aplicação de testes;
- Legibilidade;
- Criação do ambiente com Docker.

###  Boa sorte!
