<?php

namespace Ozanmuyes\Stubs;

final class ModelStub extends Stub {
  /**
   * @var array $imports
   */
  private $imports = [
    'Illuminate\Database\Eloquent\Model'
  ];

  /**
   * @var string $extends
   */
  private $extends = 'Illuminate\Database\Eloquent\Model';
  // TODO Change it to 'Model' once Helpers::truncateFromStart test is OK

  /**
   * @var array $implements
   */
  private $implements = [];

  /**
   * @var array $traits
   */
  private $traits = [];

  public function __construct() {
    parent::__construct('model');

    parent::setImports($this->imports);
    parent::setExtends($this->extends);
    parent::setImplements($this->implements);
    parent::setTraits($this->traits);
  }
}
