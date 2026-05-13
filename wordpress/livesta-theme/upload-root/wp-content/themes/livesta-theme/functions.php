<?php
if (!defined('ABSPATH')) {
    exit;
}

define('LIVESTA_THEME_VERSION', '1.0.5');

function livesta_setup_theme(): void {
    add_theme_support('title-tag');
    add_theme_support('post-thumbnails');
    add_theme_support(
        'html5',
        ['search-form', 'comment-form', 'comment-list', 'gallery', 'caption', 'style', 'script']
    );

    register_nav_menus(
        [
            'primary' => 'Primary Navigation',
            'footer'  => 'Footer Navigation',
        ]
    );
}
add_action('after_setup_theme', 'livesta_setup_theme');

function livesta_enqueue_assets(): void {
    wp_enqueue_style(
        'livesta-google-fonts',
        'https://fonts.googleapis.com/css2?family=Noto+Sans+JP:wght@300;400;500;700;900&family=Zen+Kaku+Gothic+New:wght@400;700;900&display=swap',
        [],
        null
    );

    wp_enqueue_style(
        'livesta-style',
        get_stylesheet_uri(),
        ['livesta-google-fonts'],
        LIVESTA_THEME_VERSION
    );

    wp_enqueue_script(
        'livesta-main',
        get_theme_file_uri('/assets/js/main.js'),
        [],
        LIVESTA_THEME_VERSION,
        true
    );
}
add_action('wp_enqueue_scripts', 'livesta_enqueue_assets');

function livesta_register_property_post_type(): void {
    register_post_type(
        'property',
        [
            'labels' => [
                'name'               => '物件情報',
                'singular_name'      => '物件',
                'menu_name'          => '物件情報',
                'add_new'            => '新規追加',
                'add_new_item'       => '物件を追加',
                'edit_item'          => '物件を編集',
                'new_item'           => '新しい物件',
                'view_item'          => '物件を表示',
                'search_items'       => '物件を検索',
                'not_found'          => '物件が見つかりません',
                'not_found_in_trash' => 'ゴミ箱に物件はありません',
            ],
            'public'              => true,
            'has_archive'         => true,
            'rewrite'             => [
                'slug'       => 'property',
                'with_front' => false,
            ],
            'menu_position'       => 5,
            'menu_icon'           => 'dashicons-building',
            'supports'            => ['title', 'editor', 'thumbnail', 'excerpt'],
            'show_in_rest'        => true,
        ]
    );

    register_taxonomy(
        'property_area',
        'property',
        [
            'labels' => [
                'name'          => 'エリア',
                'singular_name' => 'エリア',
            ],
            'public'            => true,
            'hierarchical'      => true,
            'show_admin_column' => true,
            'show_in_rest'      => true,
            'rewrite'           => [
                'slug'       => 'property-area',
                'with_front' => false,
            ],
        ]
    );

    register_taxonomy(
        'property_type',
        'property',
        [
            'labels' => [
                'name'          => '種別',
                'singular_name' => '種別',
            ],
            'public'            => true,
            'hierarchical'      => true,
            'show_admin_column' => true,
            'show_in_rest'      => true,
            'rewrite'           => [
                'slug'       => 'property-type',
                'with_front' => false,
            ],
        ]
    );
}
add_action('init', 'livesta_register_property_post_type');

function livesta_create_initial_terms(): void {
    // Ensure taxonomy registration is available during activation hook.
    livesta_register_property_post_type();

    $categories = [
        ['name' => 'お知らせ', 'slug' => 'news'],
        ['name' => '物件情報', 'slug' => 'property'],
        ['name' => 'メディア', 'slug' => 'media'],
    ];

    foreach ($categories as $category) {
        if (!term_exists($category['slug'], 'category')) {
            wp_insert_term($category['name'], 'category', ['slug' => $category['slug']]);
        }
    }

    $areas = livesta_get_property_area_options();
    foreach ($areas as $area) {
        if (!term_exists($area, 'property_area')) {
            wp_insert_term($area, 'property_area');
        }
    }

    $types = ['マンション', '戸建', '土地'];
    foreach ($types as $type) {
        if (!term_exists($type, 'property_type')) {
            wp_insert_term($type, 'property_type');
        }
    }

    flush_rewrite_rules();
}
add_action('after_switch_theme', 'livesta_create_initial_terms');

function livesta_get_news_category_ids(): array {
    $category_ids = [];

    foreach (['news', 'property', 'media'] as $slug) {
        $term = get_category_by_slug($slug);

        if ($term instanceof WP_Term) {
            $category_ids[] = (int) $term->term_id;
        }
    }

    return array_values(array_unique(array_filter($category_ids)));
}

function livesta_get_news_query_args(int $posts_per_page = 10, array $overrides = []): array {
    $args = [
        'post_type'           => 'post',
        'post_status'         => 'publish',
        'posts_per_page'      => $posts_per_page,
        'ignore_sticky_posts' => true,
    ];

    $category_ids = livesta_get_news_category_ids();
    if (!empty($category_ids)) {
        $args['category__in'] = $category_ids;
    }

    return array_merge($args, $overrides);
}

function livesta_limit_news_queries(WP_Query $query): void {
    if (is_admin() || !$query->is_main_query()) {
        return;
    }

    if ($query->is_home()) {
        $query->set('post_type', 'post');
        $query->set('post_status', 'publish');
        $query->set('ignore_sticky_posts', true);

        $category_ids = livesta_get_news_category_ids();
        if (!empty($category_ids)) {
            $query->set('category__in', $category_ids);
        }
    }
}
add_action('pre_get_posts', 'livesta_limit_news_queries');

function livesta_get_news_page_url(): string {
    $posts_page_id = (int) get_option('page_for_posts');

    if ($posts_page_id > 0) {
        return (string) get_permalink($posts_page_id);
    }

    return (string) home_url('/news/');
}

function livesta_get_property_archive_url(): string {
    $archive_url = get_post_type_archive_link('property');

    if (is_string($archive_url) && $archive_url !== '') {
        return $archive_url;
    }

    return (string) home_url('/property/');
}

function livesta_get_thanks_page_url(): string {
    $thanks_page = get_page_by_path('contact/thanks', OBJECT, 'page');

    if (!$thanks_page instanceof WP_Post) {
        $thanks_page = get_page_by_path('thanks', OBJECT, 'page');
    }

    if ($thanks_page instanceof WP_Post) {
        $permalink = get_permalink($thanks_page);

        if (is_string($permalink) && $permalink !== '') {
            return $permalink;
        }
    }

    return (string) home_url('/contact/thanks/');
}

