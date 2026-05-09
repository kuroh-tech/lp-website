<?php
if (!defined('ABSPATH')) {
    exit;
}

function tpi_bootstrap_upsert_page(string $title, string $slug, string $content = ''): int
{
    $existing = get_page_by_path($slug, OBJECT, 'page');

    if ($existing instanceof WP_Post) {
        return (int) $existing->ID;
    }

    return (int) wp_insert_post([
        'post_type' => 'page',
        'post_status' => 'publish',
        'post_title' => $title,
        'post_name' => $slug,
        'post_content' => $content,
    ]);
}

function tpi_bootstrap_site(): void
{
    if (get_option('tpi_demo_seeded') === '1') {
        return;
    }

    $front = tpi_bootstrap_upsert_page('トップ', 'home');
    tpi_bootstrap_upsert_page('会社概要', 'company');
    tpi_bootstrap_upsert_page('製品紹介', 'products');
    tpi_bootstrap_upsert_page('設備紹介', 'equipment');
    tpi_bootstrap_upsert_page('品質管理', 'quality');
    tpi_bootstrap_upsert_page('採用情報', 'recruit');
    tpi_bootstrap_upsert_page('お問い合わせ', 'contact');
    tpi_bootstrap_upsert_page('送信完了', 'thanks');
    tpi_bootstrap_upsert_page('プライバシーポリシー', 'privacy', "当社は、お問い合わせ・採用応募で取得した個人情報を、回答・連絡・選考の目的にのみ利用します。");

    update_option('show_on_front', 'page');
    update_option('page_on_front', $front);
    update_option('blogname', '東京精密工業株式会社');
    update_option('blogdescription', '精度が、信頼をつくる。');
    update_option('tpi_demo_seeded', '1');
    flush_rewrite_rules(false);
}

