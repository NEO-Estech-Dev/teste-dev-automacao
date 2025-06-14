#  Introdução

O projeto como objetivo a criação de uma API Rest para realizar a inscrição de candidatos a uma oportunidade de emprego.

# Tecnologias

Para execução do projeto, foram empregadas as seguintes tecnologias: framework Laravel, a linguagem de programação PHP, um banco de dados relacional, o escolhido foi o MYSQL, além de Redis para realizar cache, e o PHPUnit para execução de testes automatizados.


# Modelagem de Dados

O banco de dados possuem algumas tabelas que são geradas de forma padrão nas migrations do próprio framework. As tabelas que compõem o core do sistema e da execução do projeto são 6.

- Tabela de Usuários
  
![image](https://github.com/user-attachments/assets/466006db-4fe8-429e-a982-9532ed52d1cf)


- Tabela de Vagas

![image](https://github.com/user-attachments/assets/c06f5aa5-7e87-42bf-a62c-e53d666ed751)


- Tabela de Candidatos

![image](https://github.com/user-attachments/assets/f318ac62-5d39-4b14-9436-0dd568efce59)

- Tabela de Temperatura

![image](https://github.com/user-attachments/assets/fa9c484b-d2c3-4139-b23d-82b618a51a64)


- Tabela de Aplicações

![image](https://github.com/user-attachments/assets/5d446354-1c4a-4d1c-b70f-c2c377f685ba)

- Tabela de Jobs

![image](https://github.com/user-attachments/assets/c695462c-4b9f-4296-8393-fc9cf07c4ae3)

# Rotas

As rotas disponíveis da aplicação são:

| rotas                                            | descrição                                                                                                    |
|--------------------------------------------------|--------------------------------------------------------------------------------------------------------------|
| <kbd>GET /api/users</kbd>                        | Retorna todos os usuários cadastrados, com paginação de 20 usuários                                          |
| <kbd>GET /api/users/{id}</kbd>                   | Retorna apenas um usuário caso seja registrado                                                               |
| <kbd>GET /api/users/active/{id}</kbd>            | Torna um usuário ativo caso esteja desativado                                                                |
| <kbd>POST /api/users/store</kbd>                 | Cadastra usuários no banco de dados                                                                          |
| <kbd>PUT /api/users/update/{id}</kbd>            | Atualiza um usuário a partir de um id existente                                                              |
| <kbd>DELETE /api/users/delete/{id}</kbd>         | Realiza um soft delete de um usuário  existente                                                              |
|                                                  |                                                                                                              |
| <kbd>GET /api/vacancies</kbd>                    | Retorna todos os vagas cadastrados, com paginação de 20 vagas                                                |
| <kbd>GET /api/vacancies/{id}</kbd>               | Retorna apenas uma vaga caso seja registrada                                                                 |
| <kbd>GET /api/vacancies/active/{id}</kbd>        | Torna uma vagas ativo caso esteja desativado                                                                 |
| <kbd>POST /api/vacancies/store</kbd>             | Cadastra vaga no banco de dados                                                                              |
| <kbd>PUT /api/vacancies/update/{id}</kbd>        | Atualiza um vaga a partir de um id existente                                                                 |
| <kbd>DELETE /api/vacancies/delete/{id}</kbd>     | Realiza um soft delete de uma vaga existente                                                                 |
|                                                  |                                                                                                              |
| <kbd>GET /api/candidates</kbd>                   | Retorna todos os candidatos cadastrados, com paginação de 20 candidatos                                      |
| <kbd>GET /api/candidates/{id}</kbd>              | Retorna apenas um candidato caso seja registrado                                                             |
| <kbd>GET /api/candidates/active/{id}</kbd>       | Torna um candidato ativo caso esteja desativado                                                              |
| <kbd>POST /api/candidates/store</kbd>            | Cadastra candidato no banco de dados                                                                         |
| <kbd>PUT /api/candidates/update/{id}</kbd>       | Atualiza um candidato a partir de um id existente                                                            |
| <kbd>DELETE /api/candidates/delete/{id}</kbd>    | Realiza um soft delete de um candidato existente                                                             |
|                                                  |                                                                                                              |
| <kbd>GET /api/applications</kbd>                 | Retorna todas as aplicações cadastradas, com paginação de 20 aplicações                                      |
| <kbd>GET /api/applications/{id}</kbd>            | Retorna apenas uma aplicação caso seja registrado                                                            |
| <kbd>GET /api/applications/active/{id}</kbd>     | Torna uma aplicação ativa caso esteja desativado                                                             |
| <kbd>POST /api/applications/store</kbd>          | Cadastra aplicação no banco de dados                                                                         |
| <kbd>PUT /api/applications/update/{id}</kbd>     | Atualiza uma aplicação a partir de um id existente                                                           |
| <kbd>DELETE /api/applications/delete/{id}</kbd>  |  Realiza um soft delete de uma aplicação existente                                                           |
|                                                  |                                                                                                              |
| <kbd>GET /api/temperature/mean</kbd>             | Retorna a média das temperaturas presentes no arquivo csv                                                    |
| <kbd>GET /api/temperature/median</kbd>           | Retorna a mediana das temperaturas presentes no arquivo csv                                                  |
| <kbd>GET /api/temperature/lower</kbd>            | Retorna o menor valor de temperatura presente no arquivo csv                                                 |
| <kbd>GET /api/temperature/higher</kbd>           | Retorna o maior valor de temperatura presete no arquivo csv                                                  |
| <kbd>GET /api/temperature/greater_than_ten</kbd> | Retorna a porcentagem das temperaturas maiores que 10                                                        |
| <kbd>GET /api/temperature/lower_than_minus_ten</kbd> | Retorna a porcentagem das temperaturas menores que -10                                                   |
| <kbd>GET /api/temperature/between_both_limits</kbd>  | Retorna a porcentagem das temperaturas maiores entre -10 e 10                                            |

## Body de requisições store e update
```json
    "/users/store": {
        "fullname": "string"
    	"email": "string",
    	"level": "integer",
    	"password": "string"
    },
    "/vacancies/store": {
        "title_vacancy_job": "string",
    	"location_vacancy_job": "string",
    	"salary_vacancy_job": "float",
    	"type_vacancy_job": "tinyInteger",
    	"company_name": "string"
    },
    "/candidates/store": {
        "cpf": "string",
    	"linkedin": "string",
    	"github": "string",
    	"phone": "string",
    	"user_id": "tinyInteger"
    },
    "/applications/store": {
        "vacancy_id": "tinyInteger",
	    "candidate_id": "tinyInteger"
    },
```
# Jobs

Ao executar o comando:
```laravel
php artisan migrate
```

As tabelas de filas serão criadas. Foi criado um job que faz a leitura do arquivo example.csv na raiz do arquivo, e realiza a inserção de todos os dados dentro da tabela tbl_temperature.
Ele é disparado através de um command. Então siga o seguinte passo a passo para ter acesso aos dados disponíveis na rota de temperature:

- Executar o command
  ```laravel
  php artisan sendJob:temp
  ```
- Se caso ocorrer tudo certo, aparecerá uma mensagem de confirmação. Em seguida para é necessário executar o queue:work do laravel. Como a aplicação está em docker, será necessário utilizar o comando para verificar o id do container do php-fpm.
  ```docker
  docker ps
  ```
- Uma vez detectado esse id do container, basta utilizar esse comando, para acessar o container pelo terminal:
  ```docker
  docker exec -it {id_container} bash
  ```
- Agora que estamos dentro do container com a aplicação php, basta utilizar o comando de iniciar a fila:
  ```laravel
  php artisan queue:woork
  ```
- E pronto, você verá se o job foi executado com sucesso ou se deu algum tipo de erro.

# Testes
A parte dos testes é bem simples, eles podem ser executados através do comando:
```laravel
php artisan test
```
ou

```php
./vendor/bin/phpunit
```

# Executar o Projeto

Para executar o projeto de teste, basta ter o docker-compose. Então ir na pasta do projeto que foi clonado e utilizar o comando docker-compose up -d.

