# File manager using Vue + Laravel

Web CRUD project developed with vue + laravel

### Project Requirements

- Password protected access
- Display only the loggedin user files
- Edit, Download or Delete options

### Tech

- Vue - 2.6
- Laravel - 7.0
- Composer - V1.6+
- Node V8+
- NPM - 6.x
- Bootstrap - 4
- Bootstrap-vue 2.15
- PHP and MySQL

### Installation

Clone the respository and install npm and composer dependencies.

```sh
$ git clone https://github.com/diegovargasg/vueLogin.git
$ cd vueLogin
$ npm run install
```
***Note:***
- *In case the installation command fails, the dependencies can manually be installed by running npm install and composer install inside the folders client and api respectively*


### Update the database credentials in the .env file

Note: The database must be created first.

```sh
DB_HOST=<YOUR_HOST>
DB_PORT=<YOUR_PORT>
DB_DATABASE=<YOUR_DATABASE>
DB_USERNAME=<YOUR_USER>
DB_PASSWORD=<YOUR_PASSWORD>
```

### Import example data into the DB

```sh
$ npm run migrations
```

### Serve API

```sh
$ cd api
$ php artisan serve
```

### Serve client

```sh
$ cd client
$ npm run serve
```

The client server should be running now, usually under:

```js
http://localhost:8080/
```

### TODOS:
- [ ] Add UnitTesting
- [ ] Remove faker modification for generating images


### Links, resources and special mentions:

- https://cli.vuejs.org/ - vue cli
- https://github.com/tymondesigns/jwt-auth - JSON Web Token Authentication for Laravel & Lumen
- https://github.com/fzaninotto/Faker - Faker is a PHP library that generates fake data for you
- https://www.youtube.com/user/TechGuyWeb - Web development tutorials
- https://www.youtube.com/user/phpacademy - Web development tutorials
