# 📌 Projeto de Plataforma de Vagas

Este é um projeto **Laravel** que fornece uma API para uma plataforma de vagas, permitindo que recrutadores gerenciem vagas e candidatos se inscrevam.

## 🛠️ Requisitos

- Docker 🐳
- Docker Compose ⚙️

## ▶️ Como executar o projeto

Clone o repositório:

```bash
git clone https://github.com/WescleySil/WescleySilvaDeCastro.git
cd WescleySilvaDeCastro
```

Suba os contêineres do Docker:

```bash
docker-compose up -d
```

Este comando irá construir e iniciar todos os serviços necessários. O contêiner da aplicação (`app`) está configurado para executar automaticamente o script `startup.sh` na inicialização.

### 📜 O que o script `startup.sh` faz

- 📝 Copia o arquivo de ambiente de exemplo para `.env`
- 📦 Instala as dependências do Composer
- 🔐 Gera a chave da aplicação Laravel
- 🧱 Executa as migrações e semeia o banco de dados (`migrate:fresh --seed`)
- 📬 Inicia o `supervisord` para gerenciar os processos em fila
- 🌡️ Importa os dados de temperatura do arquivo `example.csv`

## 🌐 Acesse a aplicação

Após os contêineres estarem em execução, acesse:
[http://localhost:8000](http://localhost:8000)

## 📮 Endpoints da API

A coleção do Postman (`Teste Backend.postman_collection.json`) para testar os endpoints da API está incluída no repositório.

### 🔐 Autenticação

- `POST /api/login`: Autentica um usuário
- `POST /api/logout`: Faz logout do usuário autenticado

### 👤 Usuários

- `POST /api/user`: Cria um novo usuário
- `GET /api/user`: Lista os usuários
- `PUT /api/user/{user}`: Atualiza um usuário
- `DELETE /api/user/{user}`: Exclui um usuário

### 💼 Vagas

- `GET /api/vacancy`: Lista as vagas
- `POST /api/vacancy`: Cria uma nova vaga
- `PUT /api/vacancy/{vacancy}`: Atualiza uma vaga
- `DELETE /api/vacancy/{vacancy}`: Exclui uma vaga

### 📋 Candidaturas

- `GET /api/applicant`: Lista os candidatos
- `POST /api/applicant/apply-to-vacancy`: Inscreve um candidato em uma vaga

### 🌡️ Temperatura

- `GET /api/temperature`: Retorna a análise dos dados de temperatura

## 🔑 Credenciais de Acesso

Um usuário recrutador padrão é criado durante a semeadura do banco de dados com as seguintes credenciais:

- **E-mail:** admin@admin.com
- **Senha:** 12345678

## 🧩 Serviços do Docker

Conforme definido no `docker-compose.yml`:

- **Aplicação (Laravel)**: `app`
- **Servidor Web (Nginx)**: `nginx` (localhost:8000)
- **Banco de Dados (MySQL)**: `mysql` (localhost:3307)
- **Cache (Redis)**: `redis` (localhost:6379)