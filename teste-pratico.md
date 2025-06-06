# Teste prático - Estech

## Informações da entrega

Essa aplicação foi desenvolvida utilizando:

- PHP 8.2
- Laravel 12.16
- MySQL
- Redis
- Docker & Docker Compose
- Supervisor
- Composer

## Instruções para rodar o projeto

### 1. Clone o repositório e acesse o diretório

```bash
git clone https://github.com/seu-usuario/teste-dev-estech.git
cd teste-dev-estech
git checkout nome-completo
```
### 2. Crie um arquivo `.env` a partir do `.env.example`

```bash
cp .env.example .env
```

### 3. Configure as variáveis de ambiente no arquivo `.env`
Edite o arquivo `.env` com as configurações necessárias, como banco de dados, cache, etc. Certifique-se de que as variáveis estejam corretas para o seu ambiente.

Comando para gerar a chave de aplicação JWT:
```bash
php -r "echo bin2hex(random_bytes(32));"
```
### 4. Rode o comando docker-compose para subir os containers

```bash
docker-compose up -d
```

### 5. Rode as migrations, seeders 

```bash
docker compose exec app php artisan migrate --seed
```

## Funcionalidades implementadas
- CRUD de Usuários
- Usuário pode ser Recrutador ou Candidato
- CRUD de Vagas (CLT, PJ, Freelancer)
- CRUD de Candidatos
- Candidato pode se inscrever em várias vagas
- Vagas podem ser pausadas (somente Recrutador)
- Filtros e ordenação em todos os CRUDs
- Paginação (default 20, customizável)
- Soft Deletes
- Cache com Redis
- Importação assíncrona via Job a partir de arquivo CSV enviado via API
- Endpoint de análise com cálculos por dia
- Docker + Docker Compose configurado
- Supervisor configurado para workers

## Endpoints (Postman Collection)

A coleção do Postman está disponível na raiz do repositório. Você pode importar essa coleção para testar os endpoints da API.