function livesta_get_company_profile(): array {
    return [
        'site_name'         => 'LIVESTA',
        'company_name'      => '株式会社LIVESTA',
        'message_title'     => '代表取締役',
        'message_name'      => '佐伯 恒一',
        'message_name_en'   => 'Keiichi Saeki',
        'message_role'      => 'Representative Director',
        'message_sign'      => '株式会社LIVESTA 代表取締役 佐伯 恒一',
        'representative'    => '代表取締役 佐伯 恒一',
        'founded'           => '2016年4月12日',
        'capital'           => '3,000万円',
        'employees'         => '18名（2026年3月現在）',
        'postal_code'       => '〒150-0021',
        'address'           => '東京都渋谷区恵比寿西2-17-8 3F',
        'phone'             => '03-6455-3071',
        'fax'               => '03-6455-3072',
        'mail'              => 'info@livesta-demo.com',
        'hours'             => '10:00-18:00',
        'closed'            => '火曜・水曜・年末年始',
        'access'            => '東急東横線「代官山」駅 徒歩4分 / JR山手線「恵比寿」駅 徒歩8分',
        'business'          => '不動産売買仲介 / 賃貸管理 / 不動産開発 / コンサルティング',
        'license'           => '東京都知事(2) 第98765号',
        'associations'      => '公益社団法人 全日本不動産協会 / 公益社団法人 不動産保証協会',
        'office_note'       => 'ご来店・ご相談は予約制です。オンライン相談にも対応しています。',
        'response_time'     => '2営業日以内を目安にご連絡いたします。',
        'privacy_updated'   => '2026年3月1日',
        'managed_units'     => '1,200戸+',
        'occupancy_rate'    => '97.8%',
        'development_count' => '28棟',
        'map_query'         => '東京都渋谷区恵比寿西2-17-8',
    ];
}

function livesta_get_company_value(string $key, string $default = ''): string {
    $profile = livesta_get_company_profile();

    if (!array_key_exists($key, $profile)) {
        return $default;
    }

    return (string) $profile[$key];
}

function livesta_get_company_phone_href(): string {
    $digits = preg_replace('/[^0-9+]/', '', livesta_get_company_value('phone'));

    if ($digits === null || $digits === '') {
        return '';
    }

    return 'tel:' . $digits;
}

function livesta_get_company_map_embed_url(): string {
    $query = livesta_get_company_value('map_query', livesta_get_company_value('address'));

    return 'https://www.google.com/maps?q=' . rawurlencode($query) . '&z=16&output=embed';
}

function livesta_get_company_map_url(): string {
    $query = livesta_get_company_value('map_query', livesta_get_company_value('address'));

    return 'https://www.google.com/maps/search/?api=1&query=' . rawurlencode($query);
}

function livesta_get_property_area_options(): array {
    return ['代官山', '目黒', '世田谷', '南青山', '新宿御苑', '麹町'];
}

function livesta_get_contact_form_defaults(): array {
    $inquiry_property = isset($_GET['inquiry_property']) ? sanitize_text_field(wp_unslash($_GET['inquiry_property'])) : '';
    $inquiry_type = isset($_GET['inquiry_type']) ? sanitize_text_field(wp_unslash($_GET['inquiry_type'])) : '物件について';
    $allowed_types = ['物件について', '売却査定', '賃貸管理', '来店予約', 'その他'];

    if (!in_array($inquiry_type, $allowed_types, true)) {
        $inquiry_type = '物件について';
    }

    return [
        'inquiry_property' => $inquiry_property,
        'inquiry_type'     => $inquiry_type,
        'inquiry_message'  => $inquiry_property !== '' ? '「' . $inquiry_property . '」について詳しく知りたいです。' : '',
    ];
}

function livesta_svg_arrow(int $size = 16): string {
    $size = max(12, $size);

    return sprintf(
        '<svg width="%1$d" height="%1$d" fill="none" stroke="currentColor" stroke-width="1.5" aria-hidden="true"><path d="M3 %2$dh%3$dM%4$d 4l4 4-4 4"/></svg>',
        $size,
        (int) floor($size / 2),
        (int) ($size - 6),
        (int) ($size - 7)
    );
}

function livesta_render_breadcrumb(): void {
    if (is_front_page()) {
        return;
    }

    echo '<div class="page-breadcrumb"><div class="section-inner">';
    echo '<a href="' . esc_url(home_url('/')) . '">TOP</a>';

    if (is_home() || is_category()) {
        echo ' <span class="sep">&gt;</span> <span>お知らせ</span>';

        if (is_category()) {
            echo ' <span class="sep">&gt;</span> <span>' . esc_html(single_cat_title('', false)) . '</span>';
        }
    } elseif (is_singular('post')) {
        echo ' <span class="sep">&gt;</span> <a href="' . esc_url(livesta_get_news_page_url()) . '">お知らせ</a>';
        echo ' <span class="sep">&gt;</span> <span>' . esc_html(get_the_title()) . '</span>';
    } elseif (is_post_type_archive('property')) {
        echo ' <span class="sep">&gt;</span> <span>物件情報</span>';
    } elseif (is_singular('property')) {
        echo ' <span class="sep">&gt;</span> <a href="' . esc_url(livesta_get_property_archive_url()) . '">物件情報</a>';
        echo ' <span class="sep">&gt;</span> <span>' . esc_html(get_the_title()) . '</span>';
    } elseif (is_page()) {
        $ancestors = array_reverse(get_post_ancestors(get_the_ID()));

        foreach ($ancestors as $ancestor_id) {
            echo ' <span class="sep">&gt;</span> <a href="' . esc_url(get_permalink($ancestor_id)) . '">' . esc_html(get_the_title($ancestor_id)) . '</a>';
        }

        echo ' <span class="sep">&gt;</span> <span>' . esc_html(get_the_title()) . '</span>';
    } elseif (is_404()) {
        echo ' <span class="sep">&gt;</span> <span>404</span>';
    } else {
        echo ' <span class="sep">&gt;</span> <span>' . esc_html(wp_get_document_title()) . '</span>';
    }

    echo '</div></div>';
}

function livesta_render_page_hero(array $args = []): void {
    $defaults = [
        'eyebrow'  => '',
        'title'    => get_the_title(),
        'subtitle' => '',
        'mini'     => false,
        'class'    => '',
    ];

    $args = wp_parse_args($args, $defaults);

    $classes = ['page-hero'];
    if (!empty($args['mini'])) {
        $classes[] = 'mini';
    }
    if (!empty($args['class'])) {
        $classes[] = $args['class'];
    }

    echo '<section class="' . esc_attr(implode(' ', $classes)) . '">';
    echo '<div class="page-hero-overlay"></div>';
    echo '<div class="section-inner">';
    echo '<div class="page-hero-content">';

    if (!empty($args['eyebrow'])) {
        echo '<p class="section-eyebrow">' . esc_html($args['eyebrow']) . '</p>';
    }

    echo '<h1>' . esc_html($args['title']) . '</h1>';

    if (!empty($args['subtitle'])) {
        echo '<p class="hero-subtext">' . esc_html($args['subtitle']) . '</p>';
    }

    echo '</div>';
    echo '</div>';
    echo '</section>';
}

