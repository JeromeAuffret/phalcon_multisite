# Phalcon Multisite

This is a sample application for the [Phalcon Framework](https://github.com/phalcon/cphalcon). \
It tries to provide a lightweight multi-site architecture, with sharing and overriding functionalities. \
It also includes some features like ACL, Authentication and more.


## Get Started

### Requirements

* PHP >= 7.2
* Phalcon >= 4.0.5

### Composer

```
composer update
```
    
### Directories

This following directories need to have writes permissions : 

* cache/
* public/temp/


## Demos

### Configuration

The main database need to be configure in `src/common/config/main.php`. \
Additionally, you can add a `config.php`, which is ignored from git, to store sensitive configuration.

```
'main_database' => [
    'adapter'  => 'Postgresql',
    'host'     => '127.0.0.1',
    'username' => 'postgres',
    'password' => '',
    'dbname'   => '',
    'port' => '5432',
    'charset' => 'utf8'
]
```

### Migrations

[Phalcon Migrations](https://docs.phalcon.io/4.0/en/db-migrations) is imported with composer by default, and can be access running 

    php vendor/bin/phalcon-migrations
    
To deploy the default main database, run :

    php vendor/bin/phalcon-migrations migration 