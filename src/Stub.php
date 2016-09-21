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
   * @var string $classNamespace
   */
  private $classNamespace;

  /**
   * @var string $className
   */
  private $className;

  /**
   * The class that extends this class may override
   * this attribute to affect calculated class name
   * for generated class from stub.
   *
   * @var string $classNamePrefix
   */
  protected $classNamePrefix = '';

  /**
   * The class that extends this class may override
   * this attribute to affect calculated class name
   * for generated class from stub.
   *
   * @var string $classNameSuffix
   */
  protected $classNameSuffix = '';

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
  protected $imports = [];

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
   * @param string $type         Type of the stub (e.g. 'model', 'controller' etc.)
   * @param string $stubFileName Stub file name to be rendered.
   * @param null   $appNamespace Use the namespace for the app. For test purposes only.
   *
   * @throws \Exception
   */
  protected function __construct(string $type, $stubFileName = 'default', $appNamespace = null) {
    $this->type = strtolower(snake_case(str_singular($type)));

    // Set namespace right after setting the type
    //
    // Combine the application's namespace with the namespace
    //for the type from stubs.php config file
    if (null === $appNamespace) {
      $appNamespace = $this->getAppNamespace();
    }

    $namespaceOfType = config('stubs.namespaces.' . $this->type, null);

    if (null === $namespaceOfType) {
      throw new \Exception(
        'Either Stubs config was not published or the value of \'stubs.namespaces.' . $this->type .
        '\' is not valid. Because of this it is not possible to get correct namespace for ' . $this->type .
        ' type of stub.'
      );
    }

    $namespace = trim($appNamespace . '\\' . $namespaceOfType, '\\');

    // Remove trailing slash
    $this->classNamespace = str_replace_last('\\', '', $namespace);

    // Update imports, extends, implements and traits immediately after setting class' namespace
    $this->imports = Helpers::truncateFullyQualifiedNamespace($this->classNamespace, $this->imports, true);

    $namespaceAndImports = array_merge([$this->classNamespace], $this->imports);

    $this->extends = Helpers::truncateFullyQualifiedNamespace($namespaceAndImports, $this->extends);
    $this->implements = Helpers::truncateFullyQualifiedNamespace($namespaceAndImports, $this->implements, true);
    $this->traits = Helpers::truncateFullyQualifiedNamespace($namespaceAndImports, $this->traits, true);

    // Set stub file path right after setting the fileName
    //
    $this->stubFileName = Helpers::getStubsDirectory() . DIRECTORY_SEPARATOR . $this->type . DIRECTORY_SEPARATOR . $stubFileName . '.blade.php';
  }

  public function setName(string $name) {
    $this->className = $this->classNamePrefix . $this->normalizeNameToClassName($name) . $this->classNameSuffix;
    // TODO Change targetFileName if not suitable
    $this->targetFileName = title_case($this->className);
  }

  protected function normalizeNameToClassName(string $name) {
    return str_singular(camel_case($name));
  }

  public function setImports($imports) {
    $this->imports = Helpers::truncateFullyQualifiedNamespace($this->classNamespace, $imports, true);

    // Also update dependants
    $namespaceAndImports = array_merge([$this->classNamespace], $this->imports);

    $this->extends = Helpers::truncateFullyQualifiedNamespace($namespaceAndImports, $this->extends);
    $this->implements = Helpers::truncateFullyQualifiedNamespace($namespaceAndImports, $this->implements, true);
    $this->traits = Helpers::truncateFullyQualifiedNamespace($namespaceAndImports, $this->traits, true);
  }

  public function setExtends(string $extends) {
    // Merging the arrays because namespace must come first and then imports
    $namespaceAndImports = array_merge([$this->classNamespace], $this->imports);

    $this->extends = Helpers::truncateFullyQualifiedNamespace($namespaceAndImports, $extends);
  }

  public function setImplements($implements) {
    // Merging the arrays because namespace must come first and then imports
    $namespaceAndImports = array_merge([$this->classNamespace], $this->imports);

    $this->implements = Helpers::truncateFullyQualifiedNamespace($namespaceAndImports, $implements, true);
  }

  public function setTraits($traits) {
    // Merging the arrays because namespace must come first and then imports
    $namespaceAndImports = array_merge([$this->classNamespace], $this->imports);

    $this->traits = Helpers::truncateFullyQualifiedNamespace($namespaceAndImports, $traits, true);
  }

  public function getTargetFilePath() {
    $directoryPathToType = config('stubs.paths.targets.' . $this->type, null);

    if (null === $directoryPathToType) {
      throw new \Exception(
        'Either Stubs config was not published or the value of \'stubs.paths.targets.' . $this->type .
        '\' is not valid. Because of this it is not possible to get target file path for ' . $this->type .
        ' type of stub.'
      );
    }

    return realpath(base_path($directoryPathToType)) . DIRECTORY_SEPARATOR . $this->targetFileName . '.php';
  }

  public function getDataForRenderer() {
    return [
      'namespace' => $this->classNamespace,
      'imports' => $this->imports,
      'name' => $this->className,
      'extends' => $this->extends,
      'implements' => $this->implements,
      'traits' => $this->traits,
    ];
  }
}