function livesta_render_access_panel(array $args = []): void {
    $company = livesta_get_company_profile();
    $defaults = [
        'title' => '本社',
        'eyebrow' => 'Access',
        'compact' => false,
    ];

    $args = wp_parse_args($args, $defaults);
    $classes = ['access-visual-card'];

    if (!empty($args['compact'])) {
        $classes[] = 'compact';
    }

    echo '<div class="' . esc_attr(implode(' ', $classes)) . '">';
    echo '<iframe class="access-visual-map" src="' . esc_url(livesta_get_company_map_embed_url()) . '" loading="lazy" referrerpolicy="no-referrer-when-downgrade" allowfullscreen title="' . esc_attr($company['company_name'] . 'の所在地地図') . '"></iframe>';
    echo '<div class="access-visual-overlay">';
    echo '<div class="access-visual-meta">';
    echo '<p class="access-visual-eyebrow">' . esc_html($args['eyebrow']) . '</p>';
    echo '<h3>' . esc_html($args['title']) . '</h3>';
    echo '<ul class="access-visual-list">';
    echo '<li><span>住所</span>' . esc_html($company['postal_code'] . ' ' . $company['address']) . '</li>';
    echo '<li><span>アクセス</span>' . esc_html($company['access']) . '</li>';
    echo '<li><span>受付</span>' . esc_html($company['hours'] . ' / ' . $company['closed']) . '</li>';
    echo '</ul>';
    echo '<div class="access-visual-footer">';
    echo '<p class="access-visual-note">' . esc_html($company['office_note']) . '</p>';
    echo '<a class="access-visual-link" href="' . esc_url(livesta_get_company_map_url()) . '" target="_blank" rel="noopener noreferrer">Google Mapsで開く' . livesta_svg_arrow(14) . '</a>';
    echo '</div>';
    echo '</div>';
    echo '</div>';
    echo '</div>';
}

function livesta_render_common_cta(array $args = []): void {
    $defaults = [
        'title'       => 'まずはお気軽にご相談ください',
        'description' => '物件のご相談・売却査定・その他ご質問など、お気軽にお問い合わせください。',
        'button_text' => 'お問い合わせはこちら',
        'button_url'  => home_url('/contact/'),
        'actions'     => [],
    ];

    $args = wp_parse_args($args, $defaults);
    $actions = $args['actions'];

    if (empty($actions)) {
        $actions = [
            [
                'label'   => '物件を相談する',
                'url'     => add_query_arg('inquiry_type', '物件について', home_url('/contact/')),
                'variant' => 'primary',
            ],
            [
                'label'   => '売却査定を相談',
                'url'     => add_query_arg('inquiry_type', '売却査定', home_url('/contact/')),
                'variant' => 'secondary',
            ],
            [
                'label'   => '来店予約',
                'url'     => add_query_arg('inquiry_type', '来店予約', home_url('/contact/')),
                'variant' => 'secondary',
            ],
        ];
    }

    echo '<section class="common-cta">';
    echo '<div class="section-inner">';
    echo '<h2>' . esc_html($args['title']) . '</h2>';
    echo '<p>' . esc_html($args['description']) . '</p>';

    if (!empty($actions)) {
        echo '<div class="cta-actions">';
        foreach ($actions as $action) {
            $label = isset($action['label']) ? (string) $action['label'] : '';
            $url = isset($action['url']) ? (string) $action['url'] : '';
            $variant = isset($action['variant']) ? (string) $action['variant'] : 'secondary';

            if ($label === '' || $url === '') {
                continue;
            }

            echo '<a class="cta-action cta-action-' . esc_attr($variant) . '" href="' . esc_url($url) . '">' . esc_html($label) . livesta_svg_arrow() . '</a>';
        }
        echo '</div>';
    } else {
        echo '<a class="contact-btn" href="' . esc_url($args['button_url']) . '">' . esc_html($args['button_text']) . livesta_svg_arrow() . '</a>';
    }

    echo '</div>';
    echo '</section>';
}

function livesta_filter_document_title_parts(array $parts): array {
    $site_name = livesta_get_company_value('site_name', 'LIVESTA');

    if (is_front_page()) {
        $parts['title'] = '暮らしに、確かな価値を。';
    } elseif (is_page('about')) {
        $parts['title'] = '会社概要';
    } elseif (is_page('service')) {
        $parts['title'] = '事業内容';
    } elseif (is_post_type_archive('property')) {
        $parts['title'] = '物件情報';
    } elseif (is_home() || is_category()) {
        $parts['title'] = 'お知らせ';
    } elseif (is_page('contact')) {
        $parts['title'] = 'お問い合わせ';
    } elseif (is_page('privacy')) {
        $parts['title'] = 'プライバシーポリシー';
    }

    $parts['site'] = $site_name;

    return $parts;
}
add_filter('document_title_parts', 'livesta_filter_document_title_parts');

function livesta_redirect_property_archive_fallback(): void {
    if (!is_404()) {
        return;
    }

    $request_uri = isset($_SERVER['REQUEST_URI']) ? (string) wp_unslash($_SERVER['REQUEST_URI']) : '';
    $path = (string) wp_parse_url($request_uri, PHP_URL_PATH);

    if (trim($path, '/') !== 'property') {
        return;
    }

    $query_args = [];
    if (isset($_SERVER['QUERY_STRING']) && $_SERVER['QUERY_STRING'] !== '') {
        parse_str((string) wp_unslash($_SERVER['QUERY_STRING']), $query_args);
    }

    if (!is_array($query_args)) {
        $query_args = [];
    }

    $archive_url = livesta_get_property_archive_url();

    wp_safe_redirect(add_query_arg($query_args, $archive_url), 301);
    exit;
}
add_action('template_redirect', 'livesta_redirect_property_archive_fallback', 1);

function livesta_trim_text(string $text, int $limit = 120): string {
    $normalized = preg_replace('/\s+/u', ' ', wp_strip_all_tags($text));
    $text = trim($normalized ?? '');

    if ($text === '') {
        return '';
    }

    if (function_exists('mb_strimwidth')) {
        return mb_strimwidth($text, 0, $limit, '…', 'UTF-8');
    }

    if (strlen($text) <= $limit) {
        return $text;
    }

    return substr($text, 0, $limit) . '...';
}

function livesta_get_default_meta_description(): string {
    return '代官山・目黒・世田谷を中心に、売買仲介・賃貸管理・不動産開発・コンサルティングを行うLIVESTAのコーポレートサイトです。';
}

function livesta_get_default_og_image_url(): string {
    return livesta_get_theme_image_url('about-hero.png');
}

function livesta_get_current_url(): string {
    $request_uri = isset($_SERVER['REQUEST_URI']) ? (string) wp_unslash($_SERVER['REQUEST_URI']) : '/';

    return esc_url_raw(home_url($request_uri));
}

