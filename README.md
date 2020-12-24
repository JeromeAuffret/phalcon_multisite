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

At this point, only the base application can be access. \
To render a specific application, you need to identify it. \
Applications can be identified using session and/or hosts.

### Host

In local env, you can edit the `/etc/hosts` and add something like,

    127.0.0.1       demo2.localhost 
    
and visit the new host `http://demo2.localhost`.

Hosts configuration can be found in `src/config/main.php`.

### Session

#### Configuration

The main database need to be configure in `src/config/main.php`. \
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

    php vendor/bin/phalcon-migrations migration run --config=src/config/main.php


# Front End

## Project setup
```
npm install
```

### Compiles and hot-reloads for development
```
npm run serve
```

### Compiles and minifies for production
```
npm run build
```

### Lints and fixes files
```
npm run lint
```

### Customize configuration
See [Configuration Reference](https://cli.vuejs.org/config/).
