<?php

namespace Ozanmuyes\Stubs\Tests;

use ReflectionClass;
use ReflectionMethod;

class ModelStubTest extends \PHPUnit_Framework_TestCase {
  /**
   * @var string $className
   */
  private $className = \Ozanmuyes\Stubs\ModelStub::class;

  /**
   * The class instance that testing against.
   *
   * @var \Ozanmuyes\Stubs\ModelStub $inst
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

    $this->inst = new $this->className('app');
  }

  public function testType() {
    $this->assertEquals('', $this->getMethod('getType'));
  }
}
