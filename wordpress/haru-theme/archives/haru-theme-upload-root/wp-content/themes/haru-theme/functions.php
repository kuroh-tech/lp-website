<?php
if (!defined('ABSPATH')) {
    exit;
}

define('HARU_THEME_VERSION', '1.1.1');

function haru_setup_theme(): void
{
    add_theme_support('title-tag');
    add_theme_support('post-thumbnails');
    add_theme_support(
        'html5',
        ['search-form', 'comment-form', 'comment-list', 'gallery', 'caption', 'style', 'script']
    );

    register_nav_menus([
        'primary' => 'Primary Menu',
    ]);
}
add_action('after_setup_theme', 'haru_setup_theme');

function haru_enqueue_assets(): void
{
    wp_enqueue_style(
        'haru-google-fonts',
        'https://fonts.googleapis.com/css2?family=Noto+Sans+JP:wght@300;400;500;700;900&family=Zen+Kaku+Gothic+New:wght@400;700;900&display=swap',
        [],
        null
    );

    wp_enqueue_style(
        'haru-style',
        get_stylesheet_uri(),
        ['haru-google-fonts'],
        HARU_THEME_VERSION
    );

    wp_enqueue_style(
        'haru-main',
        get_theme_file_uri('/assets/css/main.css'),
        ['haru-style'],
        HARU_THEME_VERSION
    );

    wp_enqueue_script(
        'haru-main',
        get_theme_file_uri('/assets/js/main.js'),
        [],
        HARU_THEME_VERSION,
        true
    );
}
add_action('wp_enqueue_scripts', 'haru_enqueue_assets');

function haru_get_profile(): array
{
    return [
        'site_name' => 'HARU COFFEE',
        'tagline' => '一杯に、ていねいな時間を。',
        'address' => '〒111-0051 東京都台東区蔵前3-12-8 1F',
        'phone' => '03-5820-0001',
        'hours' => '8:00〜18:00（L.O. 17:30）',
        'closed' => '毎週水曜日',
        'seats' => '12席（カウンター4席 / テーブル8席）',
        'instagram' => 'https://instagram.com/haru_coffee_kuramae',
        'instagram_handle' => '@haru_coffee_kuramae',
        'map_query' => '東京都台東区蔵前3-12-8',
        'description' => '自家焙煎のスペシャルティコーヒーと手づくりのお菓子で、ていねいな時間を届ける蔵前のちいさな珈琲店です。',
    ];
}

function haru_get_phone_href(): string
{
    $profile = haru_get_profile();
    $digits = preg_replace('/[^0-9+]/', '', $profile['phone']);

    return $digits ? 'tel:' . $digits : '';
}

function haru_get_map_embed_url(): string
{
    $profile = haru_get_profile();

    return 'https://www.google.com/maps?q=' . rawurlencode($profile['map_query']) . '&z=16&output=embed';
}

function haru_get_news_url(): string
{
    $page_id = (int) get_option('page_for_posts');
    $url = $page_id ? get_permalink($page_id) : '';

    return $url ?: home_url('/news/');
}

