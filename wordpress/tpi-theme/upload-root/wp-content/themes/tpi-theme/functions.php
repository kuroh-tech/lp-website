<?php
if (!defined('ABSPATH')) {
    exit;
}

define('TPI_THEME_VERSION', '1.2.0');

function tpi_setup_theme(): void
{
    add_theme_support('title-tag');
    add_theme_support('post-thumbnails');
    add_theme_support(
        'html5',
        ['search-form', 'comment-form', 'comment-list', 'gallery', 'caption', 'style', 'script']
    );
}
add_action('after_setup_theme', 'tpi_setup_theme');

function tpi_enqueue_assets(): void
{
    wp_enqueue_style(
        'tpi-google-fonts',
        'https://fonts.googleapis.com/css2?family=Noto+Sans+JP:wght@300;400;500;700;900&family=Zen+Kaku+Gothic+New:wght@400;700;900&display=swap',
        [],
        null
    );

    wp_enqueue_style(
        'tpi-style',
        get_stylesheet_uri(),
        ['tpi-google-fonts'],
        TPI_THEME_VERSION
    );

    wp_enqueue_style(
        'tpi-main',
        get_theme_file_uri('/assets/css/main.css'),
        ['tpi-style'],
        TPI_THEME_VERSION
    );

    wp_enqueue_script(
        'tpi-main',
        get_theme_file_uri('/assets/js/main.js'),
        [],
        TPI_THEME_VERSION,
        true
    );
}
add_action('wp_enqueue_scripts', 'tpi_enqueue_assets');

function tpi_get_theme_images(): array
{
    return [
        'home-hero' => 'assets/images/home-hero.png',
        'company-hero' => 'assets/images/company-hero.png',
        'ceo-portrait' => 'assets/images/ceo-portrait.png',
        'company-top' => 'assets/images/company-top.png',
        'products-hero' => 'assets/images/products-hero.png',
        'product-auto-01' => 'assets/images/product-auto-01.png',
        'product-auto-02' => 'assets/images/product-auto-02.png',
        'product-auto-03' => 'assets/images/product-auto-03.png',
        'product-auto-04' => 'assets/images/product-auto-04.png',
        'product-aero-01' => 'assets/images/product-aero-01.png',
        'product-aero-02' => 'assets/images/product-aero-02.png',
        'product-aero-03' => 'assets/images/product-aero-03.png',
        'product-med-01' => 'assets/images/product-med-01.png',
        'product-med-02' => 'assets/images/product-med-02.png',
        'product-med-03' => 'assets/images/product-med-03.png',
        'prototyping' => 'assets/images/prototyping.png',
        'equipment-hero' => 'assets/images/equipment-hero.png',
        'factory-1' => 'assets/images/factory-1.png',
        'factory-2' => 'assets/images/factory-2.png',
        'quality-hero' => 'assets/images/quality-hero.png',
        'quality-inspection' => 'assets/images/quality-inspection.png',
        'recruit-hero' => 'assets/images/recruit-hero.png',
        'staff-01' => 'assets/images/staff-01.png',
        'staff-02' => 'assets/images/staff-02.png',
        'staff-03' => 'assets/images/staff-03.png',
    ];
}

function tpi_get_image_url(string $key): string
{
    $images = tpi_get_theme_images();

    if (!isset($images[$key])) {
        return '';
    }

    $relative = '/' . ltrim($images[$key], '/');
    $path = get_theme_file_path($relative);

    if (!file_exists($path)) {
        return '';
    }

    return (string) get_theme_file_uri($relative);
}

function tpi_get_company_profile(): array
{
    return [
        'site_name' => '東京精密工業株式会社',
        'site_short_name' => '東京精密工業',
        'english_name' => 'Tokyo Precision Industry Co., Ltd.',
        'tagline' => '精度が、信頼をつくる。',
        'representative' => '代表取締役 鈴木 正義',
        'message_sign' => 'Masayoshi Suzuki',
        'founded' => '1974年6月',
        'capital' => '3,000万円',
        'employees' => '85名（2026年3月現在）',
        'sales' => '12億円（2025年度）',
        'address' => '〒144-0035 東京都大田区南蒲田1-2-3',
        'factory2' => '〒144-0035 東京都大田区南蒲田2-5-10',
        'phone' => '03-3730-4510',
        'fax' => '03-3730-4511',
        'hours' => '平日 8:30-17:30',
        'mail' => 'info@tokyo-precision.example',
        'map_query' => '東京都大田区南蒲田1-2-3',
        'description' => '自動車・航空宇宙・医療機器向けの精密加工部品を手がける東京精密工業株式会社のコーポレートサイトです。',
    ];
}

