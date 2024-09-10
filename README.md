# Stripe Integration on Laravel

This is a Laravel API integrated with Google Auth and Stripe (for web financial solutions), designed as an e-commerce API that allows users to add products to a cart and purchase them, with all payment processes managed by Stripe. The API is built using SOLID principles and PostgreSQL as its database. The project uses JWT for authentication, Stripe webhooks for payment processing, email sending for notifications, TDD for test-driven development, and includes end-to-end testing. Additionally, Scribe is used to save and manage API documentation.

##### **I created a logical database model for this project, take a look:**
![logical-model](public/img/database-logical-model.png)

## Running the project with Laravel Artisan:

- The first thing you should do is add your `.env` file with the database credentials you want to use, for example:
![example-env](https://parzibyte.me/blog/wp-content/uploads/2019/03/Env-de-Laravel-para-MySQL.png)

- Then run `php artisan migrate` to run all the necessary database structure for the project. A VERY IMPORTANT DETAIL: you need to add three variables in your .env, which are: `SECRET_KEY_JWT` your JWT secret key, `HASH_TYPE_JWT` your JWT hash type (I am using HS256 in this project), `SECRET_KEY_STRIPE` your Stripe API Key, `WEBHOOK_SECRET_STRIPE` your webhook authorization key.

- After that, run `php artisan serve` and you are all set.

### That's it, below you can see the API reference documentation!
![michael-thanks](https://miro.medium.com/v2/resize:fit:960/0*kIrASm_jWM13i1tT.gif)

## Authentication and User Management

 **POST `/api/auth`** 
- **Description**: User authentication.
- **Request Body**:
```json
{
    "email": "string",
    "password": "string"
}
```

 **GET `/api/google/oauth`** 
- **Description**: Start Google OAuth.
- **Request Body**: Not required.

 **POST `/api/user/register`** 
- **Description**: Register a new user.
- **Request Body**:
```json
{
    "name": "string",
    "email": "string",
    "phone_number": "string",
    "password": "string"
}
```

 **POST `/api/user/mail/change/password`** 
- **Description**: Change password via email.
- **Request Body**:
```json
{
    "email": "string"
}
```

 **GET `/api/user/check/token`** 
- **Description**: Check if a token is valid.
- **Request Body**:
```json
{
    "token": "string"
}
```

 **POST `/api/user/change/password`** 
- **Description**: Change the user's password.
- **Request Body**:
```json
{
    "token": "string",
    "new_password": "string"
}
```

 **PUT `/api/user/update`** 
- **Description**: Update user information.
- **Request Body**:
```json
{
    "id": "integer",
    "name": "string",
    "email": "string",
    "phone_number": "string"
}
```

 **GET `/api/user/find`** 
- **Description**: Find user by ID.
- **Request Body**:
```json
{
    "id_user": "integer"
}
```

 **DELETE `/api/user/delete`** 
- **Description**: Delete user by ID.
- **Request Body**:
```json
{
    "id_user": "integer"
}
```

## Address Management

 **POST `/api/user/address/add`** 
- **Description**: Add a user address.
- **Request Body**:
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
- **Description**: Switch user address.
- **Request Body**:
```json
{
    "id_user": "integer",
    "id_address": "integer"
}
```

 **PUT `/api/user/address/update`** 
- **Description**: Update a user address.
- **Request Body**:
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
- **Description**: Find the user's address.
- **Request Body**:
```json
{
    "id_address": "integer"
}
```

 **DELETE `/api/user/address/remove`** 
- **Description**: Remove a user address.
- **Request Body**:
```json
{
    "id_address": "integer"
}
```

## Product Management

**POST `/api/product/type/register`** 
- **Description**: Register a new product type.
- **Request Body**:
```json
{
    "name": "string",
    "description": "string"
}
```

**PUT `/api/product/type/update`** 
- **Description**: Update a product type.
- **Request Body**:
```json
{
    "id_type": "integer",
    "name": "string",
    "description": "string"
}
```

 **POST `/api/product/register`** 
- **Description**: Register a new product.
- **Request Body**:
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
- **Description**: Update a product.
- **Request Body**:
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
- **Description**: Register a new card for the user.
- **Request Body**:
```json
{
    "id_user": "integer"
}
```

**DELETE `/api/card/delete`** 
- **Description**: Delete a card.
- **Request Body**:
```json
{
    "card_id": "integer"
}
```

**GET `/api/card/user`** 
- **Description**: List a user's cards.
- **Request Body**: Not required.

## Product Type and Product Retrieval

**GET `/api/product/type/index`** 
- **Description**: List all product types.
- **Request Body**: Not required.

**GET `/api/product/index`** 
- **Description**: List all products.
- **Request Body**: Not required.

**GET `/api/product/find`** 
- **Description**: Find a product by ID.
- **Request Body**:
```json
{
    "id": "integer"
}
```
