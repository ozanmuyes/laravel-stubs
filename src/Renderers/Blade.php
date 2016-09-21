<?php

namespace Ozanmuyes\Stubs\Renderers;

use Ozanmuyes\Stubs\Stub;

class Blade implements Renderer {
  /**
   * Get renderer type (e.g. 'blade', 'template' etc.)
   *
   * @return string
   */
  function getType() {
    return 'blade';
  }

  function render(Stub $stub) {
    // TODO: Implement render() method.
  }
}
