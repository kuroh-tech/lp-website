<?php
if (!defined('ABSPATH')) {
    exit;
}

function haru_bootstrap_news_samples(): array
{
    return [
        [
            'title' => '3月の定休日のご案内',
            'post_name' => 'march-closed-days',
            'category_slug' => 'info',
            'date' => '2026-03-01',
            'content' => implode("\n", [
                '<p>いつもHARU COFFEEをご利用いただきありがとうございます。3月は毎週火曜日に加え、店内メンテナンスのため3月18日をお休みとさせていただきます。</p>',
                '<ul>',
                '<li>定休日: 3月3日、10日、17日、24日、31日</li>',
                '<li>臨時休業: 3月18日</li>',
                '</ul>',
                '<p>平日は11:00-18:00、土日祝は10:00-18:00で営業予定です。ご来店前の最新情報はInstagramでもご案内しています。</p>',
            ]),
        ],
        [
            'title' => '春限定「桜ブレンド」の販売を開始しました',
            'post_name' => 'sakura-blend',
            'category_slug' => 'new-menu',
            'date' => '2026-02-15',
            'content' => implode("\n", [
                '<p>春限定のシーズナルブレンド「桜ブレンド」の販売を開始しました。エチオピアとグアテマラをベースに、やわらかな甘みと花のような余韻を楽しめる中煎りです。</p>',
                '<p>店内ではハンドドリップとラテでご提供するほか、100gから豆の販売も行っています。さくらスコーンとの組み合わせもおすすめです。</p>',
                '<p>数量限定のため、なくなり次第終了となります。</p>',
            ]),
        ],
        [
            'title' => '2月の営業時間変更のお知らせ',
            'post_name' => 'february-hours-update',
            'category_slug' => 'info',
            'date' => '2026-02-01',
            'content' => implode("\n", [
                '<p>イベント出店および焙煎スケジュール調整のため、2月の一部営業日で営業時間を変更いたします。</p>',
                '<ul>',
                '<li>2月6日: 11:00-17:00</li>',
                '<li>2月12日: 12:00-18:00</li>',
                '<li>2月23日: 10:00-16:00</li>',
                '</ul>',
                '<p>通常営業日は11:00-18:00です。変更がある場合はInstagramでも随時お知らせします。</p>',
            ]),
        ],
        [
            'title' => '2月のコーヒーセミナー開催のお知らせ',
            'post_name' => 'february-coffee-seminar',
            'category_slug' => 'event',
            'date' => '2026-01-20',
            'content' => implode("\n", [
                '<p>2月21日に少人数制のコーヒーセミナーを開催します。テーマは「ご自宅で楽しむハンドドリップの基本」。抽出のポイントや器具選びを、実演を交えながらご紹介します。</p>',
                '<ul>',
                '<li>開催日: 2月21日 9:00-10:30</li>',
                '<li>定員: 6名</li>',
                '<li>参加費: 2,500円（テイスティング付き）</li>',
                '</ul>',
                '<p>参加希望の方はお問い合わせフォームまたはInstagramのDMからご連絡ください。</p>',
            ]),
        ],
        [
            'title' => '冬季限定「ショコラモカ」が登場',
            'post_name' => 'winter-chocolat-mocha',
            'category_slug' => 'new-menu',
            'date' => '2026-01-10',
            'content' => implode("\n", [
                '<p>冬季限定ドリンク「ショコラモカ」の提供をスタートしました。ビターなエスプレッソに自家製チョコレートソースとミルクを合わせ、すっきりとした甘さに仕上げています。</p>',
                '<p>ホットのみのご提供で、店内では自家製プリンとのセットもご用意しています。寒い日のひと休みにぜひお試しください。</p>',
            ]),
        ],
        [
            'title' => '年始の営業日のご案内',
            'post_name' => 'new-year-opening-hours',
            'category_slug' => 'info',
            'date' => '2026-01-05',
            'content' => implode("\n", [
                '<p>新年もHARU COFFEEをご利用いただきありがとうございます。年始は1月5日より営業を開始いたします。</p>',
                '<ul>',
                '<li>1月5日-1月8日: 11:00-17:00</li>',
                '<li>1月9日以降: 通常営業</li>',
                '</ul>',
                '<p>今年も、ていねいに淹れたコーヒーと手づくりのお菓子をご用意してお待ちしています。</p>',
            ]),
        ],
    ];
}

function haru_bootstrap_is_default_news_post(WP_Post $post): bool
{
    $default_titles = ['Hello world!', 'Hello World!'];

    return in_array($post->post_title, $default_titles, true) || $post->post_name === 'hello-world';
}

function haru_bootstrap_find_post_by_title(string $title): ?WP_Post
{
    $posts = get_posts([
        'post_type' => 'post',
        'post_status' => ['publish', 'draft', 'pending', 'future', 'private'],
        'posts_per_page' => 20,
        'orderby' => 'date',
        'order' => 'DESC',
    ]);

    foreach ($posts as $post) {
        if ($post->post_title === $title) {
            return $post;
        }
    }

    return null;
}

