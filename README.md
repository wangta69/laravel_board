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

- **Korean Style BBS:** Hierarchical posts (Reply), Comments, Secret posts with session-based access control.
- **Universal Admin Layout:** Seamlessly integrates with both Blade Components (`<x-...>`) and traditional `@extends` admin templates.
- **Event-Driven Architecture:** Dispatches events for post/comment creation, updates, and deletion.
- **Thumbnails:** Real-time thumbnail generator helper with Laravel Storage disk support.
- **Widgets:** Latest posts widget helper.
- **Comments Component:** Easily add comment functionality to any model.

## 📦 Installation

### Requirements

- PHP >= 8.0 (Tested on PHP 8.0 ~ 8.3)
- Laravel >= 8.x (Tested on 8.x ~ 11.x)
- Bootstrap 5.x
- jQuery 3.6.x

### 1. Require the package via Composer

```bash
composer require wangta69/laravel-board
```

### 2. Install Assets & Config

Run the installation command to publish assets, configuration, and migration files.

```bash
php artisan pondol:install-bbs
```

## 🛠 Configuration & Usage

### 1. Admin Security Setup

After installation, secure the Admin Controller by setting up middleware or permissions.

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

### 2. Universal Admin Layout Configuration

You can configure the admin layout in `config/pondol-bbs.php` to seamlessly blend into your existing project:

```php
// config/pondol-bbs.php
'admin' => [
    'layout' => [
        // 1) Component format (e.g. 'pondol-common::common-admin')
        // 2) Traditional @extends format (e.g. 'admin.layouts.admin')
        // 3) null: Runs standalone with package default layout
        'name' => 'pondol-common::common-admin',

        // LNB sidebar view (set null to use host's native sidebar)
        'lnb' => 'bbs::lnb-partial',

        // Section name for @extends mode (default: 'content')
        'section' => 'content',

        // Title section name for @extends mode (default: 'title')
        'title_section' => 'title',
    ],
],
```

### 3. Create a Board

1. Access the admin panel: `http://YourDomain/bbs/admin`
2. Create a new board configuration (e.g., table name: `notice`).
3. (Optional) If you need a role management system:
   ```bash
   php artisan make:model Role -m
   ```

### 4. Access the Board

- **Admin URL:** `http://YourDomain/bbs/admin/tbl/{table_name}`
- **User URL:** `http://YourDomain/bbs/{table_name}`

## 🎨 Helpers & Components

### Real-time Thumbnail

Generate thumbnails on the fly using Laravel's Storage system.

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

You can attach a comment section to any arbitrary model or page.

```html
<x-item-comments item="story" :itemId="$story->id" skin="default" />
```

- **skin:** Skin name (default: `'default'`).
- **item:** Target category or model name (string).
- **itemId:** Unique ID of the target content.

## ⚡ Events & Webhooks (이벤트 시스템)

Laravel Board dispatches events on post and comment lifecycle activities. You can hook into these events in your host application to trigger Telegram alerts, push notifications, reward points, or logging.

### Available Events

| Event Class                        | Description                       | Properties                                                          |
| :--------------------------------- | :-------------------------------- | :------------------------------------------------------------------ |
| `Pondol\Bbs\Events\ArticleCreated` | 새 글이 등록되었을 때 발생        | `$event->article`, `$event->cfg`, `$event->user`                    |
| `Pondol\Bbs\Events\ArticleUpdated` | 글이 수정되었을 때 발생           | `$event->article`, `$event->cfg`, `$event->user`                    |
| `Pondol\Bbs\Events\ArticleDeleted` | 글이 삭제되었을 때 발생           | `$event->article`, `$event->cfg`, `$event->user`                    |
| `Pondol\Bbs\Events\CommentCreated` | 새 댓글/답글이 등록되었을 때 발생 | `$event->comment`, `$event->article`, `$event->cfg`, `$event->user` |

### Listening to Events

Register listeners in your `App\Providers\EventServiceProvider.php` (or `AppServiceProvider.php`):

```php
use Pondol\Bbs\Events\ArticleCreated;
use Pondol\Bbs\Events\CommentCreated;
use Illuminate\Support\Facades\Event;
use App\Helpers\TelegramHelper;

public function boot()
{
    // Example 1: Send Telegram alert when a new post is created
    Event::listen(ArticleCreated::class, function (ArticleCreated $event) {
        $message = "📢 [새 글 등록] <b>{$event->cfg->name}</b>\n";
        $message .= "제목: {$event->article->title}\n";
        $message .= "작성자: {$event->article->writer}";

        TelegramHelper::send($message);
    });

    // Example 2: Notify the post author when a new comment is posted
    Event::listen(CommentCreated::class, function (CommentCreated $event) {
        // Custom logic: Send notification to $event->article->user_id
    });
}
```

## 📂 Skin Customization (스킨 커스터마이징)

Laravel Board fully supports Laravel's native **Vendor View Overriding**.

To customize templates, publish the views:

```bash
php artisan vendor:publish --tag=bbs-views
```

Templates will be copied to `resources/views/vendor/bbs/templates`.

- **Zero Configuration:** If uncustomized, the package automatically falls back to its built-in default skins.
- **Pinpoint Customization:** You only need to keep the files you modified in `resources/views/vendor/bbs/templates/user/{skin}/`. Unmodified templates can safely be deleted from your Git repository.
- **Add Custom Skins:** Create a folder inside `resources/views/vendor/bbs/templates/user/your-skin-name`, and it will automatically appear in the admin board configuration selector.

## 🛠 SEO & Meta Data Customization

Laravel Board supports dynamic metadata management through `Pondol\Meta`. You can customize metadata by implementing a custom Resolver.

### 1. Create a Custom Meta Resolver

Create a class implementing `Pondol\Bbs\Contracts\BbsMetaResolver`:

```php
namespace App\Services;

use Pondol\Bbs\Contracts\BbsMetaResolver;
use Illuminate\Support\Str;

class MyBbsMetaResolver implements BbsMetaResolver
{
    /**
     * @param \Pondol\Meta\Meta $meta
     * @param string $type 'index' or 'show'
     * @param object $cfg Board Configuration (BbsTables)
     * @param object|null $article Article Data (BbsArticles)
     * @param mixed|null $collection Articles Collection (on index)
     */
    public function resolve($meta, $type, $cfg, $article = null, $collection = null)
    {
        if ($cfg->table_name === 'notice') {
            $meta->title("[Notice] " . ($article ? $article->title : $cfg->name));
        }

        if ($type === 'show' && $article) {
            $description = Str::limit(strip_tags($article->content), 160);
            $meta->description($description);

            if ($article->image) {
                $meta->image(\Storage::disk('public')->url($article->image));
            }
        }

        return $meta;
    }
}
```

### 2. Register the Resolver

Bind your custom class in `App\Providers\AppServiceProvider.php`:

```php
public function register()
{
    $this->app->bind(
        \Pondol\Bbs\Contracts\BbsMetaResolver::class,
        \App\Services\MyBbsMetaResolver::class
    );
}
```

## 📜 License

The MIT License (MIT). Please see [License File](LICENSE) for more information.
