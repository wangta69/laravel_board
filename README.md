# Why wizboard for laravel

한국형 라라벨용 게시판 입니다. <br />
현재 진행중인 프로젝트에 사용하려고 간단하게 만들고 테스트 중입니다. <br />
 <br />
Laravel 5.4에서 테스트 되었습니다.

## Installation
```
composer require wangta69/laravel_board
```

## Laravel 5

### Setup
Step 1) Add ServiceProvider to the providers array in `config/app.php`.
```
Pondol\Board\BoardServiceProvider::class,
```

Step 2)

```
php artisan vendor:publish
```

Step 3) create tables
```
php artisan migrate
```


## 관리자 페이지 설정

컨트롤러를 하나 생성 후 \Pondol\Board\AdminController를 상속합니다.

```
php artisan make:controller BoardAdminController

class BoardAdminController extends \Pondol\Board\AdminController
{
	...
}
```

### 라우팅 설정
```
Route::resource('bbs/admin', '게시판 관리자 컨트롤러');
Route::resource('bbs/{bo_id}/board', '게시판 컨트롤러');
```