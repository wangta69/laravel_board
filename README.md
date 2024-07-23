# Why wizboard for laravel

한국형 라라벨용 게시판 입니다. <br />
현재 진행중인 프로젝트에 사용하려고 간단하게 만들고 테스트 중입니다. <br />
 <br />
Laravel 5.4에서 테스트 되었습니다.<br />

## Installation
```
composer require wangta69/laravel_board
```

## Laravel 5

## Setup
Step 1.1) Add ServiceProvider to the providers array in `config/app.php`.
```
Pondol\Bbs\BbsServiceProvider::class,
```
Step 1.2) Add Facade to the aliases array in `config/app.php`.
```
'Bbs' => Pondol\Bbs\BbsFacade::class,
```

Step 2) publish stuff
```
 php artisan vendor:publish
```

Step 3) create storage for bbs
Step 3.1) make 'app' Directory on your storage and add 'rw' (if directory exist skip)
```
#mkdir storage/app
#chmod 707 storage/app
```
Step 3.2) make softLink to your public directory
```
#ln -s ../storage/app/public public/storage
```

Step 4) copy config file (bbs.php)
```
cp ./vendor/wangta69/laravel_board/src/Https/config/bbs.php ./config/bbs.php
```

## How to Use
### Login To Admin
After Install, Goto App/Http/Controllers/Bbs/AdminController.php
```
if(!Auth::user()->hasRole('administrator')) => hasRole('Your Admin Role name')
```
Type url http://YourDomain/bbs/admin and Create What you want Bbs.  <br />

If you have rolls not yet,  do 'php artisan make:model Role -m' <br />

### Users
Go to /vendor/wangta69/laravel_board/src/MOdel/bbs_articles and modify user() according to app condition;
```
public function user() {
	return $this->belongsTo('App\User');
}
```


### Extract data only
```
use Pondol\Bbs\BbsService;
public function Anything()
{
	$pds = BbsService::get_latest(array('id'=>3, 'cnt'=>4));
}
```

### Change Layout
Go to App/Http/Controllers/Bbs/AdminController.php or  BoardController.php
```
protected $blade_extends = null; to protected $blade_extends = 'SomeThing you want. example 'admin.layouts.admin';

or
[controllers]
$bbsparams = BbsService::create_params(array('blade_extends' =>'vendor.layouts.vendor'));
[blade]
{!! Html::link(route('bbs.show', ['notice', $notice->id, 'urlParams='.$bbsparams->enc]), $notice->title) !!}
```

### Make Additional Template
Go to resources/views/bbs/templates And add a template like exist template


### Copied File/Dir Info
After Install this package make copy to  <br />
app - Http - Controllers - Bbs  <br />
public - assets - pondol - bbs  <br />
resources - views - bbs  <br />
