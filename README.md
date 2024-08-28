# Waht is pondol's Laravel Board

> 한국형 라라벨용 게시판 입니다. <br />
현재 진행중인 프로젝트에 사용하려고 간단하게 만들고 테스트 중입니다. <br />
 
> This package was tested on Laravel 8.x


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

### publish all Resoucres
```
php artisan vendor:publish --provider="Pondol\Bbs\BbsServiceProvider"
php artisan vendor:publish --provider="Pondol\Editor\EditorServiceProvider"
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

### functions
#### bbs_get_thumb
> realtime thumbnail generator
```
<img src="{{bbs_get_thumb($article->image, 205, 205) }}" alt="{{$article->title}}">
```
#### bbs_get_latest
> if you want some data to dispay from bbs, follow below explain 
```
public function Anything()
{
	$notice = bbs_get_latest(array('table'=>'notice', 'cnt'=>5));
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

