<?php

namespace Ozanmuyes\Stubs\Tests;

use ReflectionClass;
use ReflectionMethod;

class HelpersTest extends \PHPUnit_Framework_TestCase {
  /**
   * @var string $className
   */
  private $className = \Ozanmuyes\Stubs\Helpers::class;

  /**
   * The class instance that testing against.
   *
   * @var \Ozanmuyes\Stubs\Helpers $inst
   */
  private $inst;

  /**
   * Get `protected` and `private` methods to test against.
   *
   * @param string $name
   *
   * @return ReflectionMethod
   */
  private function getMethod(string $name) {
    $class = new ReflectionClass($this->className);
    $method = $class->getMethod($name);

    $method->setAccessible(true);

    return $method;
  }

  protected function setUp() {
    parent::setUp();

    $this->inst = $this->getMockForAbstractClass($this->className);
  }

  private function truncateFullyQualifiedNamespaceTest($searches, $subjects, $expected) {
    $this->assertEquals(
      $expected,
      $this->getMethod('truncateFullyQualifiedNamespace')->invoke($this->inst, $searches, $subjects)
    );
  }

  public function testTruncateFromStart_1_1() {
    $namespace = 'App';
    $import = 'App\\User';

    $expected = 'User';

    $this->truncateFullyQualifiedNamespaceTest($namespace, $import, $expected);
  }

  public function testTruncateFromStart_1_2() {
    $namespace = 'App\\';
    $import = 'App\\User';

    $expected = 'User';

    $this->truncateFullyQualifiedNamespaceTest($namespace, $import, $expected);
  }

  public function testTruncateFromStart_1_3() {
    $namespace = 'App';
    $import = 'App\\User\\';

    $expected = 'User';

    $this->truncateFullyQualifiedNamespaceTest($namespace, $import, $expected);
  }

  public function testTruncateFromStart_2() {
    $namespace = 'App';
    $imports = [
      'App\\User',
      'App\\Post',
    ];

    $expected = [
      'User',
      'Post',
    ];

    $this->truncateFullyQualifiedNamespaceTest($namespace, $imports, $expected);
  }

  public function testTruncateFromStart_3_1() {
    $namespace = 'App';
    $imports = [
      'App\\User',
      'Illuminate\\Database\\Eloquent\\Model',
    ];
    $extends = [
      'Illuminate\\Database\\Eloquent\\Model',
    ];

    $namespaceAndImports = array_merge([$namespace], $imports);

    $expected = 'Model';

    $this->truncateFullyQualifiedNamespaceTest($namespaceAndImports, $extends, $expected);
  }

  public function testTruncateFromStart_3_2() {
    $namespace = 'App';
    $imports = [
      'App\\User',
      'Illuminate\\Database\\Eloquent\\Model',
    ];
    $extends = [
      'Illuminate\\Database\\Eloquent\\Model',
      'Http\\Request',
    ];

    $namespaceAndImports = array_merge([$namespace], $imports);

    $expected = [
      'Model',
      'Http\\Request',
    ];

    $this->truncateFullyQualifiedNamespaceTest($namespaceAndImports, $extends, $expected);
  }

  public function testTruncateFromStart_3_3() {
    $namespace = 'App';
    $imports = [
      'App\\User',
      'Illuminate\\Database\\Eloquent\\Model',
      'ArrayAccess',
    ];
    $extends = [
      'Illuminate\\Database\\Eloquent\\Model',
      'Http\\Request',
    ];

    $namespaceAndImports = array_merge([$namespace], $imports);

    $expected = [
      'Model',
      'Http\\Request',
    ];

    $this->truncateFullyQualifiedNamespaceTest($namespaceAndImports, $extends, $expected);
  }

  public function testTruncateFromStart_4() {
    $namespace = 'App';
    $imports = [
      'App\\User',
      'Illuminate\\Database\\Eloquent\\Model',
      'ArrayAccess',
    ];
    $extends = [
      'Illuminate\\Database\\Eloquent\\Model',
      'Http\\Request',
      'App\\User',
    ];

    $namespaceAndImports = array_merge([$namespace], $imports);

    $expected = [
      'Model',
      'Http\\Request',
      'User',
    ];

    $this->truncateFullyQualifiedNamespaceTest($namespaceAndImports, $extends, $expected);
  }
}