function livesta_get_meta_context(): array {
    $title = wp_get_document_title();
    $description = livesta_get_default_meta_description();
    $url = livesta_get_current_url();
    $image = livesta_get_default_og_image_url();
    $type = 'website';

    if (is_front_page()) {
        $description = '代官山・目黒・世田谷を中心に、売買仲介・賃貸管理・開発・資産活用の相談までワンストップで支援するLIVESTAの不動産サイトです。';
    } elseif (is_page('about')) {
        $description = 'LIVESTAの会社概要、企業理念、沿革、アクセス情報をご案内します。';
    } elseif (is_page('service')) {
        $description = '売買仲介、賃貸管理、不動産開発、コンサルティングまで、LIVESTAの4つの事業領域をご紹介します。';
    } elseif (is_post_type_archive('property')) {
        $description = '代官山・目黒・世田谷など都心6エリアの掲載物件を、価格帯・間取り・エリアから絞り込んで探せます。';
    } elseif (is_page('contact')) {
        $description = '物件相談、売却査定、賃貸管理、来店予約などのお問い合わせを受け付けています。';
    } elseif (is_home() || is_category()) {
        $description = 'LIVESTAからのお知らせ、物件更新情報、不動産に関するコラムを掲載しています。';
    } elseif (is_singular('property')) {
        $post_id = get_queried_object_id();
        $title = get_the_title($post_id) . ' | ' . livesta_get_company_value('site_name', 'LIVESTA');
        $description = livesta_trim_text(livesta_get_property_meta($post_id, 'description', get_the_excerpt($post_id) ?: livesta_get_default_meta_description()));
        $fallback_image = livesta_get_default_property_image(get_the_title($post_id), $post_id);
        $image = has_post_thumbnail($post_id) ? (string) get_the_post_thumbnail_url($post_id, 'full') : livesta_get_theme_image_url($fallback_image);
        $type = 'article';
    } elseif (is_singular('post')) {
        $post_id = get_queried_object_id();
        $description = livesta_trim_text(has_excerpt($post_id) ? get_the_excerpt($post_id) : (string) get_post_field('post_content', $post_id));
        if ($description === '') {
            $description = 'LIVESTAのお知らせ記事です。';
        }
        $image = has_post_thumbnail($post_id) ? (string) get_the_post_thumbnail_url($post_id, 'full') : livesta_get_theme_image_url('news-article-default.png');
        $type = 'article';
    } elseif (is_page('privacy')) {
        $description = 'LIVESTAのプライバシーポリシーです。個人情報の取り扱いとお問い合わせ窓口をご案内します。';
    }

    return [
        'title' => $title,
        'description' => $description,
        'url' => $url,
        'image' => $image,
        'type' => $type,
    ];
}

function livesta_get_schema_graph(array $meta): array {
    $company = livesta_get_company_profile();
    $organization_id = trailingslashit(home_url('/')) . '#organization';
    $website_id = trailingslashit(home_url('/')) . '#website';
    $page_id = $meta['url'] . '#webpage';

    $graph = [
        [
            '@type' => 'RealEstateAgent',
            '@id' => $organization_id,
            'name' => $company['company_name'],
            'url' => home_url('/'),
            'image' => $meta['image'],
            'telephone' => $company['phone'],
            'priceRange' => '¥¥¥',
            'address' => [
                '@type' => 'PostalAddress',
                'postalCode' => str_replace('〒', '', $company['postal_code']),
                'streetAddress' => $company['address'],
                'addressCountry' => 'JP',
            ],
            'openingHours' => ['Mo 10:00-18:00', 'Th 10:00-18:00', 'Fr 10:00-18:00', 'Sa 10:00-18:00', 'Su 10:00-18:00'],
            'areaServed' => livesta_get_property_area_options(),
        ],
        [
            '@type' => 'WebSite',
            '@id' => $website_id,
            'name' => $company['site_name'],
            'url' => home_url('/'),
            'publisher' => [
                '@id' => $organization_id,
            ],
        ],
        [
            '@type' => 'WebPage',
            '@id' => $page_id,
            'url' => $meta['url'],
            'name' => $meta['title'],
            'description' => $meta['description'],
            'isPartOf' => [
                '@id' => $website_id,
            ],
            'about' => [
                '@id' => $organization_id,
            ],
        ],
    ];

    if (is_singular('post')) {
        $post_id = get_queried_object_id();
        $graph[] = [
            '@type' => 'BlogPosting',
            'headline' => get_the_title($post_id),
            'datePublished' => get_the_date('c', $post_id),
            'dateModified' => get_the_modified_date('c', $post_id),
            'description' => $meta['description'],
            'image' => $meta['image'],
            'mainEntityOfPage' => [
                '@id' => $page_id,
            ],
            'publisher' => [
                '@id' => $organization_id,
            ],
        ];
    }

    return [
        '@context' => 'https://schema.org',
        '@graph' => $graph,
    ];
}

function livesta_output_head_meta(): void {
    $meta = livesta_get_meta_context();

    echo "\n" . '<meta name="description" content="' . esc_attr($meta['description']) . '">' . "\n";
    echo '<meta property="og:locale" content="ja_JP">' . "\n";
    echo '<meta property="og:type" content="' . esc_attr($meta['type']) . '">' . "\n";
    echo '<meta property="og:site_name" content="' . esc_attr(livesta_get_company_value('site_name', 'LIVESTA')) . '">' . "\n";
    echo '<meta property="og:title" content="' . esc_attr($meta['title']) . '">' . "\n";
    echo '<meta property="og:description" content="' . esc_attr($meta['description']) . '">' . "\n";
    echo '<meta property="og:url" content="' . esc_url($meta['url']) . '">' . "\n";
    echo '<meta property="og:image" content="' . esc_url($meta['image']) . '">' . "\n";
    echo '<meta name="twitter:card" content="summary_large_image">' . "\n";
    echo '<meta name="twitter:title" content="' . esc_attr($meta['title']) . '">' . "\n";
    echo '<meta name="twitter:description" content="' . esc_attr($meta['description']) . '">' . "\n";
    echo '<meta name="twitter:image" content="' . esc_url($meta['image']) . '">' . "\n";

    if (!has_site_icon()) {
        echo '<link rel="icon" href="' . esc_url(get_theme_file_uri('/assets/img/favicon.svg')) . '" type="image/svg+xml">' . "\n";
    }

    echo '<script type="application/ld+json">' . wp_json_encode(livesta_get_schema_graph($meta), JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) . '</script>' . "\n";
}
add_action('wp_head', 'livesta_output_head_meta', 1);

function livesta_get_property_meta(int $post_id, string $key, string $default = ''): string {
    $value = get_post_meta($post_id, $key, true);

    if ($value === '' || $value === null) {
        return $default;
    }

    return (string) $value;
}

function livesta_get_primary_category_name(int $post_id): string {
    $categories = get_the_category($post_id);

    if (!empty($categories)) {
        return (string) $categories[0]->name;
    }

    return 'お知らせ';
}

function livesta_get_sample_news_category_slug(array $item): string {
    if (!empty($item['category_slug'])) {
        return sanitize_title((string) $item['category_slug']);
    }

    $category = isset($item['category']) ? (string) $item['category'] : '';
    $map = [
        'お知らせ' => 'news',
        '物件情報' => 'property',
        'メディア' => 'media',
    ];

    return $map[$category] ?? 'news';
}

