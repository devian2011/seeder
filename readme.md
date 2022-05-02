# Database Seeder

## Installation

```bash
composer require devian2011/seeder
```

## Usage

```bash
php ./vendor/bin/seeder.php seeder:fill-data --templates-dir=$(PWD)/examples --mode=predefined --params=$(PWD)/.env
```

| Param | Definition | IsRequired |
| ----- | ---------- | ---------- | 
| templates-dir | Path to directory which contains definitions for fixtures | true |
| params | Path to env file. Also it supports get variables from $_ENV Global | false |  

## Configuration

Better way for configure seeds it uses yaml notations

Folder must contain databases.yaml file with database's configurations. Database configuration parser supports **ENV** function.

```yaml
databases:
  - code: seeder # Unique database code. It must use in tables definitions
    dsn: "env('MARIADB_DSN')" # PDO dsn
    user: "env('MARIADB_USER')" # Database user
    password: "env('MARIADB_PASSWORD')" # Database password
    options: # PDO options for database connect 
```



