<?php
if (!defined('ABSPATH')) {
    exit;
}

function livesta_bootstrap_find_post(string $title, string $post_type): ?WP_Post
{
    $posts = get_posts([
        'post_type' => $post_type,
        'title' => $title,
        'post_status' => 'any',
        'posts_per_page' => 1,
    ]);

    return $posts ? $posts[0] : null;
}

function livesta_bootstrap_find_post_by_slug(string $slug, string $post_type): ?WP_Post
{
    $slug = trim($slug, '/');

    if ($slug === '') {
        return null;
    }

    $existing = get_page_by_path($slug, OBJECT, $post_type);
    if ($existing instanceof WP_Post) {
        return $existing;
    }

    $posts = get_posts([
        'post_type' => $post_type,
        'name' => $slug,
        'post_status' => 'any',
        'posts_per_page' => 1,
    ]);

    return $posts ? $posts[0] : null;
}

function livesta_bootstrap_upsert_post(array $postarr): int
{
    $post_type = isset($postarr['post_type']) ? (string) $postarr['post_type'] : 'post';
    $post_title = isset($postarr['post_title']) ? (string) $postarr['post_title'] : '';
    $post_name = isset($postarr['post_name']) ? sanitize_title((string) $postarr['post_name']) : '';

    $existing = null;

    if ($post_name !== '') {
        $existing = livesta_bootstrap_find_post_by_slug($post_name, $post_type);
    }

    if (!$existing instanceof WP_Post && $post_title !== '') {
        $existing = livesta_bootstrap_find_post($post_title, $post_type);
    }

    if ($post_name !== '') {
        $postarr['post_name'] = $post_name;
    }

    if ($existing instanceof WP_Post) {
        $postarr['ID'] = (int) $existing->ID;
        $result = wp_update_post($postarr, true);
    } else {
        $result = wp_insert_post($postarr, true);
    }

    if (is_wp_error($result)) {
        return 0;
    }

    return (int) $result;
}

function livesta_bootstrap_sync_news_posts(): void
{
    if (!function_exists('livesta_get_sample_news')) {
        return;
    }

    foreach (livesta_get_sample_news() as $item) {
        if (!is_array($item)) {
            continue;
        }

        $title = isset($item['title']) ? (string) $item['title'] : '';
        if ($title === '') {
            continue;
        }

        $post_date = isset($item['date']) ? date('Y-m-d 09:00:00', strtotime(str_replace('.', '-', (string) $item['date'])) ?: time()) : current_time('mysql');
        $post_id = livesta_bootstrap_upsert_post([
            'post_type' => 'post',
            'post_status' => 'publish',
            'post_title' => $title,
            'post_name' => isset($item['slug']) ? (string) $item['slug'] : '',
            'post_excerpt' => isset($item['excerpt']) ? (string) $item['excerpt'] : '',
            'post_content' => isset($item['content']) ? (string) $item['content'] : '',
            'post_date' => $post_date,
        ]);

        if ($post_id <= 0) {
            continue;
        }

        $category_slug = function_exists('livesta_get_sample_news_category_slug')
            ? livesta_get_sample_news_category_slug($item)
            : 'news';
        $category_name = isset($item['category']) ? (string) $item['category'] : 'お知らせ';
        $term = get_term_by('slug', $category_slug, 'category');

        if (!$term instanceof WP_Term) {
            $inserted = wp_insert_term($category_name, 'category', ['slug' => $category_slug]);
            if (!is_wp_error($inserted) && isset($inserted['term_id'])) {
                $term = get_term((int) $inserted['term_id'], 'category');
            }
        }

        if ($term instanceof WP_Term) {
            wp_set_post_terms($post_id, [(int) $term->term_id], 'category');
        }
    }
}