function tpi_get_company_overview_rows(): array
{
    $profile = tpi_get_company_profile();

    return [
        ['label' => '会社名', 'value' => $profile['site_name'] . '（' . $profile['english_name'] . '）'],
        ['label' => '代表者', 'value' => $profile['representative']],
        ['label' => '設立', 'value' => $profile['founded']],
        ['label' => '資本金', 'value' => $profile['capital']],
        ['label' => '従業員数', 'value' => $profile['employees']],
        ['label' => '売上高', 'value' => $profile['sales']],
        ['label' => '本社・工場', 'value' => $profile['address']],
        ['label' => '第二工場', 'value' => $profile['factory2']],
        ['label' => '事業内容', 'value' => '金属精密加工（切削・研削・放電加工） / 試作開発 / 品質検査 / 表面処理'],
        ['label' => '主要取引先', 'value' => '大手自動車メーカー / 航空機エンジンメーカー / 医療機器メーカー（詳細は守秘義務のため非公開）'],
        ['label' => '認証規格', 'value' => 'ISO 9001:2015 / ISO 14001:2015 / JIS Q 9100'],
        ['label' => '取引銀行', 'value' => '三菱UFJ銀行 蒲田支店 / みずほ銀行 蒲田支店'],
    ];
}

function tpi_get_strengths(): array
{
    return [
        [
            'title' => 'ミクロンレベルの加工精度',
            'body' => '5軸マシニングセンタを含む最新設備と、熟練技術者の手技を融合。±0.001mmの超精密加工を安定的に実現します。',
        ],
        [
            'title' => '多品種・小ロット対応',
            'body' => '試作1個から量産まで柔軟に対応。短納期のご要望にも、最適な生産体制で応えます。',
        ],
        [
            'title' => '一貫生産体制',
            'body' => '設計サポート・材料調達・加工・検査・表面処理まで社内一貫対応。品質管理を徹底し、短納期を実現します。',
        ],
    ];
}

function tpi_get_featured_products(): array
{
    return [
        [
            'title' => '自動車エンジン部品',
            'body' => 'エンジンブロック・シリンダーヘッドの精密切削加工。大手自動車メーカーへの納入実績多数。',
            'tag' => '自動車',
            'anchor' => '/products/#automotive',
            'image' => 'product-auto-01',
        ],
        [
            'title' => '航空宇宙用構造部品',
            'body' => 'チタン・インコネル等の難削材加工。航空機エンジン・機体構造部品の製造実績があります。',
            'tag' => '航空宇宙',
            'anchor' => '/products/#aerospace',
            'image' => 'product-aero-01',
        ],
        [
            'title' => '医療機器用精密部品',
            'body' => '手術器具・インプラント部品の超精密加工。クリーンルーム環境での仕上げにも対応。',
            'tag' => '医療機器',
            'anchor' => '/products/#medical',
            'image' => 'product-med-01',
        ],
    ];
}

function tpi_get_field_cards(): array
{
    return [
        ['icon' => '01', 'title' => '自動車', 'body' => 'Tier1・Tier2サプライヤー向けの量産対応。エンジン・トランスミッション・ブレーキ関連部品を中心に加工しています。'],
        ['icon' => '02', 'title' => '航空宇宙', 'body' => 'チタンやインコネルなどの難削材加工に対応。JIS Q 9100認証のもと、厳格な品質基準で製造しています。'],
        ['icon' => '03', 'title' => '医療機器', 'body' => '超小型精密部品からインプラント部品まで、清潔性と精度が求められる分野の加工を行っています。'],
    ];
}

