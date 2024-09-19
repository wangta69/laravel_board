<?php

namespace Pondol\Bbs\Console;

use Illuminate\Console\Command;
// use Illuminate\Filesystem\Filesystem;
// use Illuminate\Support\Str;
// use Symfony\Component\Process\PhpExecutableFinder;
// use Symfony\Component\Process\Process;

class InstallCommand extends Command
{
  // use InstallsBladeStack;

  /**
   * The name and signature of the console command.
   *
   * @var string
   */
  protected $signature = 'bbs:install';

  /**
   * The console command description.
   *
   * @var string
   */
  protected $description = 'Install the Laravel Board controllers and resources';


  public function __construct()
  {
    parent::__construct();
  }

  public function handle()
  {
    return $this->installLaravelEditor();
  }


  private function installLaravelEditor()
  {
    // soft link
    \Artisan::call('storage:link');

    // editor
    \Artisan::call('vendor:publish',  [
      '--force'=> true,
      '--provider' => 'Pondol\Editor\EditorServiceProvider'
    ]);
    $this->info('The laravel editor installed successfully.'); 

    \Artisan::call('vendor:publish',  [
      '--force'=> true,
      '--provider' => 'Pondol\Bbs\BbsServiceProvider'
    ]);
    $this->info('The laravel board installed successfully.');

    $this->comment('Please execute the "php artisan migrate" commands to build market database.');
  }


}
