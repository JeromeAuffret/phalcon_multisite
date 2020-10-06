# Phalcon Multisite

This is a sample application for the [Phalcon Framework](https://github.com/phalcon/cphalcon). \
This try to provide a lightweight architecture for a multisite stack. 
   
## Get Started

### Requirements

* PHP >= 7.2
* Phalcon >= 4.0
* PostgreSQL / MySQL / MariaDb / ...

### Composer

 Phalcon Multisite use composer's autoload to load handlers

    composer update
    
### Directories

This following directories need to have write permissions : 

* cache/
* public/temp/

## Demos

### Configuration

The main database need to be configure in `src/common/config/main.php`. \
Also, you can add a `config.php` witch override `main.php` and is gitignore by default.

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
 