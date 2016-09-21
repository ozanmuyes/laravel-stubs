<?php

return [

  /**
   * Directories that holds stub files.
   *
   * Default is 'app/resources/stubs'.
   */
  'paths' => [
    realpath(base_path('resources/stubs')),

    /**
     * Indicates where to write created classes for each different
     * type of stub files (e.g. model, controllers etc.).
     */
    'targets' => [

      'model' => 'app',
      'controller' => 'app\\Http\\Controllers',
      //

    ],

  ],

  /**
   * MUST be relative to application's namespace
   */
  'namespaces' => [

    'model' => '',
    'controller' => 'Http\\Controllers',
    //

  ],

  /**
   * Default stub file renderer class here.
   *
   * Default is 'blade'.
   * Available settings;
   * - blade
   */
  'renderer' => 'blade',

];
