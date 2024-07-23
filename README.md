# Why wizboard for laravel

> 한국형 라라벨용 게시판 입니다. <br />
현재 진행중인 프로젝트에 사용하려고 간단하게 만들고 테스트 중입니다. <br />
 
> This package was testec Laravel 8.x


[Homepage](https://www.onstory.fun/doc/programming/laravel/package.laravelboard)

[goto old version 2.x](./documents/5.x.md)


## Installation
```
composer require wangta69/laravel_board
```


## Setup
- design : bootstrp 5.x
- jquery : 3.6.x

### publish stuff
```
 php artisan vendor:publish
```

### create storage soft link
```
php artisan storage:link
```

## How to Use
### Set Security for Admin.
> After Install, Goto App/Http/Controllers/Bbs/Admin and you can find controllers for Admin.<br>
Set Access Auth on __construct for security <br>
```
if(!Auth::user()->hasRole('administrator')) => hasRole('Your Admin Role name')
```

### Login To Admin
> Type url http://YourDomain/bbs/admin and Create What you want Bbs.  <br />
If you have rolls not yet,  do 'php artisan make:model Role -m' <br />

### Create BBS 
> You can make any bbs what you want <br>
> After create bbs link for admin : http://YourDomain//bbs/admin/tbl/[table name] <br>
> link for user : http://YourDomain//bbs/[table name] <br>

### Extract data only
> if you want some data to dispay from bbs, follow below explain 
```
use Pondol\Bbs\BbsService;
public function Anything()
{
	$pds = BbsService::get_latest(array('id'=>3, 'cnt'=>4));
}
```

### Make Additional Template
Go to resources/views/bbs/templates And add a template like exist template


### Forum
> if you have any article not bbs and you want add comment like forum, just insert below code
```
<x-item-comments item="story" :itemId="1" skin="default"/>
```
- skin : currently only 'default' skin exist(i'm gonna add more skins)
- item : what you want (string type)
- itemId : this is important one, if you have many articles, you put itemId for every articles

