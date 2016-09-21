# Stubs

Laravel's `make` command and it's capabilities is awesome, yet you want to push these capabilities a few steps further? I kindly present you the package you have always wanted.

## Usage

### 1) Installating

Follow **one of the steps** to install the package.

### Via Composer

Run the command below on your terminal;

```
composer require --dev ozanmuyes/laravel-stubs
```

### Manually

1. Download the [latest version of the package](https://github.com/ozanmuyes/laravel-stubs/archive/master.zip).
2. Extract the directory in the ZIP file to 'vendor' directory under your project - i.e. `/path/to/project/vendor`.
3. Rename the directory you have just extracted from 'laravel-stubs-master' to '**stubs**'.

### 2) Enabling

You will only want to use these package for local development, so you don't want to update the production  `providers` array in `config/app.php`. Instead, add the provider in `app/Providers/AppServiceProvider.php`, like so:

```php
public function register()
{
    // ...
  if ($this->app->environment() == 'local') {
    $this->app->register('Ozanmuyes\Stubs\Providers\StubsServiceProvider');
  }
}
```

### 3) Using

To use **Stubs**' offerings you need to run corresponding console command, for example to create a new model class;
```
php artisan stub:model PostsModel
```

This is no different than Artisan's `make:model` command. *Is it?* As you can see the name of the model we have just created was 'PostsModel'. But Laravel's naming convention wants us to name the entities, a model in this case, in a common way; models should have named after the entities they represents, in the singular form and without the 'Model' suffix.

Frankly **Stubs** is smart enough to create the model the way that Laravel's naming convention states. Go ahead and check the model file's name under the `app` directory. The model file's name is `Post` - not ~~PostsModel~~.

Also **Stubs** created two new files for you as well, the migration file and the seeder file for this very model. Of course any of the behavior of **Stubs** can be configured, i.e. name refactoring, migration and seeder file creation etc. For further information please consult the [configuration]() and [examples]() parts of the documentation.
