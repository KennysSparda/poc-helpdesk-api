# 🎫 PoC Helpdesk API

API REST desenvolvida em **Laravel** para gerenciamento de tickets de suporte com funcionalidade de rascunhos de respostas gerados por IA.

---

## 📌 Sobre o Projeto

Esta PoC (Proof of Concept) tem como objetivo validar o backend de um ecossistema de Helpdesk Inteligente. O sistema permite a abertura de chamados por clientes, consulta de tickets e a injeção/atualização de rascunhos de respostas via inteligência artificial para auxiliar a equipe de suporte.

---

## 🛠️ Tecnologias Utilizadas

- **Framework:** Laravel 11+
- **Linguagem:** PHP 8.3+
- **Banco de Dados:** PostgreSQL
- **Ambiente de Dev:** Docker (Laravel Sail)
- **Testes & Querys:** Postman / DBeaver

---

## 🚀 Como Rodar o Projeto

### 1. Pré-requisitos

Certifique-se de ter o Docker e o Git instalados no seu ambiente.

### 2. Passo a Passo

```bash
# Clone o repositório
git clone [https://github.com/kennyssparda/poc-helpdesk-api.git](https://github.com/kennyssparda/poc-helpdesk-api.git)
cd poc-helpdesk-api

# Copie o arquivo de ambiente
cp .env.example .env

# Suba o ambiente Docker via Sail
./vendor/bin/sail up -d

# Instale as dependências (caso precise)
./vendor/bin/sail composer install

# Execute as migrations do banco de dados no PostgreSQL
./vendor/bin/sail artisan migrate
```

Para encerrar os containers ao terminar o trabalho:

```bash
./vendor/bin/sail down
```

---

## 🔌 Endpoints da API

A base das rotas da API responde em `/api/tickets`.

| Método | Endpoint            | Descrição                                      |
| :----- | :------------------ | :--------------------------------------------- |
| `GET`  | `/api/tickets`      | Lista todos os tickets cadastrados             |
| `POST` | `/api/tickets`      | Cria um novo ticket de suporte                 |
| `GET`  | `/api/tickets/{id}` | Exibe os detalhes de um ticket específico      |
| `PUT`  | `/api/tickets/{id}` | Atualiza o status e/ou insere o rascunho de IA |

---

### 📝 Exemplos de Requisição

#### 1. Criar Ticket (`POST /api/tickets`)

**Body (JSON):**

```json
{
    "titulo": "Falha na sincronização de anúncios",
    "descricao_cliente": "Os veículos não estão subindo para a Webmotors.",
    "status": "aberto"
}
```

**Resposta (`201 Created`):**

```json
{
    "id": 1,
    "titulo": "Falha na sincronização de anúncios",
    "descricao_cliente": "Os veículos não estão subindo para a Webmotors.",
    "status": "aberto",
    "rascunho_ia": null,
    "created_at": "2026-07-20T15:49:48.000000Z",
    "updated_at": "2026-07-20T15:49:48.000000Z"
}
```

---

#### 2. Atualizar Status / Rascunho de IA (`PUT /api/tickets/1`)

**Body (JSON):**

```json
{
    "titulo": "Falha na sincronização de anúncios",
    "descricao_cliente": "Os veículos não estão subindo para a Webmotors.",
    "status": "em_atendimento",
    "rascunho_ia": "Cliente está informando que o portal Webmotors não está recebendo os estoques..."
}
```

**Resposta (`200 OK`):**

```json
{
    "id": 1,
    "titulo": "Falha na sincronização de anúncios",
    "descricao_cliente": "Os veículos não estão subindo para a Webmotors.",
    "status": "em_atendimento",
    "rascunho_ia": "Cliente está informando que o portal Webmotors não está recebendo os estoques...",
    "created_at": "2026-07-20T15:49:48.000000Z",
    "updated_at": "2026-07-20T16:01:07.000000Z"
}
```

---

## 📜 Histórico de Setup & Comandos

Comandos utilizados durante a estruturação inicial da aplicação:

```bash
# Habilitação das rotas de API e Sanctum
./vendor/bin/sail artisan install:api

# Criação da Migration, Model e Factory do Ticket
./vendor/bin/sail artisan make:model Ticket -m -f

# Criação do Controller REST com métodos de API
./vendor/bin/sail artisan make:controller Api/TicketController --model=Ticket --api

# Execução das migrations
./vendor/bin/sail artisan migrate
```

---

## 🎯 Próximos Passos (Backlog)

- [ ] Criar Service de Integração com API de LLM (Gemini API)
- [ ] Implementar processamento assíncrono de rascunhos via **Laravel Queues / Jobs**
