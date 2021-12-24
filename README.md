

# Clean&Care

Clean&Care is an API for a  car clean&care company mobile app.

## Installation & Run

#### Download project
```
 git clone git@github.com:handesahin/clean-and-care.git
 ```
 
 #### Database migration & seed
```
 php artisan migrate --seed
 ```
 
  #### Load car details
```
 php artisan cars:upsert
  ```
 #### Run project
```
 php artisan serve
  ```

## API
### Register  
#### POST    v1/register
```
 { 
    "name":"name",
    "email": "name@gmail.com",
    "password": "password"
 }
```

### Login  
#### POST    v1/login
```
 { 
    "email": "name@gmail.com",
    "password": "password"
 }
```

#### ! Bearer token is required for all endpoint in below. !


### Services  
#### GET    v1/services

### Cars  
#### POST    v1/cars/list
```
{
    "limit":20,
    "offset":0
}
```

### Orders  
#### POST    v1/orders
```
{
    "car_id": 1,
    "service_id": 3,
    "price": 100,
    "payment_method":"Balance"  
}
```

### Account  
#### GET    v1/account/balance

#### POST    v1/account/balance
```
{
    "amount":20
}
```
#### GET    v1/account/orders