function livesta_get_theme_image_url(string $filename): string {
    return (string) get_theme_file_uri('/assets/img/' . ltrim($filename, '/'));
}

function livesta_get_legacy_content_image_url(string $src): string {
    $src = trim($src);
    if ($src === '') {
        return '';
    }

    $map = [
        'banner_tel' => 'legacy-banner-placeholder.svg',
        'maps-cs_vort' => 'legacy-map-placeholder.svg',
        'burex-210x300-2' => 'top-about.png',
        'ceo.jpg' => 'ceo-portrait.png',
        'screen-shot-2014-10-26-at-17.08.36-1024x611.png' => 'legacy-size-chart-placeholder.svg',
        'img_bodytype_' => 'legacy-bodytype-placeholder.svg',
        'img_suit_black.jpg' => 'legacy-formalwear-black.svg',
        'img_suit_gray.jpg' => 'legacy-formalwear-gray.svg',
        'img_set_image.png' => 'legacy-formalwear-set.svg',
        'ico_orna_detailhead.gif' => 'legacy-ornament.svg',
        'stuffgroupphoto-1024x768.jpg' => 'legacy-team-placeholder.svg',
        '11035313_805714602811708_4644548846103535261_n1-e1472975153508.jpg' => 'legacy-team-placeholder.svg',
        '13275363_1772094093013395_1911088280_o-2.jpg' => 'legacy-team-placeholder.svg',
        '13288161_1772094133013391_619029001_o.jpg' => 'legacy-team-placeholder.svg',
        '13840527_1796746887214782_1264914267_o-2.jpg' => 'legacy-team-placeholder.svg',
    ];

    $normalized_src = strtolower($src);
    foreach ($map as $needle => $filename) {
        if (strpos($normalized_src, $needle) !== false) {
            return livesta_get_theme_image_url($filename);
        }
    }

    return '';
}

function livesta_replace_legacy_content_assets(string $content): string {
    if (is_admin() || trim($content) === '' || !is_page([
        'aboutus',
        'access',
        'bodytype',
        'company',
        'dress-size',
        'morning',
        'parents-role-and-manner',
        'recruit',
    ])) {
        return $content;
    }

    $replace_inline_ornament = static function (string $html): string {
        $legacy_ornament_url = home_url('/img/aboutus/ico_orna_detailhead.gif');
        return str_ireplace(
            [
                "url('/img/aboutus/ico_orna_detailhead.gif')",
                'url("/img/aboutus/ico_orna_detailhead.gif")',
                "url('../img/aboutus/ico_orna_detailhead.gif')",
                'url("../img/aboutus/ico_orna_detailhead.gif")',
                "url('" . $legacy_ornament_url . "')",
                'url("' . $legacy_ornament_url . '")',
            ],
            'url("' . livesta_get_theme_image_url('legacy-ornament.svg') . '")',
            $html
        );
    };

    $has_image_tag = stripos($content, '<img') !== false;
    $has_inline_ornament = stripos($content, 'ico_orna_detailhead') !== false;

    if (!$has_image_tag && !$has_inline_ornament) {
        return $content;
    }

    if (!$has_image_tag) {
        return $replace_inline_ornament($content);
    }

    $previous_state = libxml_use_internal_errors(true);
    $document = new DOMDocument('1.0', 'UTF-8');
    $wrapper = '<?xml encoding="utf-8" ?><div id="livesta-legacy-content">' . $content . '</div>';
    $loaded = $document->loadHTML($wrapper, LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD);

    if (!$loaded) {
        libxml_clear_errors();
        libxml_use_internal_errors($previous_state);
        return $content;
    }

    $xpath = new DOMXPath($document);
    foreach ($xpath->query('//img') as $image) {
        if (!$image instanceof DOMElement) {
            continue;
        }

        $replacement = livesta_get_legacy_content_image_url((string) $image->getAttribute('src'));
        if ($replacement === '') {
            continue;
        }

        $image->setAttribute('src', $replacement);
        $image->removeAttribute('srcset');
        $image->removeAttribute('sizes');
    }

    foreach ($xpath->query('//a[@href]') as $link) {
        if (!$link instanceof DOMElement) {
            continue;
        }

        $replacement = livesta_get_legacy_content_image_url((string) $link->getAttribute('href'));
        if ($replacement === '') {
            continue;
        }

        $link->setAttribute('href', $replacement);
    }

    $root = $document->getElementById('livesta-legacy-content');
    if (!$root) {
        libxml_clear_errors();
        libxml_use_internal_errors($previous_state);
        return $content;
    }

    $html = '';
    foreach ($root->childNodes as $child) {
        $html .= $document->saveHTML($child);
    }

    libxml_clear_errors();
    libxml_use_internal_errors($previous_state);

    if ($html === '') {
        return $replace_inline_ornament($content);
    }

    return $replace_inline_ornament($html);
}
add_filter('the_content', 'livesta_replace_legacy_content_assets', 20);

function livesta_get_default_property_image(string $title = '', int $post_id = 0): string {
    $title_map = [
        'ザ・パークレジデンス代官山' => 'property-01.png',
        'リヴェスタコート目黒' => 'property-02.png',
        'グランフォート世田谷' => 'property-03.png',
        'プラウド南青山テラス' => 'property-04.png',
        'レジデンシア新宿御苑' => 'property-05.png',
        'ヴィラージュ千代田麹町' => 'property-06.png',
    ];

    foreach ($title_map as $needle => $filename) {
        if ($title !== '' && strpos($title, $needle) !== false) {
            return $filename;
        }
    }

    $fallback = [
        'property-01.png',
        'property-02.png',
        'property-03.png',
        'property-04.png',
        'property-05.png',
        'property-06.png',
    ];

    if ($post_id > 0) {
        return $fallback[($post_id - 1) % count($fallback)];
    }

    return $fallback[0];
}