function haru_get_media_library(): array
{
    return [
        'concept-hero' => [
            'file' => 'concept-hero.png',
            'title' => 'Concept Hero',
            'alt' => 'やわらかな朝の光が差し込むHARU COFFEEの店内イメージ',
        ],
        'concept-coffee' => [
            'file' => 'concept-coffee.png',
            'title' => 'Coffee Craft',
            'alt' => 'ハンドドリップでコーヒーを淹れる様子のイメージ',
        ],
        'concept-sweets' => [
            'file' => 'concept-sweets.png',
            'title' => 'House Sweets',
            'alt' => '手づくりのお菓子が並ぶイメージ',
        ],
        'concept-space' => [
            'file' => 'concept-space.png',
            'title' => 'Quiet Space',
            'alt' => '自然光の入る落ち着いた店内イメージ',
        ],
        'owner-portrait' => [
            'file' => 'owner-portrait.png',
            'title' => 'Owner Portrait',
            'alt' => '店主のポートレートイメージ',
        ],
        'menu-hero' => [
            'file' => 'menu-hero.png',
            'title' => 'Menu Hero',
            'alt' => 'コーヒーと焼き菓子が並ぶテーブルのイメージ',
        ],
        'menu-blend' => [
            'file' => 'menu-blend.png',
            'title' => 'HARU Blend',
            'alt' => 'HARUブレンドのカップ写真イメージ',
        ],
        'menu-single-origin' => [
            'file' => 'menu-single-origin.png',
            'title' => 'Single Origin',
            'alt' => '季節のシングルオリジンのイメージ',
        ],
        'food-pound' => [
            'file' => 'food-pound.png',
            'title' => 'Pound Cake',
            'alt' => '本日のパウンドケーキのイメージ',
        ],
        'food-scone' => [
            'file' => 'food-scone.png',
            'title' => 'Seasonal Scone',
            'alt' => '季節のスコーンのイメージ',
        ],
        'food-toast' => [
            'file' => 'food-toast.png',
            'title' => 'An Butter Toast',
            'alt' => 'あんバタートーストのイメージ',
        ],
        'food-pudding' => [
            'file' => 'food-pudding.png',
            'title' => 'Classic Pudding',
            'alt' => '本日のプリンのイメージ',
        ],
        'access-hero' => [
            'file' => 'access-hero.png',
            'title' => 'Access Hero',
            'alt' => '蔵前の街並みと店舗外観のイメージ',
        ],
        'inside-counter' => [
            'file' => 'inside-counter.png',
            'title' => 'Counter Seats',
            'alt' => 'カウンター席のイメージ',
        ],
        'inside-table' => [
            'file' => 'inside-table.png',
            'title' => 'Table Seats',
            'alt' => 'テーブル席のイメージ',
        ],
        'inside-exterior' => [
            'file' => 'inside-exterior.png',
            'title' => 'Exterior',
            'alt' => '外観のイメージ',
        ],
        'gallery-01' => [
            'file' => 'gallery-01.png',
            'title' => 'Drip Counter',
            'alt' => 'ドリップ中のサーバーのイメージ',
        ],
        'gallery-02' => [
            'file' => 'gallery-02.png',
            'title' => 'Roasted Beans',
            'alt' => '自家焙煎豆のクローズアップイメージ',
        ],
        'gallery-03' => [
            'file' => 'gallery-03.png',
            'title' => 'Latte Art',
            'alt' => 'ラテアートのイメージ',
        ],
        'gallery-04' => [
            'file' => 'gallery-04.png',
            'title' => 'Reading Table',
            'alt' => 'コーヒーと本が置かれた窓際テーブルのイメージ',
        ],
        'gallery-05' => [
            'file' => 'gallery-05.png',
            'title' => 'Interior Detail',
            'alt' => '木製スツールと小物のイメージ',
        ],
        'gallery-06' => [
            'file' => 'gallery-06.png',
            'title' => 'Sweets Display',
            'alt' => '焼き菓子ショーケースのイメージ',
        ],
        'gallery-07' => [
            'file' => 'gallery-07.png',
            'title' => 'Wood Sign',
            'alt' => '木製看板のアップイメージ',
        ],
        'gallery-08' => [
            'file' => 'gallery-08.png',
            'title' => 'Morning Light',
            'alt' => '朝の店内の光のイメージ',
        ],
    ];
}

function haru_get_media_item(string $key): array
{
    $library = haru_get_media_library();

    return $library[$key] ?? [
        'file' => $key . '.png',
        'title' => $key,
        'alt' => $key,
    ];
}

function haru_get_media_path(string $key): string
{
    $media = haru_get_media_item($key);

    return get_theme_file_path('/assets/images/' . ltrim($media['file'], '/'));
}

function haru_get_media_url(string $key): string
{
    $media = haru_get_media_item($key);

    return get_theme_file_uri('/assets/images/' . ltrim($media['file'], '/'));
}

function haru_get_featured_menu(): array
{
    return [
        ['category' => 'Coffee', 'title' => 'HARUブレンド', 'price' => '¥550', 'image' => 'menu-blend'],
        ['category' => 'Coffee', 'title' => '季節のシングルオリジン', 'price' => '¥650', 'image' => 'menu-single-origin'],
        ['category' => 'Sweets', 'title' => '本日のパウンドケーキ', 'price' => '¥450', 'image' => 'food-pound'],
    ];
}

