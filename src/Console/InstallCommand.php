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
  // protected $signature = 'bbs:install';
  protected $signature = 'pondol:install-bbs {type=full}'; // full, simple, skip, only
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
    $type = $this->argument('type');
    return $this->installLaravelBoard($type);
  }

  /**
   * @params String $type simple: editor와 bbs만 인스톨 
   */
  private function installLaravelBoard($type)
  {
    if ($type === 'skip') return; // 타 컴포저에서 테스트 시


    $this->call('pondol:install-editor'); // soft storage link and editor resource publish

    \Artisan::call('vendor:publish',  [
      '--force'=> true,
      '--provider' => 'Pondol\Bbs\BbsServiceProvider'
    ]);

    if($type === 'full') { // auth system 인스톨
      $this->call('pondol:install-auth simple');
    }

    \Artisan::call('migrate');
    $this->info("The pondol's laravel board installed successfully.");

    // $this->comment('Please execute the "php artisan migrate" commands to build market database.');
  }


}