function livesta_get_sample_properties(): array {
    return [
        [
            'title' => 'ザ・パークレジデンス代官山',
            'area_term' => '代官山',
            'type' => 'マンション',
            'location' => '渋谷区代官山町',
            'price' => '1億2,800万円',
            'layout' => '2LDK',
            'area' => '74.2㎡',
            'station' => '東急東横線「代官山」駅 徒歩4分',
            'built_date' => '2023年9月',
            'badge' => '新着',
            'image' => 'property-01.png',
        ],
        [
            'title' => 'リヴェスタコート目黒',
            'area_term' => '目黒',
            'type' => 'マンション',
            'location' => '目黒区下目黒',
            'price' => '7,480万円',
            'layout' => '1LDK',
            'area' => '53.1㎡',
            'station' => 'JR山手線「目黒」駅 徒歩7分',
            'built_date' => '2021年11月',
            'badge' => '内見可',
            'image' => 'property-02.png',
        ],
        [
            'title' => 'グランフォート世田谷',
            'area_term' => '世田谷',
            'type' => 'マンション',
            'location' => '世田谷区新町',
            'price' => '8,950万円',
            'layout' => '3LDK',
            'area' => '82.6㎡',
            'station' => '東急田園都市線「桜新町」駅 徒歩9分',
            'built_date' => '2019年2月',
            'badge' => '価格改定',
            'image' => 'property-03.png',
        ],
        [
            'title' => 'プラウド南青山テラス',
            'area_term' => '南青山',
            'type' => 'マンション',
            'location' => '港区南青山',
            'price' => '1億9,800万円',
            'layout' => '2LDK',
            'area' => '88.4㎡',
            'station' => '東京メトロ銀座線「表参道」駅 徒歩6分',
            'built_date' => '2024年5月',
            'badge' => '角住戸',
            'image' => 'property-04.png',
        ],
        [
            'title' => 'レジデンシア新宿御苑',
            'area_term' => '新宿御苑',
            'type' => 'マンション',
            'location' => '新宿区新宿',
            'price' => '6,980万円',
            'layout' => '1SLDK',
            'area' => '61.3㎡',
            'station' => '東京メトロ丸ノ内線「新宿御苑前」駅 徒歩5分',
            'built_date' => '2020年10月',
            'badge' => '収納充実',
            'image' => 'property-05.png',
        ],
        [
            'title' => 'ヴィラージュ千代田麹町',
            'area_term' => '麹町',
            'type' => 'マンション',
            'location' => '千代田区麹町',
            'price' => '1億1,500万円',
            'layout' => '2LDK',
            'area' => '70.8㎡',
            'station' => '東京メトロ有楽町線「麹町」駅 徒歩3分',
            'built_date' => '2022年6月',
            'badge' => '駅近',
            'image' => 'property-06.png',
        ],
    ];
}

function livesta_get_sample_news(): array {
    return [
        [
            'slug' => 'website-renewal',
            'date' => '2026.02.28',
            'category' => 'お知らせ',
            'category_slug' => 'news',
            'title' => '公式ウェブサイトをリニューアルしました',
            'excerpt' => 'LIVESTAの公式ウェブサイトを全面リニューアルし、物件検索や情報発信の導線を改善しました。',
            'content' => trim(<<<'HTML'
<p>日頃より、LIVESTA株式会社のウェブサイトをご覧いただき、誠にありがとうございます。</p>
<p>このたび、当社の公式ウェブサイトを全面リニューアルいたしました。お客様が必要な情報に迷わずたどり着けるよう、構成とデザインを見直しています。</p>
<h2>リニューアルのポイント</h2>
<p>今回のリニューアルでは、物件情報、お知らせ、お問い合わせ導線の3点を中心に改善を行いました。</p>
<h3>デザインの刷新</h3>
<p>ブランドイメージを整理し、スマートフォンでも見やすいレイアウトへ再設計しました。写真や情報量が多いページでも、読み進めやすい余白設計にしています。</p>
<h3>物件検索の見直し</h3>
<p>エリア・価格帯・間取りなど、比較検討に必要な条件が一目で分かる一覧構成に変更しました。初めてご覧いただく方でも絞り込みやすい設計です。</p>
<h3>情報発信の強化</h3>
<p>新着物件、営業日、メディア掲載情報などをニュースとして継続的に発信できるようにしました。今後も更新性を高めながら改善を続けてまいります。</p>
<p>今後ともLIVESTA株式会社をどうぞよろしくお願いいたします。ご質問やご相談は、お問い合わせフォームよりお気軽にご連絡ください。</p>
HTML),
        ],
        [
            'slug' => 'park-residence-daikanyama-on-sale',
            'date' => '2026.02.15',
            'category' => '物件情報',
            'category_slug' => 'property',
            'title' => '「ザ・パークレジデンス代官山」の販売を開始しました',
            'excerpt' => '代官山駅徒歩4分のレジデンスについて、販売開始のお知らせと見学受付情報を掲載しています。',
            'content' => trim(<<<'HTML'
<p>代官山エリアでお問い合わせの多かった「ザ・パークレジデンス代官山」の販売を開始しました。</p>
<p>東急東横線「代官山」駅から徒歩4分の立地に加え、全体計画や共用部の設計にもこだわった物件です。居住用はもちろん、将来的な資産性の観点からもご相談をいただいています。</p>
<h2>今回ご案内するポイント</h2>
<p>駅距離と住環境のバランス、南向き住戸中心のプラン、都心主要エリアへのアクセス性が特徴です。</p>
<h3>見学予約について</h3>
<p>完全予約制でのご案内となります。平日・土日ともに現地見学枠をご用意しておりますので、お問い合わせフォームまたはお電話にてご連絡ください。</p>
<h3>ご相談内容に応じたご提案</h3>
<p>居住用としての比較だけでなく、住み替えや資産整理を前提としたご相談にも対応しています。売却査定やローン相談もあわせて承ります。</p>
<p>詳細情報は物件ページに順次反映してまいります。気になる方はお早めにお問い合わせください。</p>
HTML),
        ],
        [
            'slug' => 'nikkei-feature',
            'date' => '2026.01.20',
            'category' => 'メディア',
            'category_slug' => 'media',
            'title' => '日経不動産マーケット情報に当社の取り組みが掲載されました',
            'excerpt' => '賃貸管理と資産活用支援の取り組みについて、日経不動産マーケット情報に掲載されました。',
            'content' => trim(<<<'HTML'
<p>日経不動産マーケット情報にて、LIVESTAの賃貸管理および資産活用支援の取り組みをご紹介いただきました。</p>
<p>空室対策や運用改善に加え、オーナー様との定期的な情報共有の仕組みについて取材いただいています。</p>
<h2>掲載内容について</h2>
<p>記事内では、管理戸数の拡大だけでなく、入居率改善に向けたリーシング施策やリノベーション提案の考え方が紹介されています。</p>
<h3>オーナー様との伴走体制</h3>
<p>市場動向の変化が早い中で、短期的な募集条件の見直しと中長期の収益設計を分けて考える重要性についてコメントしました。</p>
<h3>今後の取り組み</h3>
<p>今後も売買仲介・賃貸管理・コンサルティングを横断しながら、実務に根ざした情報発信を続けてまいります。</p>
HTML),
        ],
        [
            'slug' => 'year-end-holiday',
            'date' => '2025.12.28',
            'category' => 'お知らせ',
            'category_slug' => 'news',
            'title' => '年末年始の営業日について',
            'excerpt' => '年末年始期間中の休業日程と、お問い合わせ対応開始日をご案内します。',
            'content' => trim(<<<'HTML'
<p>誠に勝手ながら、LIVESTAでは下記期間を年末年始休業とさせていただきます。</p>
<p>休業期間中にいただいたお問い合わせにつきましては、営業開始日以降に順次対応いたします。</p>
<h2>休業期間</h2>
<p>2025年12月30日（火）から2026年1月4日（日）まで</p>
<h3>営業開始日</h3>
<p>2026年1月5日（月）10:00より通常営業いたします。物件見学のご予約や売却相談のご連絡は、フォームから24時間受け付けております。</p>
<p>ご不便をおかけしますが、何卒ご了承のほどよろしくお願いいたします。</p>
HTML),
        ],
        [
            'slug' => 'meguro-two-units-left',
            'date' => '2025.12.10',
            'category' => '物件情報',
            'category_slug' => 'property',
            'title' => '「リヴェスタコート目黒」残り2戸となりました',
            'excerpt' => '目黒エリアの人気物件「リヴェスタコート目黒」は残り2戸となりました。',
            'content' => trim(<<<'HTML'
<p>販売中の「リヴェスタコート目黒」は、多くのお問い合わせをいただき、現在ご案内可能なお部屋が残り2戸となりました。</p>
<p>1LDK中心の使いやすい間取りに加え、目黒駅徒歩圏という利便性から、実需・投資の両面でご検討いただいています。</p>
<h2>現時点のご案内状況</h2>
<p>最新の販売状況は日々変動します。ご検討中のお客様は、まずは希望条件をご共有ください。担当より現況と近しい代替候補をあわせてご案内します。</p>
<h3>オンライン相談も対応可能です</h3>
<p>遠方のお客様やお忙しい方に向けて、オンライン面談や資料送付にも対応しています。住宅ローンや住み替えスケジュールの相談も可能です。</p>
HTML),
        ],
        [
            'slug' => 'consulting-seminar',
            'date' => '2025.11.15',
            'category' => 'お知らせ',
            'category_slug' => 'news',
            'title' => '不動産コンサルティングセミナーを開催します',
            'excerpt' => '購入・売却・資産活用をテーマにした少人数セミナー開催のお知らせです。',
            'content' => trim(<<<'HTML'
<p>LIVESTAでは、不動産の購入・売却・保有活用をテーマにした少人数制セミナーを開催いたします。</p>
<p>市場動向を踏まえた住み替えの判断軸や、資産価値を落としにくい物件選びについて、実例を交えてご紹介します。</p>
<h2>セミナー概要</h2>
<p>開催日は2025年11月30日、場所は渋谷区内の会場を予定しています。参加費は無料、事前予約制です。</p>
<h3>こんな方におすすめです</h3>
<p>初めて住まいを購入する方、資産整理を検討している方、保有不動産の活用方法を見直したい方に向けた内容です。</p>
<p>参加をご希望の方は、お問い合わせフォームより「セミナー参加希望」とご記載の上お申し込みください。</p>
HTML),
        ],
        [
            'slug' => 'interview-in-jutaku-shimpo',
            'date' => '2025.10.20',
            'category' => 'メディア',
            'category_slug' => 'media',
            'title' => '住宅新報に代表インタビューが掲載されました',
            'excerpt' => '代表インタビューが住宅新報に掲載され、都心住宅市場に対する見解をお話ししました。',
            'content' => trim(<<<'HTML'
<p>住宅新報にて、LIVESTA代表のインタビュー記事が掲載されました。</p>
<p>都心住宅市場における購入ニーズの変化、供給の見通し、仲介会社として重視している情報提供姿勢についてお話ししています。</p>
<h2>インタビューの主な内容</h2>
<p>記事では、価格だけでなく、長く住み続ける前提での立地選定や管理状態の見極めが重要である点を中心にコメントしました。</p>
<h3>LIVESTAが重視する視点</h3>
<p>表面的な条件比較だけでなく、将来的なライフプランや資産価値の維持を見据えた提案を重視しています。今後も現場に根ざした発信を続けてまいります。</p>
HTML),
        ],
        [
            'slug' => 'autumn-new-properties',
            'date' => '2025.10.01',
            'category' => '物件情報',
            'category_slug' => 'property',
            'title' => '秋の新着物件を3件追加しました',
            'excerpt' => '都心6エリアを中心に、新着物件を3件追加しました。',
            'content' => trim(<<<'HTML'
<p>秋の新着物件として、都心6エリアを中心に新たに3件の掲載を開始しました。</p>
<p>駅距離や生活利便性に加え、管理状態や将来的な流通性も踏まえてご紹介しています。</p>
<h2>今回追加した物件の特徴</h2>
<p>代官山・新宿御苑・麹町エリアから、それぞれ特徴の異なる住戸を掲載しています。ファミリー向け、DINKs向け、資産活用向けと幅広いご相談に対応可能です。</p>
<h3>ご希望条件の整理もお任せください</h3>
<p>「まだ条件が固まっていない」という段階でも問題ありません。ご予算や希望エリア、将来設計を伺いながら候補を整理してご提案します。</p>
HTML),
        ],
    ];
}

