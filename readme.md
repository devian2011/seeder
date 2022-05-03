# Database Seeder

## Installation

```bash
composer require devian2011/seeder
```

## Usage

### Simple usage

```bash
php ./vendor/bin/seeder.php seeder:fill-data --templates-dir=$(PWD)/examples --mode=predefined --params=$(PWD)/.env
```

| Param | Definition | IsRequired |
| ----- | ---------- | ---------- | 
| templates-dir | Comma separated paths to directories which contains definitions for fixtures.| true |
| params | Path to env file. Also it supports get variables from $_ENV Global | false | 

### Advanced usage

You can use seeder in your code for extend expressions, change output wrapper or add event listeners.

> Package uses [symfony/expression-language](https://symfony.com/doc/current/components/expression_language.html)  
> For create your own functions you can write [extensions](https://symfony.com/doc/current/components/expression_language/extending.html)



```php

$seeder = new \Devian2011\Seeder\Seeder(
    ['/path/to/notations/one', '/path/to/notations/two'], // Required param
    ['/path/to/env/file/.env', '/path/to/env/file/.env.local'] // optional param, it can be empty
);

$seeder->run(
    new \Devian2011\Seeder\Output\SymfonyConsoleOutput($output), // Output wrapper
    new class implements \Symfony\Component\ExpressionLanguage\ExpressionFunctionProviderInterface {
        public function getFunctions(){
            return [
                new \Symfony\Component\ExpressionLanguage\ExpressionFunction('plus', fn() -> return;, fn($ctx, $a , $b) -> return $a + $b)
            ]           
        }
    }, // Expression Language extensions
    [
        new class implements \Devian2011\Seeder\Events\EventHandlerInterface {
            public function getActions() : array 
            {
                return [\Devian2011\Seeder\SeederEvents::EVENT_SEEDER_CONFIG_LOADED] 
                // List of events You cann see all events in \Devian2011\Seeder\SeederEvents
            }
            public function handle(\Devian2011\Seeder\Events\EventInterface $event){
                echo $event->getMessage(); // Event handler
            }
        }
    ] // Array of event handlers
);
```

## Configuration

Better way for configure seeds it uses yaml notations

### Database connection configuration

Seeder must contain databases section which is used for connect to databases and mark database connections.

```yaml
databases:
  - code: seeder # Unique database code. It must use in tables definitions
    dsn: "env('MARIADB_DSN')" # PDO dsn
    user: "env('MARIADB_USER')" # Database user
    password: "env('MARIADB_PASSWORD')" # Database password
    options: # PDO options for database connect 
```

### Table configuration

For create fake data this package uses - [fakerphp/faker](https://fakerphp.github.io/)

```yaml
tables: # Required section name 
  users: # Table code (it can be overwritten by another config)
    database: seeder # Database connection code.
    name: users # Table name
    rowQuantity: 5 # Fixtures row count
    primaryKey: id # Primary key
    columns: # Columns which should
      - name: id # Column name
        value: "auto_increment" # If field with 'auto_increment' value it will not been filled
      - name: login
        value: "faker.email()" # Expression language has faker. For call faker functions you must write faker.METHOD(...$params)
      - name: password
        value: "faker.word()"
      - name: balance
        value: "400"
      - name: balance_old
        value: "context.balance - 200" # Package has context support. Context works with current row. For get column value for this row you must write context.column_name
        depends: # If column value depends on another columns. You must write **depends** section which have a list if dependant columns 
          - balance # This column depends on balance value
    relations: # If table has relation to another tables. You must set this block
      - name: role_id # Column name which should contain related value
        database: seeder # Database for relation table
        table: roles # Relation table
        column: id # Relation column
        type: manyToOne # Type of relation. Supports manyToOne and oneToOne relation
   
      - name: info_id
        database: seeder
        table: info
        column: id
        type: manyToOne
        fromDb: true # If column have relation with already loaded data. Table with loaded data must have notation
    fixed: # If we need to fill predefined data. Everything works like columns section 
      - - name: id
          value: 1
        - name: login
          value: admin
        - name: password
          value: 123456
        - name: balance
          value: 400
        - name: balance_old
          value: "context.balance - 100"
          depends:
            - balance
      - - name: id
          value: 2
        - name: login
          value: manager
        - name: password
          value: 654321
        - name: balance
          value: 200
        - name: balance_old
          value: "context.balance - 100"
          depends:
            - balance
```