function tpi_get_automotive_products(): array
{
    return [
        ['name' => 'エンジンブロック部品', 'description' => 'シリンダーブロックの主要加工面を高精度に切削。量産ラインでの安定供給を実現。', 'process' => '5軸マシニング / 研削仕上げ', 'material' => 'ADC12', 'precision' => '±0.005mm', 'image' => 'product-auto-01'],
        ['name' => 'トランスミッション部品', 'description' => 'ギヤハウジング・バルブボディの精密加工。複雑な油路形状にも対応。', 'process' => '横型MC / 放電加工', 'material' => 'FC250', 'precision' => '±0.01mm', 'image' => 'product-auto-02'],
        ['name' => 'ブレーキキャリパー', 'description' => '安全基準を満たす品質管理体制で、制動性能に直結するキャリパーボディを加工。', 'process' => '立型MC / CNC旋盤', 'material' => 'A6061', 'precision' => '±0.008mm', 'image' => 'product-auto-03'],
        ['name' => 'EV関連部品', 'description' => 'モーターハウジング・バッテリーケースなど、EV化に対応した新規加工案件を支援。', 'process' => '5軸マシニング', 'material' => 'A5052', 'precision' => '±0.01mm', 'image' => 'product-auto-04'],
    ];
}

function tpi_get_aerospace_products(): array
{
    return [
        ['name' => 'タービンブレード', 'description' => '耐熱合金の5軸加工で複雑な翼形状を高精度に実現。', 'process' => '5軸MC / 研削', 'material' => 'インコネル718', 'precision' => '±0.003mm', 'image' => 'product-aero-01'],
        ['name' => '構造用ファスナー', 'description' => '航空規格に準拠した品質管理で製造する高強度ファスナー。', 'process' => 'CNC旋盤 / 転造', 'material' => 'Ti-6Al-4V', 'precision' => '±0.005mm', 'image' => 'product-aero-02'],
        ['name' => '油圧バルブ部品', 'description' => 'フライトコントロール用油圧システムの精密バルブ。高い気密性と耐久性を両立。', 'process' => '複合旋盤 / 内面研削', 'material' => 'SUS630', 'precision' => '±0.002mm', 'image' => 'product-aero-03'],
    ];
}

function tpi_get_medical_products(): array
{
    return [
        ['name' => '内視鏡先端部品', 'description' => 'φ2mm以下の微細加工にも対応する超小型精密部品。', 'process' => 'スイス型自動旋盤 / 微細MC', 'material' => 'SUS304', 'precision' => '±0.005mm', 'image' => 'product-med-01'],
        ['name' => '人工関節コンポーネント', 'description' => '生体適合材料の高精度仕上げで、人工関節のベースプレートを製造。', 'process' => '5軸MC / 鏡面研削', 'material' => 'コバルトクロム合金', 'precision' => '±0.003mm', 'image' => 'product-med-02'],
        ['name' => '骨固定プレート', 'description' => 'チタン合金の薄肉加工と複数穴位置の高精度を両立。', 'process' => 'CNC旋盤 / MC', 'material' => 'Ti-6Al-4V ELI', 'precision' => '±0.01mm', 'image' => 'product-med-03'],
    ];
}

function tpi_get_equipment_stats(): array
{
    return [
        ['value' => '50台+', 'label' => '保有設備数'],
        ['value' => '2拠点', 'label' => '工場数'],
        ['value' => '24h', 'label' => '稼働体制'],
        ['value' => '5軸', 'label' => '最大同時加工軸数'],
    ];
}