function haru_get_gallery_items(): array
{
    return [
        ['key' => 'gallery-01', 'title' => 'ドリップ'],
        ['key' => 'gallery-02', 'title' => '焙煎豆'],
        ['key' => 'gallery-03', 'title' => 'ラテアート'],
        ['key' => 'gallery-04', 'title' => '読書時間'],
        ['key' => 'gallery-05', 'title' => '店内ディテール'],
        ['key' => 'gallery-06', 'title' => '焼き菓子'],
        ['key' => 'gallery-07', 'title' => '木製看板'],
        ['key' => 'gallery-08', 'title' => '朝の光'],
    ];
}

function haru_get_story_content(): array
{
    return [
        'quote' => '「毎日飲むコーヒーだからこそ、ていねいに淹れたい。」',
        'body' => [
            '2019年、そんな想いから蔵前の路地裏にHARUは生まれました。大きな看板もなく、席数はわずか12席。それでも、一杯ずつ心を込めてお淹れするコーヒーと、手づくりのお菓子でお迎えするこの場所を、たくさんの方が見つけてくださいました。',
            '「HARU」という名前には、毎日が春のようにあたたかくありますように、という願いを込めています。コーヒー一杯で、日々の暮らしに小さな春を届けたい。それが、私たちの変わらない想いです。',
        ],
    ];
}

function haru_get_commitments(): array
{
    return [
        [
            'number' => '01',
            'title' => 'コーヒー',
            'body' => '産地の個性が活きる自家焙煎。鮮度にこだわり、少量ずつ焙煎しています。',
            'anchor' => '#coffee',
        ],
        [
            'number' => '02',
            'title' => 'お菓子',
            'body' => 'コーヒーに寄り添う手づくりのお菓子。素材の味わいを大切にしています。',
            'anchor' => '#sweets',
        ],
        [
            'number' => '03',
            'title' => '空間',
            'body' => '忙しい日常から離れて、ほっと一息つける居場所を目指しています。',
            'anchor' => '#space',
        ],
    ];
}

function haru_get_concept_sections(): array
{
    return [
        [
            'id' => 'coffee',
            'eyebrow' => 'Coffee',
            'title' => '自家焙煎のスペシャルティコーヒー',
            'body' => [
                'HARUで使用するコーヒー豆は、すべてスペシャルティグレード。信頼のおけるインポーターを通じて、エチオピア・グアテマラ・コスタリカなど、季節ごとに旬の産地から届きます。',
                '焙煎は店内の小型焙煎機で少量ずつ。大量生産では出せない豆一つひとつの個性を引き出し、焙煎から2週間以内のフレッシュな豆だけを提供しています。',
                '抽出はハンドドリップが基本。お湯の温度、注ぐスピード、蒸らしの時間まで丁寧にコントロールし、その豆の最も美味しい一杯を目指しています。',
            ],
            'image' => 'concept-coffee',
            'layout' => 'split',
        ],
        [
            'id' => 'sweets',
            'eyebrow' => 'Sweets',
            'title' => 'コーヒーに寄り添う、手づくりのお菓子',
            'body' => [
                'HARUのお菓子は、すべて店内の小さなキッチンで手づくりしています。バター・卵・小麦粉は国産のものを中心に、素材そのものの味わいが感じられるシンプルなレシピを大切にしています。',
                '定番のパウンドケーキはしっとりとした食感で、コーヒーとの相性を考えて甘さを控えめに。季節ごとのスコーンやプリンも、旬の素材を取り入れながら少しずつメニューを入れ替えています。',
                'コーヒーとお菓子のペアリングを楽しんでいただけるよう、スタッフにお声がけいただければ、その日のおすすめの組み合わせをご提案します。',
            ],
            'image' => 'concept-sweets',
            'layout' => 'reverse',
        ],
        [
            'id' => 'space',
            'eyebrow' => 'Space',
            'title' => 'ほっと一息つける、ちいさな居場所',
            'body' => [
                'HARUの店内は、木とアイアンを基調としたあたたかみのある空間です。カウンター4席とテーブル8席の小さなお店ですが、その分、一人ひとりのお客様との距離が近い接客を大切にしています。',
                '窓から差し込む自然光と、静かに流れる音楽。読書をしたり、ぼんやりと考えごとをしたり、ただコーヒーの香りに包まれるだけでもかまいません。忙しい日常のなかで、ふと立ち止まれる場所であれたら嬉しいです。',
            ],
            'image' => 'concept-space',
            'layout' => 'wide',
        ],
    ];
}

