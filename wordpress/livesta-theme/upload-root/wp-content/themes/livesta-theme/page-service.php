<?php
get_header();
$company = livesta_get_company_profile();
?>
<main id="content">
    <?php livesta_render_page_hero([
        'eyebrow' => 'Service',
        'title' => '事業内容',
        'subtitle' => '不動産に関する各種ご相談に対応しています',
        'class' => 'service-hero',
    ]); ?>

    <?php livesta_render_breadcrumb(); ?>

    <section class="content-section text-center">
        <div class="section-inner">
            <p class="section-eyebrow">Our Services</p>
            <h2 class="section-heading">4つの事業領域</h2>
            <p class="sub-desc">LIVESTAは不動産の売買仲介を中心に、賃貸管理・開発・コンサルティングまで幅広く対応しています。各分野の情報をわかりやすくご案内します。</p>
            <div class="service-overview-grid">
                <a href="#brokerage" class="service-overview-card">
                    <div class="service-icon">🏠</div>
                    <p class="card-num">01</p>
                    <h3>売買仲介</h3>
                    <p>マンション・戸建・土地の売買をサポート</p>
                </a>
                <a href="#management" class="service-overview-card">
                    <div class="service-icon">🔑</div>
                    <p class="card-num">02</p>
                    <h3>賃貸管理</h3>
                    <p>オーナー様の資産価値を守る総合管理</p>
                </a>
                <a href="#development" class="service-overview-card">
                    <div class="service-icon">🏗</div>
                    <p class="card-num">03</p>
                    <h3>不動産開発</h3>
                    <p>街の魅力を引き出すプロジェクト</p>
                </a>
                <a href="#consulting" class="service-overview-card">
                    <div class="service-icon">💼</div>
                    <p class="card-num">04</p>
                    <h3>コンサルティング</h3>
                    <p>不動産活用に関するご相談に対応</p>
                </a>
            </div>
        </div>
    </section>

    <section class="content-section cream" id="brokerage">
        <div class="section-inner">
            <div class="service-detail">
                <div class="service-body">
                    <p class="section-eyebrow">Brokerage</p>
                    <h2>売買仲介</h2>
                    <p>マンション・戸建住宅・土地の売買を、経験豊富なスタッフがトータルサポート。住まい探しから資産としての売却まで、状況に応じてご案内します。</p>
                    <ul class="service-feature-list">
                        <li><strong>■ 豊富なネットワーク</strong><p>不動産ポータル等の公開情報に加え、既存顧客や協力会社とのネットワークも活用しながらご紹介します。</p></li>
                        <li><strong>■ 適正価格の査定</strong><p>周辺相場・取引事例・市場動向を総合的に分析し、根拠のある適正価格をご提示します。</p></li>
                        <li><strong>■ 契約後のフォロー</strong><p>契約手続きや引き渡しに関する確認事項を整理し、住み替えや資金計画も含めて進行をサポートします。</p></li>
                    </ul>
                </div>
                <div class="service-photo">
                    <img src="<?php echo esc_url(livesta_get_theme_image_url('service-brokerage.png')); ?>" alt="売買仲介サービスのイメージ" loading="lazy">
                </div>
            </div>
        </div>
    </section>

    <section class="content-section" id="management">
        <div class="section-inner">
            <div class="service-detail reverse">
                <div class="service-body">
                    <p class="section-eyebrow">Management</p>
                    <h2>賃貸管理</h2>
                    <p>入居者募集から建物管理・退去対応まで、オーナー様の負担を抑えながら賃貸運営に関する業務をサポートします。</p>
                    <ul class="service-feature-list">
                        <li><strong>■ 入居者募集・審査</strong><p>主要ポータルサイトへの掲載から内見対応、入居審査まで、必要な業務をサポートします。</p></li>
                        <li><strong>■ 建物メンテナンス</strong><p>日常清掃・設備点検・修繕計画の策定と実施。長期的な資産価値の維持に取り組みます。</p></li>
                        <li><strong>■ 月次レポート</strong><p>稼働状況・収支報告・修繕履歴を整理して共有します。</p></li>
                    </ul>
                    <div class="stat-inline">
                        <div class="stat-box"><p class="num"><?php echo esc_html($company['managed_units']); ?></p><p class="label">管理・募集サポート戸数</p></div>
                        <div class="stat-box"><p class="num"><?php echo esc_html($company['occupancy_rate']); ?></p><p class="label">平均稼働率</p></div>
                        <div class="stat-box"><p class="num">15年+</p><p class="label">平均管理年数</p></div>
                    </div>
                </div>
                <div class="service-photo">
                    <img src="<?php echo esc_url(livesta_get_theme_image_url('service-management.png')); ?>" alt="賃貸管理サービスのイメージ" loading="lazy">
                </div>
            </div>
        </div>
    </section>

    <section class="content-section cream" id="development">
        <div class="section-inner">
            <div class="service-detail">
                <div class="service-body">
                    <p class="section-eyebrow">Development</p>
                    <h2>不動産開発</h2>
                    <p>土地の仕入れから企画・設計・施工管理まで、一貫した開発体制で街の魅力を引き出すプロジェクトを手がけています。</p>
                    <ul class="service-feature-list">
                        <li><strong>■ 用地選定・仕入れ</strong><p>立地条件・法規制・需要動向を確認し、条件に沿った用地を検討します。</p></li>
                        <li><strong>■ 企画・設計</strong><p>ターゲットのライフスタイルを起点に、デザイン性と居住性を両立したプランニングを行います。</p></li>
                        <li><strong>■ 施工管理・品質管理</strong><p>ゼネコン・工務店と連携し、工期・品質・コストの管理を行います。</p></li>
                    </ul>
                    <div class="stat-inline">
                        <div class="stat-box"><p class="num"><?php echo esc_html($company['development_count']); ?></p><p class="label">累計開発棟数</p></div>
                        <div class="stat-box"><p class="num">850戸+</p><p class="label">累計分譲戸数</p></div>
                        <div class="stat-box"><p class="num">12エリア</p><p class="label">開発対象エリア</p></div>
                    </div>
                </div>
                <div class="service-photo">
                    <img src="<?php echo esc_url(livesta_get_theme_image_url('service-development.png')); ?>" alt="不動産開発サービスのイメージ" loading="lazy">
                </div>
            </div>
        </div>
    </section>

    <section class="content-section" id="consulting">
        <div class="section-inner">
            <div class="service-detail reverse">
                <div class="service-body">
                    <p class="section-eyebrow">Consulting</p>
                    <h2>不動産コンサルティング</h2>
                    <p>相続や活用方針の整理など、不動産に関わる複雑な課題を、専門家連携も含めてサポートします。</p>
                    <ul class="service-feature-list">
                        <li><strong>■ 活用方針サポート</strong><p>遊休不動産の活用や建て替え・リノベーションについて、方向性の整理をサポートします。</p></li>
                        <li><strong>■ 投資アドバイザリー</strong><p>市場情報の確認や検討項目の整理を通じて、投資判断に必要な材料の準備を支援します。</p></li>
                        <li><strong>■ 相続・事業承継支援</strong><p>不動産の評価や分割方法の検討について、必要に応じて専門家と連携しながら対応します。</p></li>
                    </ul>
                </div>
                <div class="service-photo">
                    <img src="<?php echo esc_url(livesta_get_theme_image_url('service-consulting.png')); ?>" alt="不動産コンサルティングのイメージ" loading="lazy">
                </div>
            </div>
        </div>
    </section>

    <section class="content-section dark">
        <div class="section-inner">
            <div class="dark-stats">
                <div class="dark-stat"><p class="num"><?php echo esc_html($company['managed_units']); ?></p><p class="label">管理・募集サポート戸数</p></div>
                <div class="dark-stat"><p class="num"><?php echo esc_html($company['occupancy_rate']); ?></p><p class="label">平均稼働率</p></div>
                <div class="dark-stat"><p class="num"><?php echo esc_html($company['development_count']); ?></p><p class="label">累計開発棟数</p></div>
                <div class="dark-stat"><p class="num">2営業日</p><p class="label">お問い合わせ返信目安</p></div>
            </div>
        </div>
    </section>

    <?php livesta_render_common_cta([
        'title' => '物件探しから売却査定まで、お気軽にご相談ください',
        'description' => 'ご相談内容を整理したうえで、担当領域に応じた窓口へスムーズにつなぎます。',
        'actions' => [
            [
                'label' => '売却査定を相談',
                'url' => add_query_arg('inquiry_type', '売却査定', home_url('/contact/')),
                'variant' => 'primary',
            ],
            [
                'label' => '賃貸管理を相談',
                'url' => add_query_arg('inquiry_type', '賃貸管理', home_url('/contact/')),
                'variant' => 'secondary',
            ],
            [
                'label' => '来店予約',
                'url' => add_query_arg('inquiry_type', '来店予約', home_url('/contact/')),
                'variant' => 'secondary',
            ],
        ],
    ]); ?>
</main>
<?php
get_footer();