function tpi_get_equipment_groups(): array
{
    return [
        'マシニングセンタ' => [
            ['name' => '5軸マシニングセンタ', 'maker' => 'DMG MORI DMU 50 3rd', 'spec' => '500×450×400mm / 20,000rpm', 'count' => '2台'],
            ['name' => '横型マシニングセンタ', 'maker' => 'オークマ MA-600HⅡ', 'spec' => '900×800×800mm / パレット2面', 'count' => '3台'],
            ['name' => '立型マシニングセンタ', 'maker' => 'ブラザー SPEEDIO S700X2', 'spec' => '700×400×300mm / 高速タッピング', 'count' => '5台'],
            ['name' => '大型門型マシニングセンタ', 'maker' => '東芝機械 MPF-2140B', 'spec' => '4000×2100×800mm', 'count' => '1台'],
        ],
        'NC旋盤' => [
            ['name' => 'CNC複合旋盤', 'maker' => '森精機 NTX2000', 'spec' => '最大加工径 φ660mm / ミーリング付き', 'count' => '3台'],
            ['name' => 'CNC旋盤', 'maker' => 'オークマ LB3000EX II', 'spec' => '最大加工径 φ400mm / 高精度仕上げ', 'count' => '6台'],
            ['name' => 'スイス型自動旋盤', 'maker' => 'シチズン Cincom L32', 'spec' => '最大加工径 φ32mm / 小径精密部品対応', 'count' => '4台'],
        ],
        '研削盤' => [
            ['name' => 'CNC円筒研削盤', 'maker' => 'ジェイテクト TOYODA GL4Pii-50', 'spec' => '最大加工径 φ320mm', 'count' => '2台'],
            ['name' => 'CNC平面研削盤', 'maker' => '岡本工作 PSG-126DX', 'spec' => 'テーブル 600×300mm', 'count' => '3台'],
            ['name' => '内面研削盤', 'maker' => 'ジェイテクト TOYODA GI4Ⅱ', 'spec' => '最大加工径 φ200mm', 'count' => '1台'],
        ],
        '放電加工機' => [
            ['name' => 'ワイヤー放電加工機', 'maker' => '三菱電機 MV1200R', 'spec' => '最大加工ワーク 810×640mm', 'count' => '2台'],
            ['name' => '形彫り放電加工機', 'maker' => '牧野フライス EDNC64', 'spec' => '650×450×350mm', 'count' => '2台'],
        ],
    ];
}

function tpi_get_inspection_devices(): array
{
    return [
        ['title' => '三次元測定機', 'body' => 'ミツトヨ CRYSTA-Apex V 9106。複雑形状の三次元寸法を高精度に測定。'],
        ['title' => '画像測定機', 'body' => 'キーエンス IM-8000。最大300箇所を数秒で一括測定。'],
        ['title' => '表面粗さ測定機', 'body' => 'ミツトヨ SV-C3200。Ra 0.01μm まで測定可能。'],
        ['title' => '輪郭形状測定機', 'body' => '東京精密 CONTOURECORD 2700。複雑な曲面形状の評価に対応。'],
        ['title' => 'マイクロスコープ', 'body' => 'キーエンス VHX-7000。微細加工面の目視検査に使用。'],
        ['title' => '硬度計', 'body' => 'ミツトヨ HM-200。熱処理後の硬度保証に使用。'],
    ];
}

function tpi_get_factory_sites(): array
{
    return [
        ['title' => '第一工場（本社）', 'body' => '延床面積 1,200㎡。マシニングセンタ / NC旋盤 / 研削盤を中心に、品質管理室・三次元測定室を併設。', 'image' => 'factory-1'],
        ['title' => '第二工場', 'body' => '延床面積 900㎡。大型門型MC / 放電加工 / 表面処理連携を集約し、試作と大型案件を支援。', 'image' => 'factory-2'],
    ];
}

function tpi_get_philosophy_values(): array
{
    return [
        ['number' => '01', 'title' => 'Precision', 'body' => 'ミクロンレベルの精度を追求し、妥協のないものづくりを続けます。'],
        ['number' => '02', 'title' => 'Reliability', 'body' => '品質・納期・コストのすべてで、お客様の期待を超える信頼性を実現します。'],
        ['number' => '03', 'title' => 'Innovation', 'body' => '最新技術への投資と人材育成を通じ、常に加工技術の限界に挑戦します。'],
    ];
}

function tpi_get_history(): array
{
    return [
        ['year' => '1974', 'body' => '東京都大田区にて創業。旋盤加工を中心とした町工場としてスタート。'],
        ['year' => '1980', 'body' => 'NC旋盤を導入し、数値制御加工への転換を開始。'],
        ['year' => '1985', 'body' => 'マシニングセンタを導入し、複合加工体制を確立。'],
        ['year' => '1990', 'body' => '自動車部品の量産受注を開始し、生産体制を拡充。'],
        ['year' => '1995', 'body' => 'ISO 9001認証を取得し、品質管理体制を強化。'],
        ['year' => '2008', 'body' => 'JIS Q 9100認証を取得し、航空宇宙分野の品質保証体制を確立。'],
        ['year' => '2012', 'body' => '5軸マシニングセンタを導入し、超精密加工に対応。'],
        ['year' => '2018', 'body' => '第二工場を新設し、放電加工と表面処理連携を拡充。'],
        ['year' => '2025', 'body' => '医療機器向けの超精密加工部門を強化し、クリーン環境での仕上げ工程を刷新。'],
    ];
}

