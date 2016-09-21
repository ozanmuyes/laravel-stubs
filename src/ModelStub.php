<?php

namespace Ozanmuyes\Stubs;

final class ModelStub extends Stub {
  /**
   * @var array $imports
   */
  protected $imports = [
    \Illuminate\Database\Eloquent\Model::class,
  ];

  /**
   * @var string $extends
   */
  protected $extends = \Illuminate\Database\Eloquent\Model::class;

  /**
   * @var array $implements
   */
  protected $implements = [];

  /**
   * @var array $traits
   */
  protected $traits = [];

  /**
   * ModelStub constructor.
   *
   * @param null $appNamespace Use the namespace for the app. For test purposes only.
   */
  public function __construct($appNamespace = null) {
    parent::__construct('model', 'default', $appNamespace);
  }
}
