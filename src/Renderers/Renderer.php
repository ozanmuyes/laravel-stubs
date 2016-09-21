<?php

namespace Ozanmuyes\Stubs\Renderers;

use Ozanmuyes\Stubs\Stub;

/**
 * TODO Populate Renderer interface
 * @see and implement \Illuminate\View\Compilers\CompilerInterface
 * @see \Illuminate\View\Compilers\Compiler
 * @see \Illuminate\View\Compilers\BladeCompiler
 */
interface Renderer {
  /**
   * Get renderer type (e.g. 'blade', 'template' etc.)
   *
   * @return string
   */
  function getType();

  function render(Stub $stub);
}