function tpi_get_certifications(): array
{
    return [
        ['title' => 'ISO 9001:2015', 'body' => '品質マネジメントシステムを全社で運用し、工程改善を継続しています。', 'year' => '1995年取得'],
        ['title' => 'ISO 14001:2015', 'body' => '環境負荷低減と省エネルギー活動を製造現場に組み込んでいます。', 'year' => '2018年取得'],
        ['title' => 'JIS Q 9100', 'body' => '航空宇宙分野向けの厳格なトレーサビリティと品質保証体制を構築。', 'year' => '2008年取得'],
    ];
}

function tpi_get_quality_goals(): array
{
    return [
        ['number' => '01', 'title' => '不良率 0.01%以下', 'body' => '統計的工程管理を活用し、工程内不良の発生を最小限に抑制します。'],
        ['number' => '02', 'title' => '納期遵守率 99%以上', 'body' => '生産計画と進捗管理を徹底し、お客様の要求納期を厳守します。'],
        ['number' => '03', 'title' => '顧客クレーム ゼロ', 'body' => '出荷前の全数検査と層別管理により、不良品の流出を防止します。'],
    ];
}

function tpi_get_quality_flow(): array
{
    return [
        ['step' => '01', 'title' => '受注・図面確認', 'body' => '図面レビュー / 製造性評価 / 検査基準書の作成'],
        ['step' => '02', 'title' => '材料受入検査', 'body' => 'ミルシート照合 / 材質分析 / 寸法検査'],
        ['step' => '03', 'title' => '加工（前工程）', 'body' => '初品検査 / 工程内寸法チェック / SPC管理'],
        ['step' => '04', 'title' => '加工（後工程）', 'body' => '工程間検査 / 面粗度測定 / バリ取り確認'],
        ['step' => '05', 'title' => '最終検査', 'body' => '三次元測定 / 外観検査 / 検査成績書作成'],
        ['step' => '06', 'title' => '梱包・出荷', 'body' => '防錆処理 / 梱包仕様確認 / 出荷前最終チェック'],
    ];
}

function tpi_get_traceability_items(): array
{
    return [
        ['title' => '材料管理', 'body' => 'ミルシート・材料ロット番号を記録し、入荷から消費まで追跡可能。'],
        ['title' => '加工記録', 'body' => '加工プログラム番号・使用設備・作業者・加工条件を全品記録。'],
        ['title' => '検査記録', 'body' => '測定データ・検査成績書を電子保管し、過去10年分を即時検索。'],
        ['title' => '出荷管理', 'body' => '出荷先・出荷日・梱包仕様・輸送手段を記録し、製品追跡に対応。'],
    ];
}

function tpi_get_jobs(): array
{
    return [
        [
            'title' => 'NC旋盤オペレーター',
            'type' => '正社員',
            'salary' => '月給 25万〜40万円',
            'detail' => [
                '勤務地' => '本社工場（東京都大田区）',
                '勤務時間' => '8:30〜17:30（休憩60分）',
                '休日' => '完全週休2日制（土日）/ 祝日 / 年間休日120日',
                '応募資格' => 'NC旋盤の操作経験2年以上（汎用旋盤経験者も歓迎）',
            ],
            'tasks' => ['NC旋盤を使用した精密部品の加工', '加工プログラムの作成・修正', '段取り替え・工具管理', '工程内検査'],
        ],
        [
            'title' => 'マシニングセンタオペレーター',
            'type' => '正社員',
            'salary' => '月給 25万〜42万円',
            'detail' => [
                '勤務地' => '本社工場（東京都大田区）',
                '勤務時間' => '8:30〜17:30（休憩60分）',
                '休日' => '完全週休2日制（土日）/ 祝日 / 年間休日120日',
                '応募資格' => 'マシニングセンタの操作経験2年以上（5軸経験者優遇）',
            ],
            'tasks' => ['マシニングセンタによる精密部品の加工', 'CAMプログラムの作成・最適化', '治具設計・段取り改善', '新規加工品の立ち上げ'],
        ],
        [
            'title' => '品質管理スタッフ',
            'type' => '正社員',
            'salary' => '月給 23万〜38万円',
            'detail' => [
                '勤務地' => '本社工場（東京都大田区）',
                '勤務時間' => '8:30〜17:30（休憩60分）',
                '休日' => '完全週休2日制（土日）/ 祝日 / 年間休日120日',
                '応募資格' => '品質管理の実務経験1年以上 / 三次元測定機の操作経験歓迎',
            ],
            'tasks' => ['加工品の寸法検査', '検査成績書の作成', '工程内品質監視', '不具合発生時の原因調査・是正措置'],
        ],
    ];
}

