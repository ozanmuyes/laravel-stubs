<?php

namespace Ozanmuyes\Stubs\Console\Commands;

class StubModelCommand extends StubCommand {
  /**
   * The name and signature of the console command.
   *
   * @var string
   */
  protected $signature = '
    stub:model
    {name: The name of the model}
    {-F|--file: Indicates which stub file should be used}
    {-M|--migration: Also creates a migration file}
    {-S|--seeder: Also creates a seeder file}
  ';

  /**
   * The console command description.
   *
   * @var string
   */
  protected $description = 'Create a new model class';

  /**
   * Execute the consequent console command(s) if any (e.g. create migration,
   * seeder for model).
   *
   * @return void
   */
  protected function afterHandle() {
    // Check if `migration` option set
    if ($this->option('migration')) {
      // TODO Test if `migration` is enough, or should I check for the `M` as well?
var_dump("migration");
    }

    // TODO Check also if seeder option set
  }
}