function haru_get_menu_sections(): array
{
    return [
        'coffee' => [
            'eyebrow' => 'Coffee',
            'title' => 'コーヒー',
            'items' => [
                ['name' => 'HARUブレンド', 'desc' => 'ナッツとチョコレートの風味。毎日飲んでも飽きのこない定番ブレンド。', 'price' => '¥550', 'note' => 'Hot / Ice'],
                ['name' => '季節のシングルオリジン', 'desc' => '旬の産地から届く豆を、その時季に合う焙煎でご提供。', 'price' => '¥650', 'note' => 'Hot / Ice'],
                ['name' => '深煎りブレンド', 'desc' => 'スモーキーでコクのある深煎り。ミルクとの相性も抜群です。', 'price' => '¥550', 'note' => 'Hot / Ice'],
                ['name' => 'カフェラテ', 'desc' => '自家焙煎エスプレッソと北海道産低温殺菌牛乳を合わせたラテ。', 'price' => '¥600', 'note' => 'Hot / Ice'],
                ['name' => 'カプチーノ', 'desc' => 'きめ細かいフォームミルクとエスプレッソ。シナモンはお好みで。', 'price' => '¥600', 'note' => 'Hot'],
                ['name' => 'エスプレッソ', 'desc' => '凝縮された味わいを楽しめるシングルショット。', 'price' => '¥400', 'note' => 'Hot'],
            ],
        ],
        'drink' => [
            'eyebrow' => 'Drink',
            'title' => 'その他のドリンク',
            'items' => [
                ['name' => '抹茶ラテ', 'desc' => '京都・宇治の有機抹茶を使用。甘さ控えめで上品な味わい。', 'price' => '¥650', 'note' => 'Hot / Ice'],
                ['name' => 'ほうじ茶ラテ', 'desc' => '香ばしい焙じ茶と牛乳のまろやかなハーモニー。', 'price' => '¥600', 'note' => 'Hot / Ice'],
                ['name' => 'チャイ', 'desc' => 'スパイスから手作りするHARUのチャイ。身体の芯から温まります。', 'price' => '¥650', 'note' => 'Hot'],
                ['name' => '自家製レモネード', 'desc' => '国産レモンを使った季節限定の手作りレモネード。', 'price' => '¥550', 'note' => 'Ice'],
                ['name' => 'りんごジュース', 'desc' => '長野県産ふじ100%のストレートジュース。', 'price' => '¥500', 'note' => 'Ice'],
                ['name' => 'ミネラルウォーター', 'desc' => 'お食事やテイクアウトにも合わせやすい定番ドリンク。', 'price' => '¥200', 'note' => 'Cold'],
            ],
        ],
        'food' => [
            'eyebrow' => 'Food & Sweets',
            'title' => 'フード・スイーツ',
            'cards' => [
                ['title' => '本日のパウンドケーキ', 'body' => 'バターと卵の風味が豊かなしっとりパウンドケーキ。フレーバーは日替わりです。', 'price' => '¥450', 'image' => 'food-pound'],
                ['title' => '季節のスコーン', 'body' => '外はサクッ、中はしっとり。自家製ジャムを添えて提供しています。', 'price' => '¥400', 'image' => 'food-scone'],
                ['title' => 'あんバタートースト', 'body' => '厚切り食パンに北海道バターと自家製つぶあんをたっぷりと。', 'price' => '¥500', 'image' => 'food-toast'],
                ['title' => '本日のプリン', 'body' => 'なめらかな食感の固めプリン。ほろ苦いカラメルがアクセント。', 'price' => '¥480', 'image' => 'food-pudding'],
            ],
        ],
    ];
}

function haru_get_takeout_copy(): array
{
    return [
        'すべてのドリンクメニューはテイクアウト可能です。テイクアウトカップは環境に配慮した紙製カップを使用しています。',
        'マイカップをお持ちいただいた方には、¥30引きでご提供します。店頭の混雑状況によっては少しお待ちいただく場合がございます。',
    ];
}

