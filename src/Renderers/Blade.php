<?php

namespace Ozanmuyes\Stubs\Renderers;

use Illuminate\View\Compilers\BladeCompiler;
use Ozanmuyes\Stubs\Helpers;
use Ozanmuyes\Stubs\Stub;

class Blade implements Renderer {
  private $blade;

  public function __construct(BladeCompiler $blade) {
    $this->blade = $blade;
  }

  /**
   * Get renderer type (e.g. 'blade', 'template' etc.)
   *
   * @return string
   */
  function getType() {
    return 'blade';
  }

  function render(Stub $stub) {
    $stubFilePath = Helpers::getStubsDirectory() . DIRECTORY_SEPARATOR . $stub->getType() . DIRECTORY_SEPARATOR . $stub->getStubFileName() . '.blade.stub';

    $this->blade->compile($stubFilePath);
  }
}