function livesta_get_property_type_options(): array {
    return ['マンション', '戸建', '土地'];
}

function livesta_get_property_price_options(): array {
    return [
        'up_to_3000'    => '〜3,000万円',
        '3000_to_5000'  => '3,000万〜5,000万円',
        '5000_to_8000'  => '5,000万〜8,000万円',
        'from_8000'     => '8,000万円〜',
    ];
}

function livesta_get_property_layout_options(): array {
    return ['1R〜1K', '1LDK〜2LDK', '3LDK〜'];
}

function livesta_get_property_filters(): array {
    $area = isset($_GET['area']) ? sanitize_text_field(wp_unslash($_GET['area'])) : '';
    $type = isset($_GET['type']) ? sanitize_text_field(wp_unslash($_GET['type'])) : '';
    $price = isset($_GET['price']) ? sanitize_key(wp_unslash($_GET['price'])) : '';
    $layout = isset($_GET['layout']) ? sanitize_text_field(wp_unslash($_GET['layout'])) : '';

    if (!in_array($area, livesta_get_property_area_options(), true)) {
        $area = '';
    }

    if (!in_array($type, livesta_get_property_type_options(), true)) {
        $type = '';
    }

    if (!array_key_exists($price, livesta_get_property_price_options())) {
        $price = '';
    }

    if (!in_array($layout, livesta_get_property_layout_options(), true)) {
        $layout = '';
    }

    return [
        'area' => $area,
        'type' => $type,
        'price' => $price,
        'layout' => $layout,
    ];
}

function livesta_get_price_in_man(string $price): float {
    $normalized = str_replace([',', '，', ' '], '', $price);
    $total = 0.0;

    if (preg_match('/([0-9]+(?:\.[0-9]+)?)億/u', $normalized, $matches)) {
        $total += (float) $matches[1] * 10000;
    }

    if (preg_match('/([0-9]+(?:\.[0-9]+)?)万/u', $normalized, $matches)) {
        $total += (float) $matches[1];
    }

    if ($total > 0) {
        return $total;
    }

    if (preg_match('/([0-9]+(?:\.[0-9]+)?)/u', $normalized, $matches)) {
        return (float) $matches[1];
    }

    return 0.0;
}