function haru_get_access_rows(): array
{
    $profile = haru_get_profile();

    return [
        ['label' => '店名', 'value' => $profile['site_name'] . '（ハルコーヒー）'],
        ['label' => '住所', 'value' => $profile['address']],
        ['label' => '電話番号', 'value' => $profile['phone'], 'href' => haru_get_phone_href()],
        ['label' => '営業時間', 'value' => $profile['hours']],
        ['label' => '定休日', 'value' => $profile['closed']],
        ['label' => '席数', 'value' => $profile['seats']],
        ['label' => '喫煙', 'value' => '全席禁煙'],
        ['label' => 'Wi-Fi', 'value' => 'あり（無料）'],
        ['label' => '電源', 'value' => 'カウンター席のみあり'],
        ['label' => '決済方法', 'value' => '現金 / クレジットカード / 交通系IC / PayPay'],
        ['label' => '駐車場', 'value' => 'なし（近隣のコインパーキングをご利用ください）'],
    ];
}

function haru_get_access_routes(): array
{
    return [
        [
            'title' => '電車でお越しの方',
            'items' => [
                '都営浅草線「蔵前」駅 A1出口より徒歩3分',
                '都営大江戸線「蔵前」駅 A7出口より徒歩4分',
                'JR総武線「浅草橋」駅 西口より徒歩10分',
            ],
        ],
        [
            'title' => 'バスでお越しの方',
            'items' => [
                '都営バス「蔵前二丁目」停留所より徒歩1分',
            ],
        ],
        [
            'title' => 'お車でお越しの方',
            'items' => [
                '専用駐車場はございません。',
                '近隣のコインパーキングをご利用ください。',
            ],
        ],
    ];
}

function haru_get_inside_cards(): array
{
    return [
        [
            'title' => 'カウンター席（4席）',
            'body' => '目の前でドリップする様子をご覧いただけるカウンター。おひとり様にも人気のお席です。',
            'image' => 'inside-counter',
        ],
        [
            'title' => 'テーブル席（8席）',
            'body' => '2名掛けテーブルが4卓。窓際の席は自然光が差し込み、読書にもおすすめです。',
            'image' => 'inside-table',
        ],
        [
            'title' => '外観',
            'body' => '蔵前の路地裏にひっそりと佇む白い建物の1階。小さな木の看板が目印です。',
            'image' => 'inside-exterior',
        ],
    ];
}

function haru_get_notices(): array
{
    return [
        'ご予約は承っておりません。直接ご来店ください。',
        '混雑時はお待ちいただく場合がございます。',
        '店内での撮影はご自由にどうぞ（他のお客様へのご配慮をお願いします）。',
        'お子様連れも歓迎です。ベビーカーでのご来店も可能です。',
        'ペットの同伴はご遠慮いただいております。',
        '臨時休業・営業時間変更はInstagramでお知らせします。',
    ];
}

function haru_get_contact_methods(): array
{
    $profile = haru_get_profile();

    return [
        [
            'title' => 'SNS DM',
            'body' => 'Instagramのダイレクトメッセージが最もスムーズです。' . "\n" . $profile['instagram_handle'],
        ],
        [
            'title' => 'メールフォーム',
            'body' => '下記フォームよりお問い合わせください。' . "\n" . '3営業日以内にご返信いたします。',
        ],
    ];
}

function haru_get_owner_profile(): array
{
    return [
        'name' => '春山 みずき',
        'role' => '店主 / Roaster & Barista',
        'body' => 'コーヒー豆の焙煎から抽出、お菓子のメニュー監修までを担当。季節ごとの豆選びと、ゆっくり過ごせる空間づくりを大切にしています。',
    ];
}

function haru_get_news_category_specs(): array
{
    return [
        'info' => 'お知らせ',
        'new-menu' => '新メニュー',
        'event' => 'イベント',
    ];
}

function haru_get_news_categories(): array
{
    $categories = [];

    foreach (haru_get_news_category_specs() as $slug => $label) {
        $term = get_term_by('slug', $slug, 'category');
        if ($term instanceof WP_Term) {
            $categories[] = $term;
        }
    }

    return $categories;
}

