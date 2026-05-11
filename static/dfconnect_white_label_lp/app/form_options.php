<?php

declare(strict_types=1);

return [
    'company_type' => [
        'web-agency' => 'Web制作会社',
        'ad-agency' => '広告代理店',
        'design-agency' => 'デザイン会社',
        'freelance' => 'フリーランス',
        'other' => 'その他',
    ],
    'inquiry_type' => [
        'coding_lp' => 'コーディング・LP実装',
        'wordpress' => 'WordPress制作・改修',
        'maintenance' => '運用保守・軽微修正',
        'form_check' => '公開前チェック・フォーム確認',
        'white_label_nda' => 'NDA・貴社名義での進行相談',
        'undecided' => 'まだ決まっていない',
    ],
    'response_style' => [
        'backend-only' => '完全裏方で依頼したい',
        'mtg-ok' => '必要時にMTG同席してほしい',
        'from-planning' => '要件整理から相談したい',
        'small-start' => 'まずは小さな修正を相談したい',
        'undecided' => 'まだ決まっていない',
    ],
    'desired_timing' => [
        'asap' => 'すぐ',
        '1week' => '1週間以内',
        '1month' => '1ヶ月以内',
        '3months' => '3ヶ月以内',
        'undecided' => '未定',
    ],
    'budget_range' => [
        'undecided' => '未定',
        'under-30k' => '〜3万円',
        '30k-100k' => '3〜10万円',
        '100k-300k' => '10〜30万円',
        'over-300k' => '30万円以上',
        'ongoing' => '継続相談',
    ],
    'nda' => [
        'yes' => '希望する',
        'if-needed' => '必要に応じて',
        'no' => '現時点では不要',
    ],
    'contact_method' => [
        'email' => 'メール',
        'online-mtg' => 'オンラインMTG',
        'either' => 'どちらでも',
    ],
];
