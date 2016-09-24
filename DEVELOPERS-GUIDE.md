# Developer Guide

## Adding new commands

To add new commands to the package you need to follow the steps stated as;

1. Create the command class under `src/Console/Commands` directory.  
The command class MUST extend `StubCommand` class.  
E.g. create *StubFooCommand* class like so;
```php
class StubFooCommand extends StubCommand {
  //
}
```

2. Set the command class' `signature` and `description` attributes appropriately.  
E.g. set the attributes of *StubFooCommand* class like so;
```php
protected $signature = '
  stub:foo
  {name : The name of the foo}
  {--f|file=default : The stub file name which should be rendered}
  {--L|lorem : Lorem ipsum dolor sit amet}
';

protected $description = 'Create a new foo class';
```

- If necessary fill `afterHandle` method. You can fire multiple stub commands with one command.  
E.g. call *StubBarCommand* command with options and switches, after handling the *StubFooCommand* like so;
```php
protected function afterHandle() {
  $this->call('stub:bar', [
    'name' => 'Quux',
    '--file' => 'default',  // option
    '-L' => true            // switch
  ]);

  //
}
```

3. Add new command's class to the `commands` array of `StubsServiceProvider` service provider.  
E.g. update the `commands` array like so;
```php
protected $commands = [
  //

  StubFooCommand::class,
];
```

4. Although this step is optional, it is highly recommended that you do **not** ignore this step.  
So far you have a console command which extends the `StubCommand` class to handle the command. Important thing to notice
that `StubCommand` class **needs** a stub instance for construction. If you provide a corresponding stub class that
extends base `Stub` class, you will then have full control over the stub file. Otherwise the base `Stub` class will be
used.

- Create corresponding stub class under `src` directory. This way you can override necessary attributes and methods.  
E.g. create stub class like so
```php
class FooStub extends Stub {
  //
}
```

From now on you can override base `Stub` class' various attributes, e.g. `imports`, `extends`, `traits` etc. These
attributes will be gathered right before the render operation.

- Finally register newly created stub class as a binding to base `Stub` class - i.e. **when** *StubFooCommand* (actually
underlying `StubCommand` class) **needs** `Stub` dependency, **give** newly created stub class (i.e. *FooStub* class).
```php
private function registerStubClasses() {
  //

  $this->app->when(StubFooCommand::class)
            ->needs(Stub::class)
            ->give(FooStub::class);
}
```

5. [Create the corresponding view](#creating-the-view).

## Creating the view