function haru_bootstrap_find_news_post(array $item): ?WP_Post
{
    $existing = get_page_by_path($item['post_name'], OBJECT, 'post');

    if ($existing instanceof WP_Post) {
        return $existing;
    }

    return haru_bootstrap_find_post_by_title($item['title']);
}

function haru_bootstrap_upsert_news_post(array $item, ?int $reuse_post_id = null): int
{
    $existing = null;

    if ($reuse_post_id) {
        $maybe_post = get_post($reuse_post_id);

        if ($maybe_post instanceof WP_Post) {
            $existing = $maybe_post;
        }
    }

    if (!($existing instanceof WP_Post)) {
        $existing = haru_bootstrap_find_news_post($item);
    }

    $post_data = [
        'post_type' => 'post',
        'post_status' => 'publish',
        'post_title' => $item['title'],
        'post_name' => $item['post_name'],
        'post_content' => $item['content'],
        'post_date' => $item['date'] . ' 10:00:00',
        'post_date_gmt' => get_gmt_from_date($item['date'] . ' 10:00:00'),
    ];

    if ($existing instanceof WP_Post) {
        $post_data['ID'] = $existing->ID;
        $post_id = wp_update_post($post_data, true);
    } else {
        $post_id = wp_insert_post($post_data, true);
    }

    if (is_wp_error($post_id)) {
        return 0;
    }

    $term = get_term_by('slug', $item['category_slug'], 'category');

    if ($term instanceof WP_Term) {
        wp_set_post_terms((int) $post_id, [(int) $term->term_id], 'category');
    }

    return (int) $post_id;
}

function haru_bootstrap_seed_news_posts(): void
{
    $samples = haru_bootstrap_news_samples();
    $default_post = get_page_by_path('hello-world', OBJECT, 'post');
    $has_demo_posts = false;

    foreach ($samples as $item) {
        if (haru_bootstrap_find_news_post($item) instanceof WP_Post) {
            $has_demo_posts = true;
            break;
        }
    }

    if (!($default_post instanceof WP_Post)) {
        $published_posts = get_posts([
            'post_type' => 'post',
            'post_status' => 'publish',
            'posts_per_page' => 5,
        ]);

        if (!$has_demo_posts && !empty($published_posts)) {
            return;
        }
    }

    $reuse_post_id = null;

    if ($default_post instanceof WP_Post && haru_bootstrap_is_default_news_post($default_post)) {
        foreach ($samples as $index => $item) {
            if (!(haru_bootstrap_find_news_post($item) instanceof WP_Post)) {
                $reuse_post_id = $default_post->ID;
                haru_bootstrap_upsert_news_post($item, $reuse_post_id);
                unset($samples[$index]);
                break;
            }
        }

        if (!$reuse_post_id) {
            wp_update_post([
                'ID' => $default_post->ID,
                'post_status' => 'draft',
            ]);
        }
    }

    foreach ($samples as $item) {
        haru_bootstrap_upsert_news_post($item);
    }
}

function haru_bootstrap_upsert_page(string $title, string $slug, string $content = ''): int
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

function haru_bootstrap_site(): void
{
    $seed_version = '2';

    if (get_option('haru_demo_seed_version') === $seed_version) {
        return;
    }

    $front = haru_bootstrap_upsert_page('トップ', 'home');
    haru_bootstrap_upsert_page('コンセプト', 'concept');
    haru_bootstrap_upsert_page('メニュー', 'menu');
    haru_bootstrap_upsert_page('アクセス', 'access');
    haru_bootstrap_upsert_page('お問い合わせ', 'contact');
    haru_bootstrap_upsert_page('送信完了', 'thanks');
    haru_bootstrap_upsert_page('プライバシーポリシー', 'privacy', "HARU COFFEEは、お問い合わせで取得した個人情報を返信・案内の目的にのみ利用します。");
    $news = haru_bootstrap_upsert_page('お知らせ', 'news');

    update_option('show_on_front', 'page');
    update_option('page_on_front', $front);
    update_option('page_for_posts', $news);
    update_option('blogname', 'HARU COFFEE');
    update_option('blogdescription', '一杯に、ていねいな時間を。');

    $categories = [
        ['name' => 'お知らせ', 'slug' => 'info'],
        ['name' => '新メニュー', 'slug' => 'new-menu'],
        ['name' => 'イベント', 'slug' => 'event'],
    ];

    foreach ($categories as $category) {
        if (!term_exists($category['slug'], 'category')) {
            wp_insert_term($category['name'], 'category', ['slug' => $category['slug']]);
        }
    }

    haru_bootstrap_seed_news_posts();

    update_option('haru_demo_seeded', '1');
    update_option('haru_demo_seed_version', $seed_version);
    flush_rewrite_rules(false);
}
