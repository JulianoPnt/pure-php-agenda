# Test Cohros

## API Documentation

### Setup Project
1 - Setup database_structure.sql in your MySQL or SQLite server.

2 - Edit the config.php file with database connection and JWT secret (can be any password). Then:

``` 
$ composer install
$ php -S localhost:8000
```

### Routes

Note: All agenda routes are limited by account permissions (Can only update, delete own contacts).

HTTP Method | Route | Name
------------ | ------------- | -------------
POST | auth/login | Authentication Login
POST | auth/register | Authentication Register
POST | auth/checkToken | Authentication Check
GET | agenda | Agenda Get All From User
GET | agenda/{id} | Agenda Get By ID
POST | agenda | Agenda Insertion
PUT | agenda | Agenda Update
DELETE | agenda/{id} | Agenda Delete

### Authentication

#### Register User

POST: http://localhost:8000/api/auth/register

Example of JSON Sent
```
{
    "first_name": "Juliano",
    "last_name": "Pantoni Filho",
    "email": "juliano.88@hotmail.com",
    "password": "123321",
    "confirm_password": "123321"
}
```

#### Login

POST: http://localhost:8000/api/auth/login

Example of JSON Sent
```
{
    "email": "juliano.88@hotmail.com",
    "password": "123321",
}
```

Will return a JWT token that must be used in Authorization Header as a **Bearer Token**  to access agenda data.
Example of server response after login:
```
HTTP 200 Response.
{
    "bearer_token": "eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJ1c2VyX2lkIjoiNiIsInVzZXJfZW1haWwiOiJqdWxpYW5vLjg4QGhvdG1haWwuY29tIiwicGFzc3dvcmQiOiIkMnkkMTAkWTBVWThVYnYuNVVYTjl1ajNpbEthT0taXC9hcE0xcTQ5RjdTNHpyN2poSXF4MjhHcmJwalwvQyIsImxvZ2dlZF9hdCI6IjIwMjAtMDctMTNUMDA6NTk6NTUuNDI4ODU4WiIsImV4cGlyZXNfYXQiOiIyMDIwLTA3LTE2VDAwOjU5OjU1LjQyODY5OFoifQ.arNtQ4LeouQai5pP7XI2xgxNYXGA0AbyJ-wpthA4Kpw",
    "message": "Authorized",
    "expires_at": "2020-07-16T00:59:55.428698Z"
}
```

#### Validate Token

POST: http://localhost:8000/api/auth/checktoken

Must contain Authorizarion Header (Bearer token).

Examples of server response:
```
HTTP 200 Response.
{
    "message": "Authorized"
}

OR

HTTP 401 Response.
{
    "message": "Unauthorized"
}
```

### Agenda

#### Get contacts
GET http://localhost:8000/api/agenda

Can have JSON Body for pagination. 
If not present, will get unpaginated.
JSON Example:
```
{
    "page": 1,
    "perpage": 3
}
```
Server Response Example:
```
HTTP 200 Response.
{
    "message": "Success",
    "data": {
        "0": {
            "id": "1",
            "user_id": "5",
            "first_name": "Juliano",
            "last_name": "Pantoni Filho",
            "email": "juliano.88@hotmail.com",
            "address_city": "asd",
            "address_state": "asd",
            "address": "asd",
            "address_number": "23",
            "address_cep": "235",
            "address_district": "asdf",
            "created_at": "2020-07-12 17:27:43",
            "modified_at": null
        },
        "1": {
            "id": "2",
            "user_id": "5",
            "first_name": "zxc",
            "last_name": "d",
            "email": "zxc@zxc",
            "address_city": "zxvc",
            "address_state": "cxvz",
            "address": "zxcv",
            "address_number": "42",
            "address_cep": "3245",
            "address_district": "zxcb",
            "created_at": "2020-07-12 17:27:43",
            "modified_at": null
        },
        "pagination": {
            "page": 1,
            "perpage": 3
        }
    }
}
```

#### Insert contacts
POST http://localhost:8000/api/agenda

Note: Only first_name, email, phones are required.

JSON Example:
```
{
    "first_name": "Lucas",
    "last_name": "Souza", 
    "email": "email@gmail.com",
    "address_city": "Sertãozinho",
    "address_state": "São Paulo",
    "address": "Rua X",
    "address_number": 4124, 
    "address_cep": "14182-452", 
    "address_district": "Centro", 
    "phones": [
        {"number": 39204213},
        {"number": 6523535}
    ]
}
```
Server Response Examples:
```
HTTP 200 Response.
{
    "message": "Success"
}

OR

HTTP 400 Response.
{
    "message": {
        "field": "The field is required"
    }
}
```

#### Update contacts
PUT http://localhost:8000/api/agenda

Note: Only id and another field is required.

JSON Example:
```
{
    "id": 1,
    "first_name": "Lucas",
    "last_name": "Souza", 
    "email": "email@gmail.com",
    "address_city": "Sertãozinho",
    "address_state": "São Paulo",
    "address": "Rua X",
    "address_number": 4124, 
    "address_cep": "14182-452", 
    "address_district": "Centro", 
    "phones": [
        {
            "id": 1,
            "number": 39204213
        },
        {
            "id": 2,
            "number": 6523535
        }
    ]
}
```
Server Response Examples:
```
HTTP 200 Response.
{
    "message": "Success"
}
```

#### DELETE contacts
DELETE http://localhost:8000/api/agenda/{id} 

Server Response Examples:
```
HTTP 200 Response.
{
    "message": "Success"
}
```