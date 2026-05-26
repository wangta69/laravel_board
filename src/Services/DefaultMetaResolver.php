<?php

namespace Pondol\Bbs\Services;

use Illuminate\Support\Str;
use Pondol\Bbs\Contracts\BbsMetaResolver;

class DefaultMetaResolver implements BbsMetaResolver
{
    public function resolve($meta, $type, $cfg, $article = null, $collection = null)
    {
        // 페이지네이션 번호 가져오기 (중복 타이틀 방지용)
        $page = request('page', 1);
        $pageSuffix = ($page > 1) ? " - {$page}페이지" : '';

        if ($type === 'index') {
            // --- [1. 목록 페이지(Index) SEO 설정] ---
            $title = $cfg->name.$pageSuffix;
            $description = $cfg->name.' 게시판의 최신 소식과 정보를 확인하세요.';

            // --- [핵심 추가] 게시물 리스트(ItemList) 생성 ---
            $itemListElement = [];
            if ($collection) {
                foreach ($collection as $index => $item) {
                    $itemListElement[] = [
                        '@type' => 'ListItem',
                        'position' => $index + 1, // 순서
                        'url' => route('bbs.show', [$cfg->table_name, $item->id]),
                        'name' => $item->title,
                    ];
                }
            }

            $meta->title($title)
                ->description($description)
                ->canonical(url()->current())
                ->type('CollectionPage');

            $meta->structuredData([
                [
                    '@context' => 'https://schema.org',
                    '@type' => 'CollectionPage',
                    'name' => $title,
                    'description' => $description,
                    'url' => url()->current(),
                    // [추가] 리스트 데이터 삽입
                    'mainEntity' => [
                        '@type' => 'ItemList',
                        'numberOfItems' => count($itemListElement),
                        'itemListElement' => $itemListElement,
                    ],
                ],
                // BreadcrumbList는 기존과 동일하게 유지
                [
                    '@context' => 'https://schema.org',
                    '@type' => 'BreadcrumbList',
                    'itemListElement' => [
                        ['@type' => 'ListItem', 'position' => 1, 'name' => config('app.name'), 'item' => url('/')],
                        ['@type' => 'ListItem', 'position' => 2, 'name' => $cfg->name, 'item' => route('bbs.index', $cfg->table_name)],
                    ],
                ],
            ]);

        } else {
            // --- [2. 상세 페이지(Show) SEO 설정] (기존 로직 유지 및 정돈) ---
            $cleanDesc = preg_replace('/\s+/', ' ', strip_tags($article->content));
            $description = Str::limit($cleanDesc, 160);
            $imageUrl = $article->image ? \Storage::url($article->image) : asset('assets/images/default-og.png');

            $meta->title($article->title)
                ->description($description)
                ->canonical(url()->current())
                ->keywords($article->keywords)
                ->image($imageUrl)
                ->type('Article');

            $meta->structuredData([
                // [객체 1] Article
                [
                    '@context' => 'https://schema.org',
                    '@type' => 'Article',
                    'mainEntityOfPage' => [
                        '@type' => 'WebPage',
                        '@id' => url()->current(),
                    ],
                    'headline' => $article->title,
                    'description' => $description,
                    'image' => [$imageUrl],
                    'datePublished' => $article->created_at->toIso8601String(),
                    'dateModified' => $article->updated_at->toIso8601String(),
                    'author' => [
                        '@type' => 'Person',
                        'name' => $article->writer ?? config('app.name'),
                    ],
                    'publisher' => [
                        '@type' => 'Organization',
                        'name' => config('app.name'),
                        'logo' => [
                            '@type' => 'ImageObject',
                            'url' => asset('ci.webp'),
                        ],
                    ],
                ],
                // [객체 2] BreadcrumbList (홈 > 게시판명 > 글제목)
                [
                    '@context' => 'https://schema.org',
                    '@type' => 'BreadcrumbList',
                    'itemListElement' => [
                        [
                            '@type' => 'ListItem',
                            'position' => 1,
                            'name' => config('app.name', 'Home'),
                            'item' => url('/'),
                        ],
                        [
                            '@type' => 'ListItem',
                            'position' => 2,
                            'name' => $cfg->name,
                            'item' => route('bbs.index', $cfg->table_name),
                        ],
                        [
                            '@type' => 'ListItem',
                            'position' => 3,
                            'name' => Str::limit($article->title, 20),
                            'item' => url()->current(),
                        ],
                    ],
                ],
            ]);
        }

        return $meta;
    }
}