function livesta_matches_price_filter(string $price, string $filter): bool {
    if ($filter === '') {
        return true;
    }

    $value = livesta_get_price_in_man($price);

    if ($value <= 0) {
        return false;
    }

    switch ($filter) {
        case 'up_to_3000':
            return $value <= 3000;
        case '3000_to_5000':
            return $value >= 3000 && $value < 5000;
        case '5000_to_8000':
            return $value >= 5000 && $value < 8000;
        case 'from_8000':
            return $value >= 8000;
        default:
            return true;
    }
}

function livesta_matches_layout_filter(string $layout, string $filter): bool {
    if ($filter === '') {
        return true;
    }

    $layout = strtoupper($layout);

    switch ($filter) {
        case '1R〜1K':
            return strpos($layout, '1R') !== false || strpos($layout, '1K') !== false || strpos($layout, '1DK') !== false;
        case '1LDK〜2LDK':
            return strpos($layout, '1LDK') !== false || strpos($layout, '1SLDK') !== false || strpos($layout, '2LDK') !== false || strpos($layout, '2SLDK') !== false;
        case '3LDK〜':
            return strpos($layout, '3LDK') !== false || strpos($layout, '3SLDK') !== false || strpos($layout, '4LDK') !== false || strpos($layout, '4SLDK') !== false;
        default:
            return true;
    }
}

function livesta_property_item_matches_filters(array $item, array $filters): bool {
    $area = isset($item['area_term']) ? (string) $item['area_term'] : '';
    $type = isset($item['type']) ? (string) $item['type'] : '';
    $location = isset($item['location']) ? (string) $item['location'] : '';
    $price = isset($item['price']) ? (string) $item['price'] : '';
    $layout = isset($item['layout']) ? (string) $item['layout'] : '';

    if ($filters['area'] !== '' && $area !== $filters['area'] && strpos($location, $filters['area']) === false) {
        return false;
    }

    if ($filters['type'] !== '' && strpos($type, $filters['type']) === false) {
        return false;
    }

    if (!livesta_matches_price_filter($price, $filters['price'])) {
        return false;
    }

    if (!livesta_matches_layout_filter($layout, $filters['layout'])) {
        return false;
    }

    return true;
}

function livesta_render_property_image(int $post_id, string $title): void {
    if (has_post_thumbnail($post_id)) {
        echo get_the_post_thumbnail($post_id, 'large');
        return;
    }

    $fallback = livesta_get_default_property_image($title, $post_id);
    echo '<img src="' . esc_url(livesta_get_theme_image_url($fallback)) . '" alt="' . esc_attr($title) . '" loading="lazy">';
}

function livesta_render_fallback_form(): void {
    $thanks_url = livesta_get_thanks_page_url();
    $defaults = livesta_get_contact_form_defaults();
    $areas = livesta_get_property_area_options();
    $privacy_url = home_url('/privacy/');
    ?>
    <form class="contact-form-fallback" action="<?php echo esc_url($thanks_url); ?>" method="post">
        <div class="form-grid">
            <div class="form-group full-width">
                <label>お問い合わせ種別 <span class="required">*</span></label>
                <div class="radio-row">
                    <label class="choice-chip"><input type="radio" name="inquiry_type" value="物件について" <?php checked($defaults['inquiry_type'], '物件について'); ?>><span>物件について</span></label>
                    <label class="choice-chip"><input type="radio" name="inquiry_type" value="売却査定" <?php checked($defaults['inquiry_type'], '売却査定'); ?>><span>売却査定</span></label>
                    <label class="choice-chip"><input type="radio" name="inquiry_type" value="賃貸管理" <?php checked($defaults['inquiry_type'], '賃貸管理'); ?>><span>賃貸管理</span></label>
                    <label class="choice-chip"><input type="radio" name="inquiry_type" value="来店予約" <?php checked($defaults['inquiry_type'], '来店予約'); ?>><span>来店予約</span></label>
                    <label class="choice-chip"><input type="radio" name="inquiry_type" value="その他" <?php checked($defaults['inquiry_type'], 'その他'); ?>><span>その他</span></label>
                </div>
            </div>
            <div class="form-group full-width">
                <label>対象物件</label>
                <input type="text" name="inquiry_property" value="<?php echo esc_attr($defaults['inquiry_property']); ?>" placeholder="例）ザ・パークレジデンス代官山">
            </div>
            <div class="form-group">
                <label>お名前 <span class="required">*</span></label>
                <input type="text" name="inquiry_name" placeholder="例）山田 太郎" autocomplete="name" required>
            </div>
            <div class="form-group">
                <label>フリガナ <span class="required">*</span></label>
                <input type="text" name="inquiry_kana" placeholder="例）ヤマダ タロウ" required>
            </div>
            <div class="form-group">
                <label>メールアドレス <span class="required">*</span></label>
                <input type="email" name="inquiry_email" placeholder="例）taro@example.com" autocomplete="email" required>
            </div>
            <div class="form-group">
                <label>電話番号</label>
                <input type="tel" name="inquiry_tel" inputmode="tel" placeholder="例）09012345678">
            </div>
            <div class="form-group full-width">
                <label>ご住所</label>
                <input type="text" name="inquiry_address" placeholder="例）渋谷区恵比寿西">
            </div>
            <div class="form-group">
                <label>ご希望のエリア</label>
                <select name="inquiry_area">
                    <option>選択してください</option>
                    <?php foreach ($areas as $area) : ?>
                        <option value="<?php echo esc_attr($area); ?>"><?php echo esc_html($area); ?></option>
                    <?php endforeach; ?>
                    <option>その他</option>
                </select>
            </div>
            <div class="form-group">
                <label>ご希望の価格帯</label>
                <select name="inquiry_price">
                    <option>選択してください</option>
                    <option>〜3,000万円</option>
                    <option>3,000万〜5,000万円</option>
                    <option>5,000万〜8,000万円</option>
                    <option>8,000万円〜</option>
                </select>
            </div>
            <div class="form-group full-width">
                <label>ご希望の間取り</label>
                <select name="inquiry_layout">
                    <option>選択してください</option>
                    <option>1R〜1K</option>
                    <option>1LDK〜2LDK</option>
                    <option>3LDK〜</option>
                </select>
            </div>
            <div class="form-group full-width">
                <label>お問い合わせ内容 <span class="required">*</span></label>
                <textarea id="inquiry-message" name="inquiry_message" rows="7" placeholder="ご質問やご要望をご記入ください" required><?php echo esc_textarea($defaults['inquiry_message']); ?></textarea>
            </div>
            <div class="form-group full-width check-group">
                <label class="consent-card">
                    <input class="consent-input" type="checkbox" name="inquiry_privacy_consent" value="1" required>
                    <span class="consent-copy">個人情報の取り扱いについて同意する</span>
                </label>
                <p class="form-subnote"><a href="<?php echo esc_url($privacy_url); ?>">個人情報保護方針</a>をご確認の上、送信してください。</p>
            </div>
        </div>
        <div class="form-actions">
            <button type="submit" class="contact-btn">送信する</button>
        </div>
    </form>
    <p class="form-note"><?php echo esc_html(livesta_get_company_value('response_time')); ?></p>
    <?php
}
