# Symfony Fruityvice
This project tries to demonstrate a fully working Symfony project.
Database support: MariaDB. MySQL, Postgres, SQLite, DB2, Oracle, SQL Server using [Doctrine](https://www.doctrine-project.org/)
UI: [Vue 3](https://vuejs.org/), [Element Plus](http://element-plus.org/)

## Installation
Before proceding with installation make sure you have [composer](https://getcomposer.org/), [symfony-cli](https://symfony.com/download), [nodejs ^19.9.0](https://github.com/nvm-sh/nvm) installed.

### Step 1: Dependency installation
```bash
$ cd project_directory
$ composer install
$ npm install
```

### Step 2: Environment preparation
create `.env.local` and added neccessary environment variables for this project.
```
APP_ENV=dev
APP_SECRET=random_32_character
DATABASE_URL="mysql://username:password@127.0.0.1:3306/database_name?serverVersion=mariadb-10.4.28&charset=utf8"
MAILER_DSN=smtp://user:pass@smtp.example.com:25
FROM_EMAIL=dummy_sender@dummy.com
ADMIN_EMAIL=dummy_admin@dummy.com
```

### Step 3: Database preparation
Run following commands, it will prepare the database for you.
```bash
$ php bin/console doctrine:database:create
$ php bin/console doctrine:migrations:migrate
```

### Step 4: Database population
This project is using data from [frutyvice](https://fruityvice.com/) to populated sample data into database.
Run following command to do so.
`$ php bin/console fruits:fetch`

### Final step: Run the project
Build vue source
`$ npm run build`

Start server
`$ symfony server:start`

## Setup test environment
create `.env.test.local` and added neccessary environment variables for this project.
```
KERNEL_CLASS='App\Kernel'
APP_SECRET='$ecretf0rt3st'
SYMFONY_DEPRECATIONS_HELPER=999999
PANTHER_APP_ENV=panther
PANTHER_ERROR_SCREENSHOT_DIR=./var/error-screenshots
DATABASE_URL="mysql://username:password@127.0.0.1:3306/database_name?serverVersion=mariadb-10.4.28&charset=utf8"
MAILER_DSN=null://null
FROM_EMAIL=dummy_sender@dummy.com
ADMIN_EMAIL=dummy_admin@dummy.com
```

Run following command to initite test database
```bash
$ php bin/console --env=test doctrine:database:create
$ php bin/console --env=test doctrine:schema:create
```

That's it. If you find any bug or have any kind of interesting idea, feel free to create an issue on it.

Happy coding ;)