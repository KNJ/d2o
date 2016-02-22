# D2O

[![Packagist Pre Release](https://img.shields.io/packagist/vpre/knj/d2o.svg)](https://packagist.org/packages/knj/d2o)
[![Build Status](https://travis-ci.org/KNJ/d2o.svg?branch=master)](https://travis-ci.org/KNJ/d2o)

![D2O-chan](img/d2o.png)

## Installation

Use Composer.

```sh
composer require knj/d2o
```

## D2O from PDO

If D2O, you can use PDO and PDOStatement with only 1 statement using method chaining:

```php
$d2o = new Wazly\D2O($dsh, $username, $password);

$sql = 'SELECT * FROM users WHERE id = :id'

$row = $d2o
    ->state($sql)
    ->run([':id' => 3])
    ->pick();
```

otherwise need to `$dbh` as PDO and `$stmt` as PDOStatement and at least 3 statements are required:

```php
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
$d2o->state($sql); // returns $d2o
```

### bind ( [ array $parameters [, string $bind_type = 'value' ] ] )

`D2O::bind()` is to be called after `D2O::state()`. It is similar to `PDOStatement::bindValue()` but returns D2O instance.

```php
$d2o->state($sql)
    ->bind([
        ':role' => $role,
        ':limit' => [$limit, 'int'],
    ]); // returns $d2o
```

### run ( [ array $parameters [, string $bind_type = 'value' ] ] )

`D2O::run()` is to be called after `D2O::state()`. It is similar to `PDOStatement::execute()` but returns D2O instance.

```php
$d2o->state($sql)
    ->bind([
        ':role' => $role,
        ':limit' => [$limit, 'int'],
    ])
    ->run(); // returns $d2o

$d2o->state($sql)
    ->run([
        ':role' => $role,
        ':limit' => [$limit, 'int'],
    ]); // the same as the above
```

### pick ( string $fetch_style )

`D2O::pick()` is to be called after `D2O::run()`. It is almost the same as `PDOStatement::fetch()`. It does not return the instance but query result.

```php
$row = $d2o->state($sql)
    ->run([
        ':role' => $role,
        ':limit' => [1, 'int'],
    ])
    ->pick(); // recommended if the number of rows is supposed to be 1

$result = $d2o->state($sql)
    ->run([
        ':role' => $role,
        ':limit' => [$limit, 'int'],
    ]); // recommended if the number of rows is supposed to be 2 or more

$row1 = $result->pick();
$row2 = $result->pick();
$row3 = $result->pick();
```

### format ( string $fetch_style )

`D2O::format()` is to be called after `D2O::run()`. It is almost the same as `PDOStatement::fetchAll()`. It does not return the instance but query result.

```php
$rows = $d2o->state($sql)
    ->run([
        ':role' => $role,
        ':limit' => [$limit, 'int'],
    ])
    ->format();
```

## Differences between D2O and PDO

### PDOStatement lies inside of D2O

Using PDO, extra variable is required to contain PDOStatement instance.
However, D2O has PDOStatement as its property so that it can provide method chaining.

_Example of sequential insertion_:

```php
$d2o->state('INSERT INTO items(name, price) VALUES (:name, :price)')
    ->run(['name' => 'pencil', 'price' => 20])
    ->run(['name' => 'eraser', 'price' => 60])
    ->run(['name' => 'notebook', 'price' => 100]);
```

### Data binding

D2O saves coding when you bind values on placeholders:

```php
$d2o->bind([
    'role' => 'editor',  // ':role' => 'editor', PDO::PARAM_STR
    'limit' => 20,       // ':limit' => 20, PDO::PARAM_INT
    'hash' => '6293',    // ':hash' => '6293', PDO::PARAM_STR
    'id' => [36, 'str'], // ':id' => 36, PDO::PARAM_STR
]);
```

- You don't have to use colons
- You don't have to `PDO_PARAM_*` when the value is string, integer, or null
- You can specify data type more concisely, like `'str'` instead of `PDO::PARAM_STR`

And D2O never calls `PDOStatement::bindParam()`.
