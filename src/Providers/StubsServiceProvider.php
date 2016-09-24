<?php

namespace Ozanmuyes\Stubs\Providers;

use Illuminate\Support\ServiceProvider;
use Ozanmuyes\Stubs\Console\Commands\{
  StubModelCommand
};
use Ozanmuyes\Stubs\{
  Helpers,
  Stub,
  ModelStub
};

class StubsServiceProvider extends ServiceProvider {
  /**
   * List of console commands that this package provides.
   *
   * @var array $commands
   */
  protected $commands = [
    StubModelCommand::class,

    // Add more command registration here
  ];

  /**
   * Bootstrap the application services.
   *
   * @return void
   */
  public function boot() {
    // The method MUST be called here, after Blade specific
    // registrations had been done. Do NOT move somewhere else.
    $this->registerBladeDirectives();
  }

  /**
   * Register the application services.
   */
  public function register() {
    $this->registerStubClasses();

    $this->registerStubsViewFinder();

    // Register console commands
    $this->commands($this->commands);
  }

  // TODO Write a function to ease defining Blade directives
  private function registerBladeDirectives() {
    $blade = $this->app->make('blade.compiler');

    $blade->directive('namespace', function ($param) {
      if ('' === $param) {
        $param = '$namespace';
      }

      return '<?php echo "namespace " . ' . $param . ' . ";" . PHP_EOL; ?>';
    });

    $blade->directive('imports', function ($param) {
      if ('' === $param) {
        $param = '$imports';
      }

      return
        '<?php foreach(' . $param . ' as $item) { echo "use " . $item . ";" . PHP_EOL; } ?>';
    });

    $blade->directive('class', function ($param) {
      if ('' === $param) {
        $param = '$name, $extends, $implements';
      }

      $param = array_map('trim', explode(',', $param));

      $name = null;
      $extends = null;
      $implements = null;

      switch (count($param)) {
        case 3: {
          $name = $param[0];
          $extends = $param[1];
          $implements = $param[2];

          break;
        }

        case 2: {
          $name = $param[0];
          $extends = $param[1];

          break;
        }

        case 1: {
          $name = $param[0];

          break;
        }

        default: {
          throw new \Exception('Stub\'s name attribute was not set.');
        }
      }

      $extendsString = '';
      $implementsString = '';

      if (null !== $extends) {
        $extendsString = 'if ("" !== $extends) { echo " extends " . $extends; }';
      }

      if (null !== $implements) {
        $implementsString = 'if (count($implements) > 0) { echo " implements " . implode(", ", ' . $implements . '); }';
      }

      return sprintf(
        '<?php echo "class " . %s; %s %s ?>',
        $name,
        $extendsString,
        $implementsString
      );
    });

    $blade->directive('traits', function ($param) {
      if ('' === $param) {
        $param = '$traits';
      }

      return
        '<?php if (count($traits) > 0) { echo "use " . implode(", ", ' . $param . ') . ";" . PHP_EOL; } ?>';
    });
  }

  private function registerStubClasses() {
    $this->app->when(StubModelCommand::class)
      ->needs(Stub::class)
      ->give(ModelStub::class);

    // Add more stub type's binding here
  }

  // TODO Figure out the codes below
  private function registerStubsViewFinder() {
    /**
     * @var \Illuminate\View\FileViewFinder $finder
     */
    $finder = app('view.finder'); // resolve the finder
    $finder->addExtension('blade.stub');
    $finder->addExtension('stub');
    $finder->addLocation(Helpers::getStubsDirectory());

    $this->app->singleton('view.finder', function () use ($finder) {
      return $finder;
    });
  }
}
