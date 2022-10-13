# AnthonyCecconato_5_18102021

Objectif : Développer un blog avec une architecture MVC, et la mise en place des bonnes pratiques.

[![Codacy Badge](https://app.codacy.com/project/badge/Grade/7b11d8126b1e49b5976118ad7abb5bae)](https://www.codacy.com/gh/acecconato/AnthonyCecconato_5_18102021/dashboard?utm_source=github.com&amp;utm_medium=referral&amp;utm_content=acecconato/AnthonyCecconato_5_18102021&amp;utm_campaign=Badge_Grade)

### Demo user
The following demo admin user is available to test the web application:
- Username : demo
- Password : demo_password

## Quick setup (require make)


### 1. Get started

`git clone git@github.com:acecconato/AnthonyCecconato_5_18102021.git anthonycecconato`

`cd anthonycecconato`

### 2. Installation

Install composer and node dependencies with yarn (default)  
`make install`

> Available parameters: `PACKAGE_MANAGER`, `PACKAGE_MANAGER_LOCK_FILE`
>
> E.g: With npm instead of yarn  
> `make install PACKAGE_MANAGER=npm`

Configure the environment  
`make prepare DB_HOST=localhost DB_NAME=anthonyc5 DB_USER=root DB_PASSWORD=root`

> Available parameters: `MAILER_SENDER`

You can configure the .env file manually if required.

Prepare
`make prepare DB_USER=root DB_PASSWORD=root DB_NAME=anthonyc5`

Start the webapp with  
`make run`


## Manual installation (without make)


### Download the projet and build it

> `git clone git@github.com:acecconato/AnthonyCecconato_5_18102021.git anthonycecconato`

> `cd anthonycecconato`

> `composer install --no-dev --optimize-autoloader`

> `npm install` or `yarn install`
> 

### Configure the environment

> `cp .env.example .env`

Then update the file as your needs. You must fill the database connection informations.


### Setup the database

You will need a MySQL or Mariadb database ready to receive the database import. When it's done, do the following:

> `mysql -u username -p dbname < sql/structure.sql`

### Generate demo fixtures

> `php ./src/Fixture/generate.php`

### Build assets

> `yarn build`

### Run the app

> `php -S localhost:8001 -t public`

## Testing emails

### Get started

To test the sending of the emails, you can install maildev : https://github.com/maildev/maildev.

Then you will need to update the .env file to edit the DSN and the SENDER values.
