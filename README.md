# Phalcon Multisite

This is a sample application for the [Phalcon Framework](https://github.com/phalcon/cphalcon).

The main goal of the project is to provide a multisite architecture, with shared folders, modules and basic functionalities 
which can be extending and overriding, for the specific uses.

It also includes some features like Authentication, ACL and more.


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

    php vendor/bin/phalcon-migrations migration run --config=src/common/config/main.php