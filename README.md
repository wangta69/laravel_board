# Laravel Board (K-BBS)

A Korean-style Bulletin Board System (BBS) package for Laravel.  
한국형 계층형 게시판(BBS)을 라라벨에서 쉽고 빠르게 구축하기 위한 패키지입니다.

[![Latest Stable Version](https://poser.pugx.org/wangta69/laravel-board/v/stable)](https://packagist.org/packages/wangta69/laravel-board)
[![License](https://poser.pugx.org/wangta69/laravel-board/license)](https://packagist.org/packages/wangta69/laravel-board)

## 💡 Overview

This library is designed to implement the hierarchical board structure (List, View, Write, Reply, Comment) commonly used in Korea within the Laravel environment. It supports **Bootstrap 5** by default.

이 라이브러리는 **[길라(Gilra)](https://gilra.kr)** (Online Fortune Service)의 커뮤니티 및 공지사항 기능을 구축하는 데 실제로 사용되었습니다.

- **Demo:** [Live Demo Link](https://www.onstory.fun/community)
- **Official Docs:** [Documentation](https://www.onstory.fun/packages/laravel-board)

## 🚀 Features

- **Korean Style BBS:** Hierarchical posts (Reply), Comments, Secret posts.
- **Admin Panel:** Built-in controller and views for board management.
- **Thumbnails:** Real-time thumbnail generator helper.
- **Widgets:** Latest posts widget helper.
- **Comments Component:** Easily add comment functionality to any model.

## 📦 Installation

### Requirements

- PHP >= 7.4
- Laravel >= 8.x (Tested on 8.x ~ 11.x)
- Bootstrap 5.x
- jQuery 3.6.x

### 1. Require the package via Composer

```bash
composer require wangta69/laravel-board
```

### 2. Install Assets & Config

Run the installation command to publish assets and migration files.

```bash
php artisan pondol:install-bbs
```

## 🛠 Configuration & Usage

### 1. Admin Security Setup

After installation, you should secure the Admin Controller.
Go to `App/Http/Controllers/Bbs/Admin/BbsController.php` (or similar admin controllers) and set up the middleware or permission check in the `__construct` method.

```php
public function __construct()
{
    $this->middleware('auth');

    // Example: Check for administrator role
    // if (!Auth::user()->hasRole('administrator')) {
    //     abort(403, 'Unauthorized action.');
    // }
}
```

### 2. Create a Board

1. Access the admin panel: `http://YourDomain/bbs/admin`
2. Create a new board configuration (e.g., table name: `notice`).
3. (Optional) If you need a role management system:
   ```bash
   php artisan make:model Role -m
   ```

### 3. Access the Board

- **Admin URL:** `http://YourDomain/bbs/admin/tbl/{table_name}`
- **User URL:** `http://YourDomain/bbs/{table_name}`

## 🎨 Helpers & Components

### Real-time Thumbnail

Generate thumbnails on the fly.

```html
<!-- usage: bbs_get_thumb($image_path, $width, $height) -->
<img
  src="{{ bbs_get_thumb($article->image, 205, 205) }}"
  alt="{{ $article->title }}"
/>
```

### Latest Posts Widget

Display the latest posts from a specific board.

```php
public function index()
{
    // usage: bbs_get_latest(['table' => 'table_name', 'cnt' => count])
    $notices = bbs_get_latest(['table' => 'notice', 'cnt' => 5]);

    return view('welcome', compact('notices'));
}
```

### Forum (Comment) Component

You can attach a comment section to any arbitrary model or page, not just the BBS.

```html
<x-item-comments item="story" :itemId="$story->id" skin="default" />
```

- **skin:** Skin name (currently 'default' is available).
- **item:** Target category or model name (string).
- **itemId:** Unique ID of the target content.

## 📂 Customization

To customize the templates, look into the `resources/views/bbs/templates` directory. You can duplicate an existing template and modify it to create your own skin.

## 📜 License

The MIT License (MIT). Please see [License File](LICENSE) for more information.