function haru_get_primary_news_category($post = null): ?WP_Term
{
    $terms = get_the_category($post);
    $allowed = array_keys(haru_get_news_category_specs());

    foreach ($terms as $term) {
        if (in_array($term->slug, $allowed, true)) {
            return $term;
        }
    }

    return $terms[0] ?? null;
}

function haru_get_latest_news(int $count = 3): array
{
    return get_posts([
        'post_type' => 'post',
        'post_status' => 'publish',
        'posts_per_page' => $count,
        'ignore_sticky_posts' => true,
    ]);
}

function haru_get_news_badge_color(string $slug): string
{
    $map = [
        'info' => '#7A8450',
        'new-menu' => '#6B4226',
        'event' => '#8B6347',
    ];

    return $map[$slug] ?? '#6B4226';
}

function haru_render_page_hero(
    string $eyebrow,
    string $title,
    string $subtitle = '',
    bool $mini = false,
    string $media_key = ''
): void {
    $classes = ['page-hero'];

    if ($mini) {
        $classes[] = 'mini';
    }

    if ($media_key !== '') {
        $classes[] = 'has-media';
    }
    ?>
    <section class="<?php echo esc_attr(implode(' ', $classes)); ?>">
        <?php if ($media_key !== '') : ?>
            <div class="page-hero-media" aria-hidden="true">
                <?php haru_render_placeholder($media_key, 'hero-fill'); ?>
            </div>
        <?php endif; ?>
        <div class="section-inner centered page-hero-inner">
            <p class="section-eyebrow"><?php echo esc_html($eyebrow); ?></p>
            <h1 class="page-title"><?php echo esc_html($title); ?></h1>
            <?php if ($subtitle !== '') : ?>
                <p class="page-subtitle"><?php echo esc_html($subtitle); ?></p>
            <?php endif; ?>
        </div>
    </section>
    <?php
}

function haru_render_media(string $key, string $shape = ''): void
{
    $media = haru_get_media_item($key);
    $classes = 'media-frame';
    $path = haru_get_media_path($key);
    $is_priority_media = in_array($shape, ['hero-fill', 'arch'], true);

    if ($shape !== '') {
        $classes .= ' ' . $shape;
    }

    if (file_exists($path)) {
        $attributes = [
            'class' => 'media-image',
            'src' => haru_get_media_url($key),
            'alt' => $media['alt'],
            'decoding' => 'async',
            'loading' => $is_priority_media ? 'eager' : 'lazy',
        ];

        if ($is_priority_media) {
            $attributes['fetchpriority'] = 'high';
        }
        ?>
        <figure class="<?php echo esc_attr($classes); ?>">
            <img
                <?php foreach ($attributes as $attribute => $value) : ?>
                    <?php echo esc_attr($attribute); ?>="<?php echo esc_attr($value); ?>"
                <?php endforeach; ?>
            >
        </figure>
        <?php

        return;
    }
    $classes .= ' is-fallback';
    ?>
    <div class="<?php echo esc_attr($classes); ?>" role="img" aria-label="<?php echo esc_attr($media['alt']); ?>">
        <span class="media-frame-title"><?php echo esc_html($media['title']); ?></span>
        <small class="media-frame-file"><?php echo esc_html($media['file']); ?></small>
    </div>
    <?php
}

function haru_render_placeholder(string $key, string $shape = ''): void
{
    haru_render_media($key, $shape);
}

function haru_render_news_row(WP_Post $post, bool $reveal = true): void
{
    $term = haru_get_primary_news_category($post);
    $classes = 'news-row';

    if ($reveal) {
        $classes .= ' reveal';
    }
    ?>
    <article class="<?php echo esc_attr($classes); ?>">
        <span class="news-date"><?php echo esc_html(get_the_date('Y.m.d', $post)); ?></span>
        <?php if ($term instanceof WP_Term) : ?>
            <span class="news-badge" style="background:<?php echo esc_attr(haru_get_news_badge_color($term->slug)); ?>">
                <?php echo esc_html($term->name); ?>
            </span>
        <?php endif; ?>
        <a class="news-title" href="<?php echo esc_url(get_permalink($post)); ?>">
            <?php echo esc_html(get_the_title($post)); ?>
        </a>
    </article>
    <?php
}