function tpi_get_recruit_stats(): array
{
    return [
        ['value' => '85名', 'label' => '社員数'],
        ['value' => '38.5歳', 'label' => '平均年齢'],
        ['value' => '12.8年', 'label' => '平均勤続年数'],
        ['value' => '95%', 'label' => '定着率（3年）'],
        ['value' => '15日', 'label' => '平均有給取得日数'],
        ['value' => '20名', 'label' => '20代社員数'],
        ['value' => '8名', 'label' => '女性社員数'],
        ['value' => '3名', 'label' => '昨年度中途入社'],
    ];
}

function tpi_get_employee_voices(): array
{
    return [
        ['name' => '中村 健太', 'role' => 'マシニングセンタオペレーター', 'joined' => '2018年入社', 'body' => '難しい加工条件をチームで詰めて、図面通りの精度が出た瞬間が一番やりがいを感じます。', 'image' => 'staff-01'],
        ['name' => '杉本 里奈', 'role' => '品質管理スタッフ', 'joined' => '2020年入社', 'body' => '測定と改善提案の両方に関われるので、品質保証の手応えを感じながら働けています。', 'image' => 'staff-02'],
        ['name' => '山口 直人', 'role' => 'NC旋盤オペレーター', 'joined' => '2016年入社', 'body' => '先輩に相談しやすく、新しい設備の立ち上げにも参加できる環境です。', 'image' => 'staff-03'],
    ];
}

function tpi_get_benefits(): array
{
    return [
        ['icon' => '01', 'title' => '昇給・賞与', 'body' => '年1回昇給 / 年2回賞与（計4ヶ月分実績）'],
        ['icon' => '02', 'title' => '社会保険完備', 'body' => '健康保険・厚生年金・雇用保険・労災保険を完備。'],
        ['icon' => '03', 'title' => '休日・休暇', 'body' => '完全週休2日 / 年間休日120日 / 有給休暇 / 慶弔休暇。'],
        ['icon' => '04', 'title' => '資格支援', 'body' => '技能検定の受験費用負担 / 合格時報奨金制度。'],
        ['icon' => '05', 'title' => '通勤・住宅', 'body' => '交通費全額支給 / 住宅手当 / 引越し費用補助。'],
        ['icon' => '06', 'title' => '研修制度', 'body' => '入社時研修 / OJT / 外部セミナー参加支援。'],
    ];
}

function tpi_get_recruit_flow(): array
{
    return [
        ['step' => '01', 'title' => '応募', 'body' => 'フォームまたはお電話にてご応募ください。'],
        ['step' => '02', 'title' => '書類選考', 'body' => '履歴書・職務経歴書をもとに1週間以内に結果をご連絡します。'],
        ['step' => '03', 'title' => '一次面接', 'body' => '人事担当との面接を実施します。オンラインにも対応可能です。'],
        ['step' => '04', 'title' => '工場見学・最終面接', 'body' => '実際の職場をご覧いただいた上で、代表と最終面接を行います。'],
        ['step' => '05', 'title' => '内定', 'body' => '最終面接後1週間以内に結果をご連絡し、入社日を調整します。'],
    ];
}

function tpi_get_prototyping_features(): array
{
    return [
        ['title' => '試作1個から対応', 'body' => '最小ロット1個から受注可能。試作段階から量産までシームレスに移行できます。'],
        ['title' => '3D CAD/CAM対応', 'body' => 'CATIA / NX / Mastercam に対応し、3Dデータから加工プログラムを作成します。'],
        ['title' => 'DFMレビュー', 'body' => '加工性と量産性を踏まえた設計フィードバックで、立ち上がり前の課題を整理します。'],
        ['title' => '短納期対応', 'body' => '特急品は最短3営業日で対応。優先ラインを確保してスピーディーに製作します。'],
    ];
}