function livesta_bootstrap_upsert_page(string $title, string $slug, string $content = '', int $parent_id = 0): int
{
    $path = $slug;
    if ($parent_id > 0) {
        $parent_path = trim((string) get_page_uri($parent_id), '/');
        if ($parent_path !== '') {
            $path = $parent_path . '/' . $slug;
        }
    }

    $existing = get_page_by_path($path, OBJECT, 'page');

    if ($existing instanceof WP_Post) {
        $updates = [
            'ID' => (int) $existing->ID,
        ];

        if ((int) $existing->post_parent !== $parent_id) {
            $updates['post_parent'] = $parent_id;
        }

        if ($existing->post_title !== $title) {
            $updates['post_title'] = $title;
        }

        if ($content !== '' && trim((string) $existing->post_content) === '') {
            $updates['post_content'] = $content;
        }

        if (count($updates) > 1) {
            wp_update_post($updates);
        }

        return (int) $existing->ID;
    }

    if ($parent_id > 0) {
        $existing = get_page_by_path($slug, OBJECT, 'page');

        if ($existing instanceof WP_Post) {
            $updates = [
                'ID' => (int) $existing->ID,
                'post_parent' => $parent_id,
            ];

            if ($existing->post_title !== $title) {
                $updates['post_title'] = $title;
            }

            if ($content !== '' && trim((string) $existing->post_content) === '') {
                $updates['post_content'] = $content;
            }

            wp_update_post($updates);

            return (int) $existing->ID;
        }
    }

    return (int) wp_insert_post([
        'post_type' => 'page',
        'post_status' => 'publish',
        'post_title' => $title,
        'post_name' => $slug,
        'post_parent' => $parent_id,
        'post_content' => $content,
    ]);
}

function livesta_bootstrap_site(): void
{
    $is_seeded = get_option('livesta_demo_seeded') === '1';

    $home_id = livesta_bootstrap_upsert_page('トップ', 'home');
    $about_id = livesta_bootstrap_upsert_page('会社概要', 'about');
    $service_id = livesta_bootstrap_upsert_page('事業内容', 'service');
    $contact_id = livesta_bootstrap_upsert_page('お問い合わせ', 'contact');
    $thanks_id = livesta_bootstrap_upsert_page('送信完了', 'thanks', '', $contact_id);
    $privacy_id = livesta_bootstrap_upsert_page('プライバシーポリシー', 'privacy', "個人情報はお問い合わせ対応の目的にのみ使用し、法令に基づく場合を除き第三者へ提供しません。");
    $sitemap_id = livesta_bootstrap_upsert_page('サイトマップ', 'sitemap');
    $news_id = livesta_bootstrap_upsert_page('お知らせ', 'news');

    update_option('show_on_front', 'page');
    update_option('page_on_front', $home_id);
    update_option('page_for_posts', $news_id);

    livesta_bootstrap_sync_news_posts();

    if (!$is_seeded && function_exists('livesta_get_sample_properties') && wp_count_posts('property')->publish < 1) {
        foreach (livesta_get_sample_properties() as $item) {
            if (livesta_bootstrap_find_post($item['title'], 'property')) {
                continue;
            }

            $post_id = wp_insert_post([
                'post_type' => 'property',
                'post_status' => 'publish',
                'post_title' => $item['title'],
                'post_content' => $item['title'] . ' のご案内です。',
            ]);

            if (!$post_id || is_wp_error($post_id)) {
                continue;
            }

            update_post_meta($post_id, 'price', $item['price']);
            update_post_meta($post_id, 'layout', $item['layout']);
            update_post_meta($post_id, 'area_size', $item['area']);
            update_post_meta($post_id, 'station', $item['station']);
            update_post_meta($post_id, 'built_date', $item['built_date']);
            update_post_meta($post_id, 'badge', $item['badge']);
            update_post_meta($post_id, 'location', $item['location']);

            $area_term = term_exists($item['area_term'], 'property_area');
            if ($area_term) {
                wp_set_post_terms($post_id, [(int) $area_term['term_id']], 'property_area');
            }

            $type_term = term_exists($item['type'], 'property_type');
            if ($type_term) {
                wp_set_post_terms($post_id, [(int) $type_term['term_id']], 'property_type');
            }
        }
    }

    foreach ([$about_id, $service_id, $contact_id, $thanks_id, $privacy_id, $sitemap_id] as $page_id) {
        if ($page_id > 0) {
            wp_update_post(['ID' => $page_id, 'post_status' => 'publish']);
        }
    }

    if (!$is_seeded) {
        update_option('livesta_demo_seeded', '1');
    }

    flush_rewrite_rules(false);
}
