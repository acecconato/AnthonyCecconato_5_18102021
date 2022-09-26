# AnthonyCecconato_5_18102021

Objectif : Développer un blog avec une architecture MVC, et la mise en place des bonnes pratiques.

[![Codacy Badge](https://app.codacy.com/project/badge/Grade/7b11d8126b1e49b5976118ad7abb5bae)](https://www.codacy.com/gh/acecconato/AnthonyCecconato_5_18102021/dashboard?utm_source=github.com&amp;utm_medium=referral&amp;utm_content=acecconato/AnthonyCecconato_5_18102021&amp;utm_campaign=Badge_Grade)

## Get started

### Installation

> `git clone git@github.com:acecconato/AnthonyCecconato_5_18102021.git anthonycecconato`

> `cd anthonycecconato`

> `composer install`

> `npm install` or `yarn install`

### Environment configuration
> `cp .env.example .env`

Then update the file as your needs.

### Database setup

You will need a MySQL or Mariadb database ready to receive the database import. When it's done, do the following:
> `mysql -u username -p dbname < sql/structure.sql`

### Generate demo fixtures
> `php ./src/Fixture/generate.php`

### Build assets
> `yarn build`

### Running the app
> `php -S localhost:8001 -t public`