function tpi_get_processing_technologies(): array
{
    return [
        ['title' => '5軸同時加工', 'body' => '複雑な3D形状を1チャッキングで高精度に加工し、セットアップ工数を削減します。'],
        ['title' => '難削材加工', 'body' => 'チタン・インコネル・ハステロイなどの難削材に、専用工具と最適条件で対応します。'],
        ['title' => '微細加工', 'body' => 'φ0.1mmの微細穴加工やRa0.2μmクラスの仕上げなど、超精密領域に対応します。'],
        ['title' => 'ワイヤー放電加工', 'body' => '導電性材料の高精度形状加工に対応し、薄肉や異形状でも安定した品質を実現します。'],
        ['title' => '研削仕上げ', 'body' => '円筒・平面・内面研削を組み合わせ、高い寸法精度と面粗度を両立します。'],
        ['title' => '表面処理', 'body' => '硬質アルマイト・無電解ニッケル・黒染めなど、外部連携を含めて一貫対応します。'],
    ];
}

function tpi_get_contact_methods(): array
{
    $profile = tpi_get_company_profile();

    return [
        ['title' => 'お電話', 'body' => $profile['phone'] . "\n受付: " . $profile['hours']],
        ['title' => 'メール・フォーム', 'body' => '下記フォームより24時間受付。内容確認後、1営業日以内を目安にご返信します。'],
        ['title' => '図面送付', 'body' => '図面や3Dデータを添えていただければ、加工可否や概算見積もりのご相談が可能です。'],
    ];
}

function tpi_get_contact_type_options(): array
{
    return ['お見積り', '技術相談', '工場見学', '採用について', 'その他'];
}

function tpi_get_processing_type_options(): array
{
    return ['選択してください', '切削加工', '研削加工', '放電加工', '試作・開発', 'その他'];
}

function tpi_get_map_embed_url(): string
{
    $profile = tpi_get_company_profile();
    return 'https://www.google.com/maps?q=' . rawurlencode($profile['map_query']) . '&z=16&output=embed';
}

function tpi_get_phone_href(): string
{
    $profile = tpi_get_company_profile();
    $digits = preg_replace('/[^0-9+]/', '', $profile['phone']);
    return $digits ? 'tel:' . $digits : '';
}

function tpi_render_breadcrumbs(string $current): void
{
    ?>
    <nav class="breadcrumb" aria-label="Breadcrumb">
        <a href="<?php echo esc_url(home_url('/')); ?>">TOP</a>
        <span aria-hidden="true">&gt;</span>
        <span aria-current="page"><?php echo esc_html($current); ?></span>
    </nav>
    <?php
}

function tpi_render_page_hero(
    string $eyebrow,
    string $title,
    string $subtitle = '',
    bool $compact = false,
    string $image_key = '',
    bool $show_breadcrumbs = true
): void {
    $class = $compact ? 'page-hero compact' : 'page-hero';
    $image_url = $image_key !== '' ? tpi_get_image_url($image_key) : '';
    $style = '';

    if ($image_url !== '') {
        $class .= ' has-image';
        $style = '--hero-image: url("' . esc_url_raw($image_url) . '");';
    }
    ?>
    <section class="<?php echo esc_attr($class); ?>"<?php echo $style !== '' ? ' style="' . esc_attr($style) . '"' : ''; ?>>
        <div class="section-inner">
            <?php if ($show_breadcrumbs) : ?>
                <?php tpi_render_breadcrumbs($title); ?>
            <?php endif; ?>
            <p class="section-eyebrow"><?php echo esc_html($eyebrow); ?></p>
            <h1 class="page-title"><?php echo esc_html($title); ?></h1>
            <?php if ($subtitle !== '') : ?>
                <p class="page-subtitle"><?php echo esc_html($subtitle); ?></p>
            <?php endif; ?>
        </div>
    </section>
    <?php
}

function tpi_render_placeholder(string $label, string $tone = ''): void
{
    $classes = 'media-placeholder';
    if ($tone !== '') {
        $classes .= ' ' . $tone;
    }
    echo '<div class="' . esc_attr($classes) . '"><span>' . esc_html($label) . '</span></div>';
}

