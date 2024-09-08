# Stripe Integration on Laravel

Este é um API em Laravel integrado com Google Auth e Stripe (para soluções financeiras web), projetado como uma API de e-commerce que permite aos usuários adicionar produtos a um carrinho e comprá-los, com todos os processos de pagamento sendo gerenciados pelo Stripe. A API é construída usando princípios SOLID e PostgreSQL como seu banco de dados. O projeto usa JWT para autenticação, webhooks do Stripe para processamento de pagamentos, envio de emails para notificações, TDD para desenvolvimento orientado por testes, e inclui testes end-to-end. Além disso, Scribe é usado para salvar e gerenciar a documentação da API.

##### **Eu criei um modelo de banco de dados lógico deste projeto, dê uma olhada:**
![logical-model](public/img/database-logical-model.png)

## Rodando o projeto com Laravel Artisan:

- A primeira coisa que você deve fazer é adicionar seu arquivo `.env` com as credenciais do banco de dados que você deseja usar, por exemplo:
![exemple-env](https://parzibyte.me/blog/wp-content/uploads/2019/03/Env-de-Laravel-para-MySQL.png)

- Em seguida, execute `php artisan migrate` para rodar toda a estrutura de banco de dados necessária para o projeto. UM DETALHE MUITO IMPORTANTE: você precisa adicionar três variáveis no seu .env, que são: `SECRET_KEY_JWT` sua chave secreta do JWT, `HASH_TYPE_JWT` seu tipo de hash JWT (estou usando HS256 neste projeto), `SECRET_KEY_STRIPE` sua API Key do Stripe, `WEBHOOK_SECRET_STRIPE` sua chave de autorização do webhook.

- Depois disso, execute `php artisan serve` e pronto.

### É isso, abaixo você pode ver a documentação de referência da API!
![michael-thanks](https://miro.medium.com/v2/resize:fit:960/0*kIrASm_jWM13i1tT.gif)

## Authentication and User Management

 **POST `/api/auth`** 
   - **Descrição**: Autenticação de usuário.
   - **Corpo da Requisição**:
     ```json
     {
       "email": "string",
       "password": "string"
     }
     ```

 **GET `/api/google/oauth`** 
   - **Descrição**: Iniciar o OAuth do Google.
   - **Corpo da Requisição**: Não é necessário.

 **POST `/api/user/register`** 
   - **Descrição**: Registrar um novo usuário.
   - **Corpo da Requisição**:
     ```json
     {
       "name": "string",
       "email": "string",
       "phone_number": "string",
       "password": "string"
     }
     ```

 **POST `/api/user/mail/change/password`** 
   - **Descrição**: Alterar a senha via email.
   - **Corpo da Requisição**:
     ```json
     {
       "email": "string"
     }
     ```

 **GET `/api/user/check/token`** 
   - **Descrição**: Verificar se um token é válido.
   - **Corpo da Requisição**:
     ```json
     {
       "token": "string"
     }
     ```

 **POST `/api/user/change/password`** 
   - **Descrição**: Alterar a senha do usuário.
   - **Corpo da Requisição**:
     ```json
     {
       "token": "string",
       "new_password": "string"
     }
     ```

 **PUT `/api/user/update`** 
   - **Descrição**: Atualizar informações do usuário.
   - **Corpo da Requisição**:
     ```json
     {
       "id": "integer",
       "name": "string",
       "email": "string",
       "phone_number": "string"
     }
     ```

 **GET `/api/user/find`** 
   - **Descrição**: Encontrar usuário por ID.
   - **Corpo da Requisição**:
     ```json
     {
       "id_user": "integer"
     }
     ```

 **DELETE `/api/user/delete`** 
    - **Descrição**: Excluir um usuário.
    - **Corpo da Requisição**:
      ```json
      {
        "id_user": "integer"
      }
      ```

## Address Management

 **POST `/api/user/address/add`** 
    - **Descrição**: Adicionar um endereço de usuário.
    - **Corpo da Requisição**:
      ```json
      {
        "id_user": "integer",
        "street": "string",
        "city": "string",
        "state": "string",
        "zip_code": "string",
        "country": "string"
      }
      ```

 **PATCH `/api/user/address/switch`** 
    - **Descrição**: Trocar o endereço do usuário.
    - **Corpo da Requisição**:
      ```json
      {
        "id_user": "integer",
        "id_address": "integer"
      }
      ```

 **PUT `/api/user/address/update`** 
    - **Descrição**: Atualizar o endereço de um usuário.
    - **Corpo da Requisição**:
      ```json
      {
        "id_address": "integer",
        "street": "string",
        "city": "string",
        "state": "string",
        "zip_code": "string",
        "country": "string"
      }
      ```

 **GET `/api/user/address/find`** 
    - **Descrição**: Encontrar o endereço do usuário.
    - **Corpo da Requisição**:
      ```json
      {
        "id_address": "integer"
      }
      ```

 **DELETE `/api/user/address/remove`** 
    - **Descrição**: Remover um endereço de usuário.
    - **Corpo da Requisição**:
      ```json
      {
        "id_address": "integer"
      }
      ```

## Product Management

 **POST `/api/product/type/register`** 
    - **Descrição**: Registrar um novo tipo de produto.
    - **Corpo da Requisição**:
      ```json
      {
        "name": "string",
        "description": "string"
      }
      ```

 **PUT `/api/product/type/update`** 
    - **Descrição**: Atualizar um tipo de produto.
    - **Corpo da Requisição**:
      ```json
      {
        "id_type": "integer",
        "name": "string",
        "description": "string"
      }
      ```

 **POST `/api/product/register`** 
    - **Descrição**: Registrar um novo produto.
    - **Corpo da Requisição**:
      ```json
      {
        "name": "string",
        "price": "decimal",
        "company": "string",
        "type_id": "integer",
        "desc": "string",
        "stock": "integer"
      }
      ```

 **PUT `/api/product/update`** 
    - **Descrição**: Atualizar um produto.
    - **Corpo da Requisição**:
      ```json
      {
        "id_product": "integer",
        "name": "string",
        "price": "decimal",
        "company": "string",
        "type_id": "integer",
        "desc": "string",
        "stock": "integer"
      }
      ```

## Card Management

 **POST `/api/card/register`** 
    - **Descrição**: Registrar um novo cartão para o usuário.
    - **Corpo da Requisição**:
      ```json
      {
        "id_user": "integer"
      }
      ```

 **DELETE `/api/card/delete`** 
    - **Descrição**: Excluir um cartão.
    - **Corpo da Requisição**:
      ```json
      {
        "card_id": "integer"
      }
      ```

 **GET `/api/card/user`** 
    - **Descrição**: Listar cartões de um usuário.
    - **Corpo da Requisição**: Não é necessário.

## Product Type and Product Retrieval

**GET `/api/product/type/index`** 
    - **Descrição**: Listar todos os tipos de produto.
    - **Corpo da Requisição**: Não é necessário.

 **GET `/api/product/index`** 
    - **Descrição**: Listar todos os produtos.
    - **Corpo da Requisição**: Não é necessário.

 **GET `/api/product/find`** 
    - **Descrição**: Encontrar um produto pelo ID.
    - **Corpo da Requisição**:
      ```json
      {
        "id": "integer"
      }
      ```
