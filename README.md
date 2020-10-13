# Phalcon Multisite

This is a sample application for the [Phalcon Framework](https://github.com/phalcon/cphalcon).

The main goal of this project is to provide a multisite architecture, that allow sharing code between multiple applications.

It also includes some fully customizable features like Authentication, ACL and more.


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

## Devtools

[Phalcon Devtools](https://docs.phalcon.io/4.0/en/devtools) and
[Phalcon Migrations](https://docs.phalcon.io/4.0/en/db-migrations) are imported with composer 

    php vendor/bin/phalcon
    php vendor/bin/phalcon-migrations

## Demos

At this point, only the common application can be access. \
To render a specific application, you need to identify it. \
Applications can be identified using session and/or hosts.

### Host

If are on local, you can edit the `/etc/hosts` and add something like following,

    127.0.0.1       demo2.localhost 
    
Now, visit you new host `http://demo2.localhost`.

Host configuration can be found in `src/common/config/main.php`.

### Session

#### Configuration

The main database need to be configure in `src/common/config/main.php`. \
Additionally, you can add a `config.php`, which is ignored from git, to store sensitive configuration.

```
'main_database' => [
    'adapter'  => 'Postgresql',
    'host'     => '127.0.0.1',
    'username' => 'postgres',
    'password' => '',
    'dbname'   => '',
    'port'     => '5432',
    'charset'  => 'utf8'
]
```

#### Migrations
    
To deploy the default main database, run :

    php vendor/bin/phalcon-migrations migration run --config=src/common/config/main.php