function tpi_render_media(string $key, string $label, string $tone = '', string $class = ''): void
{
    $image_url = tpi_get_image_url($key);
    $classes = trim('media-frame ' . $tone . ' ' . $class);

    if ($image_url !== '') {
        echo '<figure class="' . esc_attr($classes) . '">';
        echo '<img src="' . esc_url($image_url) . '" alt="' . esc_attr($label) . '" loading="lazy" decoding="async">';
        echo '</figure>';
        return;
    }

    $fallback_classes = trim('media-placeholder ' . $tone . ' ' . $class);
    echo '<div class="' . esc_attr($fallback_classes) . '"><span>' . esc_html($label) . '</span></div>';
}

function tpi_render_common_cta(string $title = '精密加工のご相談はお気軽にお問い合わせください', string $body = '試作1個から量産まで。まずは図面や仕様をお送りください。'): void
{
    ?>
    <section class="cta-band">
        <div class="section-inner cta-band-inner">
            <div>
                <p class="section-eyebrow">Contact</p>
                <h2 class="section-title"><?php echo esc_html($title); ?></h2>
                <p class="section-desc slim"><?php echo esc_html($body); ?></p>
            </div>
            <div class="cta-band-actions cta-band-actions-equal">
                <a class="button button-primary" href="<?php echo esc_url(home_url('/contact/')); ?>">お問い合わせ</a>
                <a class="button button-secondary" href="<?php echo esc_url(home_url('/recruit/')); ?>">採用情報</a>
            </div>
        </div>
    </section>
    <?php
}

function tpi_render_contact_form(): void
{
    ?>
    <form class="contact-form" action="<?php echo esc_url(home_url('/thanks/')); ?>" method="post" enctype="multipart/form-data">
        <fieldset class="form-fieldset">
            <legend>お問い合わせ種別</legend>
            <div class="radio-group">
                <?php foreach (tpi_get_contact_type_options() as $index => $option) : ?>
                    <label class="radio-option">
                        <input type="radio" name="type" value="<?php echo esc_attr($option); ?>" <?php checked($index, 0); ?> required>
                        <span><?php echo esc_html($option); ?></span>
                    </label>
                <?php endforeach; ?>
            </div>
        </fieldset>
        <div class="form-grid">
            <label>
                <span>会社名</span>
                <input type="text" name="company" placeholder="例）株式会社サンプル" required>
            </label>
            <label>
                <span>部署名</span>
                <input type="text" name="department" placeholder="例）購買部">
            </label>
            <label>
                <span>お名前</span>
                <input type="text" name="name" placeholder="例）山田 太郎" required>
            </label>
            <label>
                <span>メールアドレス</span>
                <input type="email" name="email" placeholder="info@example.com" required>
            </label>
            <label>
                <span>電話番号</span>
                <input type="tel" name="tel" placeholder="例）03-1234-5678">
            </label>
            <label>
                <span>ご希望の加工内容</span>
                <select name="process">
                    <?php foreach (tpi_get_processing_type_options() as $option) : ?>
                        <option value="<?php echo esc_attr($option); ?>"><?php echo esc_html($option); ?></option>
                    <?php endforeach; ?>
                </select>
            </label>
            <label>
                <span>材質</span>
                <input type="text" name="material" placeholder="例）SUS304 / A6061 / チタン">
            </label>
            <label>
                <span>数量</span>
                <input type="text" name="quantity" placeholder="例）試作3個、月産100個">
            </label>
            <label class="full file-drop">
                <span>図面添付</span>
                <input type="file" name="drawing" accept=".pdf,.dxf,.step,.stp,.iges,.igs">
                <small>PDF / DXF / STEP / IGES を想定したデモUIです。</small>
            </label>
            <label class="full">
                <span>お問い合わせ内容</span>
                <textarea name="message" rows="6" placeholder="図面番号、材質、ロット、納期などをご記入ください。" required></textarea>
            </label>
            <label class="full consent-check">
                <input type="checkbox" name="privacy" required>
                <span><a href="<?php echo esc_url(home_url('/privacy/')); ?>">個人情報の取り扱い</a>について同意する</span>
            </label>
        </div>
        <button class="button button-primary" type="submit">送信する</button>
    </form>
    <?php
}

function tpi_filter_document_title_parts(array $parts): array
{
    $profile = tpi_get_company_profile();
    $parts['site'] = $profile['site_short_name'];

    if (is_front_page()) {
        $parts['title'] = $profile['tagline'];
    }

    return $parts;
}
add_filter('document_title_parts', 'tpi_filter_document_title_parts');
