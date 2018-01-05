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
Step 1.1) Add ServiceProvider to the providers array in `config/app.php`.
```
Pondol\Bbs\BoardServiceProvider::class,
```
Step 1.2) Add Facade to the aliases array in `config/app.php`.
```
'Bbs' => Pondol\Bbs\BbsFacade::class,
```


Step 2)

```
php artisan vendor:publish
```

Step 3) create tables
```
php artisan migrate
```

Step 4) create storage link <br />

```
ln -s [Absolute Path]/storage/app/public [Absolute Path]/public/storage
```