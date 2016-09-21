<?php

namespace Ozanmuyes\Stubs;

use Illuminate\Console\AppNamespaceDetectorTrait;

abstract class Stub {
  use AppNamespaceDetectorTrait;

  /**
   * The type of class being generated, e.g. just a 'class',
   * 'model', 'controller' etc.
   * This attribute must be set by the class that extends this
   * class to properly determine the corresponding stubs path.
   *
   * @var string
   */
  private $type;

  /**
   * @var string $namespace
   */
  private $namespace;

  /**
   * @var string $className
   */
  private $className;

  /**
   * @var string $stubFileName
   */
  private $stubFileName;

  /**
   * @var string $targetFileName
   */
  private $targetFileName;

  /**
   * @var array $imports
   */
  private $imports = [];

  /**
   * @var string $extends
   */
  protected $extends = '';

  /**
   * @var array $implements
   */
  protected $implements = [];

  /**
   * @var array $traits
   */
  protected $traits = [];

  /**
   * Stub constructor.
   *
   * @param string $type     Type of the stub (e.g. 'model', 'controller' etc.)
   * @param string $fileName Stub file name to be rendered.
   */
  protected function __construct(string $type, $fileName = 'default') {
    $this->type = strtolower(Str::snake(Str::plural($type)));

    // Set namespace right after setting the type
    //
    // Combine the application's namespace with the namespace
    //for the type from stubs.php config file
    $namespace = $this->getAppNamespace() . config('stubs.namespaces' . $this->type);

    // Remove trailing slash
    $this->namespace = str_replace_last('\\', '', $namespace);

    // Set stub file path right after setting the fileName
    //
    $this->stubFileName = Helper::getStubsDirectory() . '/' . $this->type . '/' . $fileName . '.blade.php';
  }

  public function setName(string $name) {
    // TODO Set className and targetFileName depending on $name
  }

  // TODO Parameter may be string as well
  public function setImports(array $imports) {
    // TODO Process (isimlerini ayır, Helpers::truncateFromStart ile) imports
    // $this->imports = processedImports;
  }

  public function setExtends(string $extends) {
    // TODO Process (isimlerini ayır, Helpers::truncateFromStart ile) imports
    // $this->extends = processedExtends;
  }

  // TODO Parameter may be string as well
  public function setImplements(array $implements) {
    // TODO Process (isimlerini ayır, Helpers::truncateFromStart ile) imports
    // $this->implements = processedImplements;
  }

  // TODO Parameter may be string as well
  public function setTraits(array $traits) {
    // TODO Process (isimlerini ayır, Helpers::truncateFromStart ile) imports
    // $this->traits = processedTraits;
  }

  public function getDataForRenderer() {
    return [
      'namespace' => $this->namespace,
      'imports' => $this->imports,
      'name' => $this->className,
      'extends' => $this->extends,
      'implements' => $this->implements,
      'traits' => $this->traits,
    ];
  }
}
