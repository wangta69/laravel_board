<?php

namespace Pondol\Bbs\Console;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Schema;
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

    // users 테이블이 있는지 확인
    // $user_password = $this->ask('Password for administrator?'); 
    if (!Schema::hasTable('users')) {
      $this->info('no users table. Install laravel/breeze or other auth');
    };

    \Artisan::call('migrate');

    $this->info('The laravel board installed successfully.');

    // $this->comment('Please execute the "php artisan migrate" commands to build market database.');
  }


}