function haru_render_common_cta(): void
{
    $profile = haru_get_profile();
    ?>
    <section class="cta-section" id="contact">
        <div class="section-inner centered">
            <p class="section-eyebrow">Instagram</p>
            <h2 class="section-title">最新情報はInstagramでお知らせしています</h2>
            <p class="section-desc slim centered">新メニューや営業時間変更、イベント情報はInstagramが最も早く更新されます。</p>
            <div class="cta-sns">
                <a href="<?php echo esc_url($profile['instagram']); ?>" target="_blank" rel="noreferrer noopener">Instagram</a>
                <a href="<?php echo esc_url(home_url('/contact/')); ?>">Contact</a>
            </div>
        </div>
    </section>
    <?php
}

function haru_render_contact_form(): void
{
    ?>
    <form class="contact-form" action="<?php echo esc_url(home_url('/thanks/')); ?>" method="get">
        <fieldset class="radio-group">
            <legend>お問い合わせ種別 <span class="required-badge">必須</span></legend>
            <div class="radio-row">
                <label class="radio-card">
                    <input type="radio" name="type" value="取材・メディア掲載" checked>
                    <span>取材・メディア掲載</span>
                </label>
                <label class="radio-card">
                    <input type="radio" name="type" value="コラボレーション・出店依頼">
                    <span>コラボレーション・出店依頼</span>
                </label>
                <label class="radio-card">
                    <input type="radio" name="type" value="その他のご質問">
                    <span>その他のご質問</span>
                </label>
            </div>
        </fieldset>
        <div class="form-grid">
            <label>
                <span>お名前 <span class="required-badge">必須</span></span>
                <input type="text" name="name" placeholder="例）春山 太郎" required>
            </label>
            <label>
                <span>メールアドレス <span class="required-badge">必須</span></span>
                <input type="email" name="email" placeholder="info@example.com" required>
            </label>
            <label>
                <span>電話番号</span>
                <input type="tel" name="tel" placeholder="03-1234-5678">
            </label>
            <label class="full">
                <span>お問い合わせ内容 <span class="required-badge">必須</span></span>
                <textarea name="message" rows="6" placeholder="お問い合わせ内容をご記入ください。" required></textarea>
            </label>
        </div>
        <label class="privacy-check">
            <input type="checkbox" name="agree" required>
            <span><a href="<?php echo esc_url(home_url('/privacy/')); ?>">プライバシーポリシー</a>に同意する</span>
        </label>
        <button class="button button-primary" type="submit">送信する</button>
    </form>
    <?php
}

function haru_render_posts_pagination(): void
{
    $links = paginate_links([
        'type' => 'array',
        'prev_text' => '←',
        'next_text' => '→',
    ]);

    if (!$links) {
        return;
    }
    ?>
    <nav class="pagination" aria-label="Pagination">
        <?php foreach ($links as $link) : ?>
            <?php echo wp_kses_post($link); ?>
        <?php endforeach; ?>
    </nav>
    <?php
}

function haru_render_post_navigation(): void
{
    $previous = get_previous_post();
    $next = get_next_post();

    if (!$previous && !$next) {
        return;
    }
    ?>
    <nav class="article-nav" aria-label="Post navigation">
        <div class="article-nav-item">
            <?php if ($previous instanceof WP_Post) : ?>
                <span class="article-nav-label">前の記事</span>
                <a href="<?php echo esc_url(get_permalink($previous)); ?>"><?php echo esc_html(get_the_title($previous)); ?></a>
            <?php endif; ?>
        </div>
        <div class="article-nav-item align-right">
            <?php if ($next instanceof WP_Post) : ?>
                <span class="article-nav-label">次の記事</span>
                <a href="<?php echo esc_url(get_permalink($next)); ?>"><?php echo esc_html(get_the_title($next)); ?></a>
            <?php endif; ?>
        </div>
    </nav>
    <?php
}

function haru_filter_document_title_parts(array $parts): array
{
    $profile = haru_get_profile();
    $parts['site'] = $profile['site_name'];

    if (is_front_page()) {
        $parts['title'] = $profile['tagline'];
    }

    return $parts;
}
add_filter('document_title_parts', 'haru_filter_document_title_parts');
