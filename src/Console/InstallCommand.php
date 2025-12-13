<?php
namespace Pondol\Bbs\Console;

use Illuminate\Console\Command;

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

    // $this->call('pondol:install-common'); pondol:install-meta 에서 이 부분 실행
    $this->call('pondol:install-meta');
    $this->call('pondol:install-editor'); // soft storage link and editor resource publish
    

    \Artisan::call('vendor:publish',  [
      '--force'=> true,
      '--provider' => 'Pondol\Bbs\BbsServiceProvider'
    ]);

    if($type === 'full') { // auth system 인스톨
      $this->call('pondol:install-auth', ['type'=> 'simple']);
    }

    \Artisan::call('migrate');
    $this->info("The pondol's laravel bbs installed successfully.");

  }

}
