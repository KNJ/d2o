# D2O

## Installation

Use Composer.

```sh
composer require knj/d2o
```

## D2O from PDO

If D2O, you can use PDO and PDOStatement with only 1 statement using chain method:

```php
<?php
$d2o = new Wazly\D2O($dsh, $username, $password);

$sql = 'SELECT * FROM users WHERE id = :id'

$row = $d2o
    ->state($sql)
    ->run([':id' => 3])
    ->pick();
```

otherwise need to `$dbh` as PDO and `$stmt` as PDOStatement and at least 3 statements are required:

```php
<?php
$dbh = new PDO($dsh, $username, $password);

$sql = 'SELECT * FROM users WHERE id = :id';

$stmt = $dbh->prepare($sql);
$stmt->execute([':id' => 3]);
$row = $stmt->fetch(PDO::FETCH_OBJ);
```

## Methods

### state ( string $statement )

`D2O::state()` is to be called by D2O directly. It is almost the same as `PDO::prepare()` but returns D2O instance itself.

```php
<?php
$d2o->state($sql); // returns $d2o
```

### bind ( [ array $parameters [, string $bind_type = 'value' ] ] )

`D2O::bind()` is to be called after `D2O::state()`. It is similar to `PDOStatement::bindValue()` but returns D2O instance.

```php
<?php
$d2o->state($sql)
    ->bind([
        ':role' => $role,
        ':limit' => $limit,
    ]); // returns $d2o
```

### run ( [ array $parameters [, string $bind_type = 'value' ] ] )

`D2O::run()` is to be called after `D2O::state()`. It is similar to `PDOStatement::execute()` but returns D2O instance.

```php
<?php
$d2o->state($sql)
    ->bind([
        ':role' => $role,
        ':limit' => $limit,
    ])
    ->run(); // returns $d2o

$d2o->state($sql)
    ->run([
        ':role' => $role,
        ':limit' => $limit,
    ]); // the same as the above
```

### pick ( string $fetch_style )

`D2O::pick()` is to be called after `D2O::run()`. It is almost the same as `PDOStatement::fetch()`. It does not return the instance but query result.

```php
<?php
$row = $d2o->state($sql)
    ->run([
        ':role' => $role,
        ':limit' => 1,
    ])
    ->pick(); // recommended if the number of rows is supposed to be 1

$result = $d2o->state($sql)
    ->run([
        ':role' => $role,
        ':limit' => $limit,
    ]); // recommended if the number of rows is supposed to be 2 or more

$row1 = $result->pick();
$row2 = $result->pick();
$row3 = $result->pick();
```

### format ( string $fetch_style )

`D2O::format()` is to be called after `D2O::run()`. It is almost the same as `PDOStatement::fetchAll()`. It does not return the instance but query result.

```php
<?php
$rows = $d2o->state($sql)
    ->run([
        ':role' => $role,
        ':limit' => $limit,
    ])
    ->format();
```
