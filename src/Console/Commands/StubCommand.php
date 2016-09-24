<?php

namespace Ozanmuyes\Stubs\Console\Commands;

use Illuminate\Console\Command;
use Ozanmuyes\Stubs\Stub;

abstract class StubCommand extends Command {
  /**
   * The stub instance.
   *
   * @var Stub $stub
   */
  private $stub;

  /**
   * Create a new command instance.
   *
   * @param Stub $stub
   */
  public final function __construct(Stub $stub) {
    parent::__construct();

    $this->stub = $stub;
  }

  /**
   * Execute the console command.
   *
   * @return mixed
   * @throws \Exception
   */
  public function handle() {
    // Since there is no any more appropriate way to get CLI arguments and
    // switches, get them here and set the stub accordingly.
    $name = $this->argument('name');

    // TODO Write exception class
    if (null === $name) {
      /**
       * This kind of exception caused by the developer of the package.
       * Check all the classes that extends this class and ensure
       * the class that extends requires `name` as argument.
       */
      throw new \Exception('Stub\'s name did not specified');
    }

    // TODO Check all required arguments

    // TODO In the Stub class set class name and also filename depending this given name
    $this->stub->setName($name);

    // Setting the stub is finished. Now create the file.
    $this->createActualFile();

    // TODO Print success/error message

    return $this->afterHandle();
  }

  private function createActualFile() {
    $rendered = \View::make(
      $this->stub->getType() . DIRECTORY_SEPARATOR . $this->stub->getStubFileName(), // e.g. 'model/default'
      $this->stub->getDataForRenderer()
    )->render();

    // HACK Since Blade renderer could not properly handle PHP opening tag we add it here
    $rendered = "<?php\r\n\r\n" . $rendered;

    file_put_contents($this->stub->getTargetFilePath(), $rendered);
  }

  /**
   * Execute the consequent console command(s) if any (e.g. create migration,
   * seeder for model).
   *
   * @return void|mixed
   */
  abstract protected function afterHandle();
}
