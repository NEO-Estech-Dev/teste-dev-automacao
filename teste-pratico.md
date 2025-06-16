# Guia de Inicialização do Projeto

## 1. Clonando o Projeto

Clone o repositório para sua máquina local:

```bash
git clone https://github.com/Felipe1208/teste-dev-backend.git
cd teste-dev-backend
git checkout felipe-jorge-pereira
```

## 2. Subindo o Ambiente com Docker

O projeto utiliza Docker para facilitar a configuração do ambiente. Para iniciar todos os serviços necessários, execute:

```bash
docker-compose up -d
```

O backend estará disponível na porta **80** (ou na porta definida pela variável de ambiente `APP_PORT`).

## 3. Executando Migrations e Seeders

Após subir os containers, acesse o container da aplicação e execute as migrations e seeders para preparar o banco de dados:

```bash
docker-compose exec laravel.test bash
php artisan migrate --seed
```

## 4. Importando Arquivo CSV

Para importar o arquivo `example.csv` localizado em `storage/app/example.csv`, utilize o comando Artisan:

```bash
php artisan import:csv example.csv
```

## 5. Tecnologias e Funcionalidades

- **Testes Automatizados:** O projeto possui testes implementados para garantir a qualidade das funcionalidades.
- **Cache:** Utilização do Redis para cache, otimizando a performance das operações.
- **Filas:** Processamento assíncrono de tarefas utilizando Supervisor para gerenciamento das filas.
- **Banco de Dados:** MySQL como sistema gerenciador de banco de dados relacional.
- **Framework:** Laravel 12 rodando com PHP 8.4.
- **Autorização:** Controle de acesso implementado via Policies do Laravel.
- **Regra de Negócio:** Por regra do negócio, as ações ideais de administradores estão atribuídas ao papel de **recrutador**, que exerce essas permissões no sistema.
- **Seeders:** As seeders configuram um starter pack com dados iniciais para facilitar a utilização do projeto, incluindo usuários de teste (candidato e recrutador) e vagas de exemplo.

---

Siga os passos acima para iniciar e utilizar o projeto corretamente.
