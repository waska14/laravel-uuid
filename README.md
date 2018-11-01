# Laravel-Uuid
**If you want to use uuid columns and fill them automatically, this package is for you.**

This is a Laravel package for using (auto-generating) uuid in models.
With this package you write less code, as the uuid(s) are being created, when you first save your instances.

### Docs

* [Laravel compatibility](#laravel-compatibility)
* [Installation](#installation)
* [Migrations](#migrations)
* [Models](#models)
* [Configuration](#configuration)
* [Other usage](#other-usage)

## Laravel compatibility

 Laravel  | Compatible
:---------|:----------
&#62;=5.1 | ✓


## Installation

Add the package in your composer.json by executing the command.

```bash
composer require waska14/laravel-uuid
```

Next, add the service provider in your `config/app.php`

```php
'providers' => [
    ....
    Waska\LaravelUuid\UuidServiceProvider::class,
    ....
],
```

## Migrations

```php
// 2.1 If you want to use uuid column as primary column:
Schema::create('users', function(Blueprint $table)
{
    $table->uuid('uuid')->primary();
    $table->string('another_column')->nullable();
    ...
});

// 2.2 If you want to use uuid column as non-primary column:
Schema::create('users', function(Blueprint $table)
{
    $table->increments('id');
    $table->uuid('uuid')->unique();
    ...
});

// 2.3 If you want to use multiple uuid columns (maybe on of them is primary):
Schema::create('users', function(Blueprint $table)
{
    $table->uuid('uuid')->primary();
    $table->uuid('uuid_column_2')->unique();
    $table->uuid('uuid_column_3')->unique();
    ...
});
```

*Note: If uuid column is not primary, you should make it unique, or manually index the column.*

## Models

1. You must use trait in model
```php
class User extends Authenticatable
{
    use Waska\Traits\Uuid;
}
```

2.1 If you are using uuid column as primary key and column_name **is default** `id`, you need nothing more.

2.2 If you are using uuid column as primary key and column_name **is not default** `id`, you must 
define `protected $primaryKey` and `protected $uuid_column`:
```php
class User extends Authenticatable
{
    use Waska\Traits\Uuid;

    protected $primaryKey = "uuid_primary_column_name";
    protected $uuid_column = "uuid_primary_column_name";
}
```

2.3 If you are using non-primary uuid column and **column name equals to `default_column_name`** (from config), 
you need only to append column name in `protected $fillable`:
```php
class User extends Authenticatable
{
    use Waska\Traits\Uuid;

    protected $fillable = [
        'name',
        'email',
        'uuid', // config/waska.uuid.php -> default_column_name
    ];
}
```

2.4 If you are using non-primary uuid column and **column name doesn't equal to `default_column_name`** (from config), 
you need define `protected $uuid_column` and append column name in `protected $fillable`:
```php
class User extends Authenticatable
{
    use Waska\Traits\Uuid;

    protected $uuid_column = "uuid_column_name";
    
    protected $fillable = [
        'name',
        'email',
        'uuid_column_name',
    ];
}
```

2.5 If you are using multiple uuid columns (if one of them is primary, you must do step ***2.1*** at first),
you need define `protected $uuid_column` **as an array** and append column names (*only non-primary*) in `protected $fillable`
```php
class User extends Authenticatable
{
    use Waska\Traits\Uuid;

    protected $primaryKey = "uuid_primary_column_name"; // If one of them is primary
    protected $uuid_column = ["uuid_column_name1", "uuid_column_name2", "uuid_column_name3"];
    
    protected $fillable = [
        'name',
        'email',
        'uuid_column_name1',
        'uuid_column_name2',
        'uuid_column_name3',
    ];
}
```


## Configuration

***If you want to change default configuration***, you must copy the configuration file to your project.

Laravel 5.*
```bash
php artisan vendor:publish --tag=waska-uuid-config
```

*Note: If you're going to use **v3** or **v5** uuid, it's recommended to change **v3_default_namespace** 
and **v5_default_namespace** with valid uuid strings .*

*Generate them with this command in tinker (Start tinker: `php artisan tinker`)*
```php
Waska\Uuid::get(4);
```
*Where **3** is version number. For **v5** you must pass **5** as the first parameter.*



## Other usage

Generate uuid (**Universal Unique Identifier**)
```php
/**
 * This generates name-based Uuid
 * @param int $version.
 * @param string $name. String which the uuid is generating for.
 * @param string $namespace. Valid uuid string. Default value is defined in config/waska.uuid.php
 * @return String
 */
Waska\Uuid::get(3, "some_random_string", "valid_uuid");

// This generates pseudo-random Uuid
Waska\Uuid::get(); // Default version is 4, so it means: Waska\Uuid::get(4);

/**
 * This generates name-based Uuid
 * @param int $version.
 * @param string $name. String which the uuid is generating for.
 * @param string $namespace. Valid uuid string. Default value is defined in config/waska.uuid.php
 * @return String
 */
Waska\Uuid::get(5, "some_random_string", "valid_uuid");
```
