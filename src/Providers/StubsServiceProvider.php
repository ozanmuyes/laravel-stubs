<?php

namespace Ozanmuyes\Stubs\Providers;

use Illuminate\Support\ServiceProvider;
use Ozanmuyes\Stubs\Console\Commands\StubModelCommand;
use Ozanmuyes\Stubs\Renderers\{
  Renderer,
  Blade as BladeRenderer,
  Template as TemplateRenderer
};
use Ozanmuyes\Stubs\{
  Stub,
  ModelStub
};

class StubsServiceProvider extends ServiceProvider {
  /**
   * Bootstrap the application services.
   *
   * @return void
   */
  public function boot() {
    $this->registerBladeDirectives();
  }

  /**
   * Register the application services.
   */
  public function register() {
    $this->registerRenderers();

    $this->registerStubClasses();

    $this->registerStubCommands();
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
        '<?php echo "class " . %s; %s %s echo " {" . PHP_EOL; ?>',
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
        '<?php echo "use " . implode(", ", ' . $param . ') . ";" . PHP_EOL; ?>';
    });
  }

  private function registerRenderers() {
    $this->app->bind(Renderer::class,
      function () {
        switch (config('stubs.renderer', 'blade')) {
          // Add more renderer here

          case 'template':
            return new TemplateRenderer();

          case 'blade':
          default:
            return new BladeRenderer();
        }
      });
    //
  }

  private function registerStubClasses() {
    $this->app->when(StubModelCommand::class)
      ->needs(Stub::class)
      ->give(ModelStub::class);

//    $this->app->when(StubControllerCommand::class)
//      ->needs(Stub::class)
//      ->give(ControllerStub::class);

    // Add more stub type's binding here
  }

  /**
   * Register stub commands.
   */
  private function registerStubCommands() {
    // StubModelCommand
    //
    $this->app->singleton('command.stubs.model', function ($app) {
      return $app['Ozanmuyes\\Stubs\\Console\\Commands\\StubModelCommand'];
    });

    $this->commands('command.stubs.model');

    // Add more command registration here
  }
}
