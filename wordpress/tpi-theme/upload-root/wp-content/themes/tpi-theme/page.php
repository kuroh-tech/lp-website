<?php
get_header();

$slug = get_post_field('post_name', get_the_ID());
$profile = tpi_get_company_profile();
?>
<main>
    <?php if ($slug === 'company') : ?>
        <?php tpi_render_page_hero('Company', '会社概要', '精度の追求を支える、確かな組織と人', false, 'company-hero'); ?>
        <section>
            <div class="section-inner two-column align-start">
                <div class="reveal">
                    <?php tpi_render_media('ceo-portrait', '代表取締役 鈴木 正義', 'warm', 'ratio-3-4'); ?>
                    <div class="caption-stack">
                        <strong>代表取締役</strong>
                        <span>鈴木 正義</span>
                        <small><?php echo esc_html($profile['message_sign']); ?></small>
                    </div>
                </div>
                <div class="reveal prose-block">
                    <p class="section-eyebrow">Message</p>
                    <h2 class="section-title">ご挨拶</h2>
                    <p>東京精密工業は、1974年の創業以来、金属精密加工の分野で半世紀にわたり技術を磨き続けてまいりました。</p>
                    <p>当社が最も大切にしているのは「精度」です。最新の設備と、長年の経験を持つ技術者の手技。その両方があって初めて、お客様の求める品質を安定して提供することができます。</p>
                    <p>自動車・航空宇宙・医療機器と、当社が手がける分野は多岐にわたりますが、共通しているのは「精度が人命や安全に直結する」ということです。その責任を胸に刻みながら、これからも技術の革新に挑戦し続けてまいります。</p>
                </div>
            </div>
        </section>
        <section class="section-dark">
            <div class="section-inner">
                <div class="section-header centered reveal">
                    <p class="section-eyebrow">Philosophy</p>
                    <h2 class="section-title">企業理念</h2>
                    <p class="section-lead">精度が、信頼をつくる。</p>
                    <p class="section-desc slim light">私たちは、ものづくりの精度を通じて、社会の安全と発展に貢献します。</p>
                </div>
                <div class="card-grid card-grid-3">
                    <?php foreach (tpi_get_philosophy_values() as $item) : ?>
                        <article class="dark-card reveal">
                            <span class="feature-card-number"><?php echo esc_html($item['number']); ?></span>
                            <h3><?php echo esc_html($item['title']); ?></h3>
                            <p><?php echo esc_html($item['body']); ?></p>
                        </article>
                    <?php endforeach; ?>
                </div>
            </div>
        </section>
        <section class="section-light">
            <div class="section-inner">
                <div class="section-header reveal">
                    <p class="section-eyebrow">Overview</p>
                    <h2 class="section-title">会社概要</h2>
                </div>
                <table class="data-table data-table-wide reveal">
                    <tbody>
                    <?php foreach (tpi_get_company_overview_rows() as $row) : ?>
                        <tr>
                            <th><?php echo esc_html($row['label']); ?></th>
                            <td><?php echo esc_html($row['value']); ?></td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </section>
        <section>
            <div class="section-inner two-column align-start">
                <div class="reveal">
                    <p class="section-eyebrow">History</p>
                    <h2 class="section-title">沿革</h2>
                    <div class="timeline">
                        <?php foreach (tpi_get_history() as $item) : ?>
                            <article class="timeline-item">
                                <strong><?php echo esc_html($item['year']); ?></strong>
                                <p><?php echo esc_html($item['body']); ?></p>
                            </article>
                        <?php endforeach; ?>
                    </div>
                </div>
                <div class="reveal">
                    <p class="section-eyebrow">Certification</p>
                    <h2 class="section-title">認証・品質規格</h2>
                    <div class="stack-grid">
                        <?php foreach (tpi_get_certifications() as $item) : ?>
                            <article class="info-card certification-card">
                                <h3><?php echo esc_html($item['title']); ?></h3>
                                <p><?php echo esc_html($item['body']); ?></p>
                                <p class="meta certification-year"><?php echo esc_html($item['year']); ?></p>
                            </article>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        </section>
        <section class="section-light">
            <div class="section-inner two-column align-start">
                <div class="reveal">
                    <p class="section-eyebrow">Access</p>
                    <h2 class="section-title">アクセス</h2>
                    <p class="section-desc slim">京急線・JR線からアクセスしやすい大田区の本社工場を拠点に、都内近郊の協力会社と連携しながら量産まで対応しています。</p>
                    <ul class="detail-list">
                        <li><span>所在地</span><?php echo esc_html($profile['address']); ?></li>
                        <li><span>第二工場</span><?php echo esc_html($profile['factory2']); ?></li>
                        <li><span>TEL</span><a href="<?php echo esc_url(tpi_get_phone_href()); ?>"><?php echo esc_html($profile['phone']); ?></a></li>
                        <li><span>FAX</span><?php echo esc_html($profile['fax']); ?></li>
                        <li><span>受付時間</span><?php echo esc_html($profile['hours']); ?></li>
                    </ul>
                </div>
                <div class="reveal map-frame">
                    <iframe src="<?php echo esc_url(tpi_get_map_embed_url()); ?>" loading="lazy" referrerpolicy="no-referrer-when-downgrade" title="東京精密工業へのアクセスマップ"></iframe>
                </div>
            </div>
        </section>
        <?php tpi_render_common_cta(); ?>

    <?php elseif ($slug === 'products') : ?>
        <?php tpi_render_page_hero('Products', '製品紹介', '精密加工で応える、多様な産業のニーズ', false, 'products-hero'); ?>
        <section>
            <div class="section-inner">
                <div class="section-header centered reveal">
                    <p class="section-eyebrow">Fields</p>
                    <h2 class="section-title">対応分野</h2>
                    <p class="section-desc">自動車・航空宇宙・医療機器を中心に、精度と品質が求められる幅広い分野の精密部品を手がけています。</p>
                </div>
                <div class="card-grid card-grid-3">
                    <?php foreach (tpi_get_field_cards() as $item) : ?>
                        <article class="info-card centered reveal field-card">
                            <span class="field-icon"><?php echo esc_html($item['icon']); ?></span>
                            <h3><?php echo esc_html($item['title']); ?></h3>
                            <p><?php echo esc_html($item['body']); ?></p>
                        </article>
                    <?php endforeach; ?>
                </div>
            </div>
        </section>
        <section class="section-light" id="automotive">
            <div class="section-inner">
                <div class="section-header reveal">
                    <p class="section-eyebrow">Automotive</p>
                    <h2 class="section-title">自動車部品</h2>
                    <p class="section-desc">Tier1・Tier2サプライヤーとして、量産からプロトタイプまで柔軟な生産体制で対応します。</p>
                </div>
                <div class="card-grid card-grid-2">
                    <?php foreach (tpi_get_automotive_products() as $item) : ?>
                        <article class="panel-card reveal">
                            <?php tpi_render_media($item['image'], $item['name'], 'silver', 'ratio-16-10'); ?>
                            <div class="panel-card-body">
                                <h3><?php echo esc_html($item['name']); ?></h3>
                                <p><?php echo esc_html($item['description']); ?></p>
                                <dl class="spec-grid">
                                    <div><dt>加工内容</dt><dd><?php echo esc_html($item['process']); ?></dd></div>
                                    <div><dt>材質</dt><dd><?php echo esc_html($item['material']); ?></dd></div>
                                    <div><dt>精度</dt><dd><?php echo esc_html($item['precision']); ?></dd></div>
                                </dl>
                            </div>
                        </article>
                    <?php endforeach; ?>
                </div>
            </div>
        </section>
        <section id="aerospace">
            <div class="section-inner">
                <div class="section-header reveal">
                    <p class="section-eyebrow">Aerospace</p>
                    <h2 class="section-title">航空宇宙部品</h2>
                    <p class="section-desc">JIS Q 9100認証のもと、難削材加工と厳格な品質保証体制で航空機分野を支えています。</p>
                </div>
                <div class="card-grid card-grid-3">
                    <?php foreach (tpi_get_aerospace_products() as $item) : ?>
                        <article class="panel-card reveal">
                            <?php tpi_render_media($item['image'], $item['name'], 'dark', 'ratio-16-10'); ?>
                            <div class="panel-card-body">
                                <h3><?php echo esc_html($item['name']); ?></h3>
                                <p><?php echo esc_html($item['description']); ?></p>
                                <dl class="spec-grid">
                                    <div><dt>加工内容</dt><dd><?php echo esc_html($item['process']); ?></dd></div>
                                    <div><dt>材質</dt><dd><?php echo esc_html($item['material']); ?></dd></div>
                                    <div><dt>精度</dt><dd><?php echo esc_html($item['precision']); ?></dd></div>
                                </dl>
                            </div>
                        </article>
                    <?php endforeach; ?>
                </div>
            </div>
        </section>
        <section class="section-light" id="medical">
            <div class="section-inner">
                <div class="section-header reveal">
                    <p class="section-eyebrow">Medical</p>
                    <h2 class="section-title">医療機器部品</h2>
                    <p class="section-desc">超小型精密部品からインプラント関連まで、清潔性と寸法精度を両立した加工を行っています。</p>
                </div>
                <div class="card-grid card-grid-3">
                    <?php foreach (tpi_get_medical_products() as $item) : ?>
                        <article class="panel-card reveal">
                            <?php tpi_render_media($item['image'], $item['name'], 'silver', 'ratio-16-10'); ?>
                            <div class="panel-card-body">
                                <h3><?php echo esc_html($item['name']); ?></h3>
                                <p><?php echo esc_html($item['description']); ?></p>
                                <dl class="spec-grid">
                                    <div><dt>加工内容</dt><dd><?php echo esc_html($item['process']); ?></dd></div>
                                    <div><dt>材質</dt><dd><?php echo esc_html($item['material']); ?></dd></div>
                                    <div><dt>精度</dt><dd><?php echo esc_html($item['precision']); ?></dd></div>
                                </dl>
                            </div>
                        </article>
                    <?php endforeach; ?>
                </div>
            </div>
        </section>
        <section>
            <div class="section-inner two-column align-start">
                <div class="reveal prose-block">
                    <p class="section-eyebrow">Prototyping</p>
                    <h2 class="section-title">試作・開発対応</h2>
                    <p>量産前の試作・開発段階から対応いたします。図面がない段階でのご相談も歓迎し、3Dデータからの加工プログラム作成にも対応しています。</p>
                    <p>試作段階で得た知見を量産設計に反映することで、立ち上がり後の品質安定とコスト最適化につなげています。</p>
                    <div class="stack-grid">
                        <?php foreach (tpi_get_prototyping_features() as $item) : ?>
                            <article class="info-card">
                                <h3><?php echo esc_html($item['title']); ?></h3>
                                <p><?php echo esc_html($item['body']); ?></p>
                            </article>
                        <?php endforeach; ?>
                    </div>
                </div>
                <div class="reveal">
                    <?php tpi_render_media('prototyping', '試作・開発イメージ', 'dark', 'ratio-4-3'); ?>
                </div>
            </div>
        </section>
        <section class="section-dark">
            <div class="section-inner">
                <div class="section-header centered reveal">
                    <p class="section-eyebrow">Technology</p>
                    <h2 class="section-title">対応加工技術</h2>
                </div>
                <div class="card-grid card-grid-3">
                    <?php foreach (tpi_get_processing_technologies() as $item) : ?>
                        <article class="dark-card reveal technology-card">
                            <h3><?php echo esc_html($item['title']); ?></h3>
                            <p><?php echo esc_html($item['body']); ?></p>
                        </article>
                    <?php endforeach; ?>
                </div>
            </div>
        </section>
        <?php tpi_render_common_cta(); ?>

    <?php elseif ($slug === 'equipment') : ?>
        <?php tpi_render_page_hero('Equipment', '設備紹介', '最新設備と熟練の技が、精度を生む', false, 'equipment-hero'); ?>
        <section>
            <div class="section-inner">
                <div class="section-header centered reveal">
                    <p class="section-eyebrow">Overview</p>
                    <h2 class="section-title">充実の設備環境</h2>
                    <p class="section-desc">2つの工場に合計50台以上の加工設備・検査設備を保有し、加工内容に最適な設備を選定しています。</p>
                </div>
                <div class="stat-bar reveal">
                    <?php foreach (tpi_get_equipment_stats() as $item) : ?>
                        <div class="stat-bar-item">
                            <strong><?php echo esc_html($item['value']); ?></strong>
                            <span><?php echo esc_html($item['label']); ?></span>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </section>
        <section class="section-light">
            <div class="section-inner">
                <div class="section-header reveal">
                    <p class="section-eyebrow">CNC Machines</p>
                    <h2 class="section-title">主要加工設備</h2>
                </div>
                <?php foreach (tpi_get_equipment_groups() as $group => $rows) : ?>
                    <div class="equipment-group reveal">
                        <h3><?php echo esc_html($group); ?></h3>
                        <table class="data-table data-table-wide">
                            <thead>
                            <tr>
                                <th>設備名</th>
                                <th>メーカー</th>
                                <th>仕様</th>
                                <th>台数</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php foreach ($rows as $row) : ?>
                                <tr>
                                    <td><?php echo esc_html($row['name']); ?></td>
                                    <td><?php echo esc_html($row['maker']); ?></td>
                                    <td><?php echo esc_html($row['spec']); ?></td>
                                    <td><?php echo esc_html($row['count']); ?></td>
                                </tr>
                            <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php endforeach; ?>
            </div>
        </section>
        <section>
            <div class="section-inner">
                <div class="section-header reveal">
                    <p class="section-eyebrow">Inspection</p>
                    <h2 class="section-title">検査・測定設備</h2>
                    <p class="section-desc">加工精度を保証するため、高精度な検査・測定設備を完備しています。</p>
                </div>
                <div class="card-grid card-grid-3">
                    <?php foreach (tpi_get_inspection_devices() as $item) : ?>
                        <article class="info-card reveal">
                            <h3><?php echo esc_html($item['title']); ?></h3>
                            <p><?php echo esc_html($item['body']); ?></p>
                        </article>
                    <?php endforeach; ?>
                </div>
            </div>
        </section>
        <section class="section-light">
            <div class="section-inner">
                <div class="section-header reveal">
                    <p class="section-eyebrow">Factory Layout</p>
                    <h2 class="section-title">工場紹介</h2>
                </div>
                <div class="card-grid card-grid-2">
                    <?php foreach (tpi_get_factory_sites() as $item) : ?>
                        <article class="panel-card reveal">
                            <?php tpi_render_media($item['image'], $item['title'], 'dark', 'ratio-16-9'); ?>
                            <div class="panel-card-body">
                                <h3><?php echo esc_html($item['title']); ?></h3>
                                <p><?php echo esc_html($item['body']); ?></p>
                            </div>
                        </article>
                    <?php endforeach; ?>
                </div>
            </div>
        </section>
        <?php tpi_render_common_cta(); ?>

    <?php elseif ($slug === 'quality') : ?>
        <?php tpi_render_page_hero('Quality', '品質管理', 'すべての工程に、品質保証の仕組みを', false, 'quality-hero'); ?>
        <section>
            <div class="section-inner">
                <div class="section-header centered reveal">
                    <p class="section-eyebrow">Quality Policy</p>
                    <h2 class="section-title">品質方針</h2>
                    <p class="section-lead">品質は、すべてに優先する。</p>
                    <p class="section-desc slim">受注から出荷まで全工程で品質を管理し、コストや納期のために品質を犠牲にしない体制を徹底しています。</p>
                </div>
                <div class="card-grid card-grid-3">
                    <?php foreach (tpi_get_quality_goals() as $item) : ?>
                        <article class="info-card reveal quality-goal-card">
                            <span class="feature-card-number"><?php echo esc_html($item['number']); ?></span>
                            <h3><?php echo esc_html($item['title']); ?></h3>
                            <p><?php echo esc_html($item['body']); ?></p>
                        </article>
                    <?php endforeach; ?>
                </div>
            </div>
        </section>
        <section class="section-light">
            <div class="section-inner">
                <div class="section-header reveal">
                    <p class="section-eyebrow">QC Flow</p>
                    <h2 class="section-title">品質管理フロー</h2>
                    <p class="section-desc">受注から出荷まで、各工程に検査ポイントを設置。不良品を次工程に送らない仕組みを構築しています。</p>
                </div>
                <div class="flow-grid">
                    <?php foreach (tpi_get_quality_flow() as $item) : ?>
                        <article class="flow-card reveal">
                            <span class="flow-step"><?php echo esc_html($item['step']); ?></span>
                            <h3><?php echo esc_html($item['title']); ?></h3>
                            <p><?php echo esc_html($item['body']); ?></p>
                        </article>
                    <?php endforeach; ?>
                </div>
            </div>
        </section>
        <section>
            <div class="section-inner two-column align-start">
                <div class="reveal">
                    <?php tpi_render_media('quality-inspection', '品質検査風景', 'silver', 'ratio-4-3'); ?>
                </div>
                <div class="reveal prose-block">
                    <p class="section-eyebrow">Inspection System</p>
                    <h2 class="section-title">検査体制</h2>
                    <p>品質管理室に専任スタッフ5名を配置し、三次元測定機・画像測定機を用いて加工品の全数検査を行っています。</p>
                    <ul class="bullet-list">
                        <li>初品検査: 新規品・設計変更品は加工開始時に全寸法を測定</li>
                        <li>工程内検査: 抜き取り検査とSPCで工程の安定性を監視</li>
                        <li>最終検査: 外観検査と寸法検査を実施し、検査成績書を添付</li>
                        <li>校正管理: すべての測定機器を定期校正し、トレーサビリティを確保</li>
                    </ul>
                </div>
            </div>
        </section>
        <section class="section-dark">
            <div class="section-inner">
                <div class="section-header centered reveal">
                    <p class="section-eyebrow">Traceability</p>
                    <h2 class="section-title">トレーサビリティ管理</h2>
                    <p class="section-desc slim light">材料ロット・加工条件・検査記録・出荷先を紐づけて管理し、品質問題発生時にも迅速な原因特定が可能です。</p>
                </div>
                <div class="card-grid card-grid-4">
                    <?php foreach (tpi_get_traceability_items() as $item) : ?>
                        <article class="dark-card reveal">
                            <h3><?php echo esc_html($item['title']); ?></h3>
                            <p><?php echo esc_html($item['body']); ?></p>
                        </article>
                    <?php endforeach; ?>
                </div>
            </div>
        </section>
        <section class="section-light">
            <div class="section-inner">
                <div class="section-header reveal">
                    <p class="section-eyebrow">Certification</p>
                    <h2 class="section-title">認証・規格</h2>
                </div>
                <div class="card-grid card-grid-3">
                    <?php foreach (tpi_get_certifications() as $item) : ?>
                        <article class="info-card reveal certification-card">
                            <h3><?php echo esc_html($item['title']); ?></h3>
                            <p><?php echo esc_html($item['body']); ?></p>
                            <p class="meta certification-year"><?php echo esc_html($item['year']); ?></p>
                        </article>
                    <?php endforeach; ?>
                </div>
            </div>
        </section>
        <?php tpi_render_common_cta(); ?>

    <?php elseif ($slug === 'recruit') : ?>
        <?php tpi_render_page_hero('Recruit', '精度で、未来をつくる仲間へ。', 'ものづくりに情熱を持つあなたを待っています', false, 'recruit-hero'); ?>
        <section>
            <div class="section-inner narrow-text">
                <div class="section-header centered reveal">
                    <p class="section-eyebrow">Message</p>
                    <h2 class="section-title">採用メッセージ</h2>
                    <p class="section-desc">東京精密工業は、「精度が、信頼をつくる」をモットーに、50年以上にわたりものづくりに取り組んできました。特別な経験よりも、「良いものを作りたい」という素直な気持ちと、粘り強く技術を磨く姿勢を歓迎します。</p>
                </div>
            </div>
        </section>
        <section class="section-light">
            <div class="section-inner">
                <div class="section-header reveal">
                    <p class="section-eyebrow">In Numbers</p>
                    <h2 class="section-title">数字で見る東京精密工業</h2>
                </div>
                <div class="card-grid card-grid-4">
                    <?php foreach (tpi_get_recruit_stats() as $item) : ?>
                        <article class="stat-card reveal">
                            <strong><?php echo esc_html($item['value']); ?></strong>
                            <span><?php echo esc_html($item['label']); ?></span>
                        </article>
                    <?php endforeach; ?>
                </div>
            </div>
        </section>
        <section>
            <div class="section-inner">
                <div class="section-header reveal">
                    <p class="section-eyebrow">Open Positions</p>
                    <h2 class="section-title">募集職種</h2>
                </div>
                <div class="accordion-list">
                    <?php foreach (tpi_get_jobs() as $item) : ?>
                        <article class="accordion-item reveal">
                            <button class="accordion-toggle" type="button">
                                <span><?php echo esc_html($item['title']); ?></span>
                                <span class="job-badge"><?php echo esc_html($item['type']); ?></span>
                            </button>
                            <div class="accordion-content">
                                <p class="job-salary"><?php echo esc_html($item['salary']); ?></p>
                                <dl class="detail-list compact">
                                    <?php foreach ($item['detail'] as $label => $value) : ?>
                                        <div>
                                            <dt><?php echo esc_html($label); ?></dt>
                                            <dd><?php echo esc_html($value); ?></dd>
                                        </div>
                                    <?php endforeach; ?>
                                </dl>
                                <ul class="bullet-list">
                                    <?php foreach ($item['tasks'] as $task) : ?>
                                        <li><?php echo esc_html($task); ?></li>
                                    <?php endforeach; ?>
                                </ul>
                            </div>
                        </article>
                    <?php endforeach; ?>
                </div>
            </div>
        </section>
        <section class="section-light">
            <div class="section-inner">
                <div class="section-header reveal">
                    <p class="section-eyebrow">Interview</p>
                    <h2 class="section-title">社員紹介</h2>
                </div>
                <div class="card-grid card-grid-3">
                    <?php foreach (tpi_get_employee_voices() as $item) : ?>
                        <article class="panel-card reveal employee-card">
                            <?php tpi_render_media($item['image'], $item['name'], 'silver', 'ratio-1-1'); ?>
                            <div class="panel-card-body">
                                <h3><?php echo esc_html($item['name']); ?></h3>
                                <p class="meta"><?php echo esc_html($item['role']); ?> / <?php echo esc_html($item['joined']); ?></p>
                                <p><?php echo esc_html($item['body']); ?></p>
                            </div>
                        </article>
                    <?php endforeach; ?>
                </div>
            </div>
        </section>
        <section>
            <div class="section-inner">
                <div class="section-header reveal">
                    <p class="section-eyebrow">Benefits</p>
                    <h2 class="section-title">福利厚生</h2>
                </div>
                <div class="card-grid card-grid-3">
                    <?php foreach (tpi_get_benefits() as $item) : ?>
                        <article class="info-card reveal benefit-card centered">
                            <span class="benefit-icon"><?php echo esc_html($item['icon']); ?></span>
                            <h3><?php echo esc_html($item['title']); ?></h3>
                            <p><?php echo esc_html($item['body']); ?></p>
                        </article>
                    <?php endforeach; ?>
                </div>
            </div>
        </section>
        <section class="section-light">
            <div class="section-inner">
                <div class="section-header reveal">
                    <p class="section-eyebrow">Flow</p>
                    <h2 class="section-title">応募フロー</h2>
                </div>
                <div class="step-flow-grid">
                    <?php foreach (tpi_get_recruit_flow() as $item) : ?>
                        <article class="step-flow-item reveal">
                            <span class="flow-step"><?php echo esc_html($item['step']); ?></span>
                            <h3><?php echo esc_html($item['title']); ?></h3>
                            <p><?php echo esc_html($item['body']); ?></p>
                        </article>
                    <?php endforeach; ?>
                </div>
            </div>
        </section>
        <section class="cta-band recruit-cta">
            <div class="section-inner cta-band-inner">
                <div>
                    <p class="section-eyebrow">Entry</p>
                    <h2 class="section-title">一緒に、精度の先を追求しませんか？</h2>
                    <p class="section-desc slim">ご応募・ご質問は下記よりお気軽にどうぞ。工場見学やカジュアル面談のご相談も歓迎しています。</p>
                </div>
                <div class="cta-band-actions">
                    <a class="button button-primary" href="<?php echo esc_url(home_url('/contact/')); ?>">エントリーする</a>
                    <a class="button button-secondary" href="<?php echo esc_url(tpi_get_phone_href()); ?>">電話で問い合わせ</a>
                </div>
            </div>
        </section>

    <?php elseif ($slug === 'contact') : ?>
        <?php tpi_render_page_hero('Contact', 'お問い合わせ', '精密加工のご相談・お見積りはお気軽にどうぞ', true); ?>
        <section>
            <div class="section-inner">
                <div class="card-grid card-grid-3">
                    <?php foreach (tpi_get_contact_methods() as $item) : ?>
                        <article class="info-card centered reveal">
                            <h3><?php echo esc_html($item['title']); ?></h3>
                            <p><?php echo wp_kses_post(nl2br(esc_html($item['body']))); ?></p>
                        </article>
                    <?php endforeach; ?>
                </div>
            </div>
        </section>
        <section class="section-light">
            <div class="section-inner two-column align-start">
                <div class="reveal">
                    <p class="section-eyebrow">Form</p>
                    <h2 class="section-title">メールフォーム</h2>
                    <p class="section-desc slim">以下のフォームに必要事項をご記入の上、送信してください。デモサイトのため、送信後は完了ページに遷移します。</p>
                    <?php tpi_render_contact_form(); ?>
                </div>
                <div class="reveal stack-grid">
                    <div class="info-card map-card">
                        <h3><?php echo esc_html($profile['site_short_name']); ?></h3>
                        <p><?php echo esc_html($profile['address']); ?></p>
                        <p><a href="<?php echo esc_url(tpi_get_phone_href()); ?>"><?php echo esc_html($profile['phone']); ?></a></p>
                        <p>FAX: <?php echo esc_html($profile['fax']); ?></p>
                        <p><?php echo esc_html($profile['hours']); ?></p>
                    </div>
                    <div class="map-frame compact">
                        <iframe src="<?php echo esc_url(tpi_get_map_embed_url()); ?>" loading="lazy" referrerpolicy="no-referrer-when-downgrade" title="東京精密工業へのアクセスマップ"></iframe>
                    </div>
                </div>
            </div>
        </section>

    <?php elseif ($slug === 'thanks') : ?>
        <?php tpi_render_page_hero('Thanks', '送信が完了しました', 'お問い合わせありがとうございます。内容を確認のうえ、担当よりご連絡いたします。', true); ?>
        <section>
            <div class="section-inner narrow-box centered">
                <div class="success-badge">✓</div>
                <p class="section-desc slim">通常1営業日以内に返信いたします。お急ぎの場合はお電話でも受け付けています。</p>
                <a class="button button-primary" href="<?php echo esc_url(home_url('/')); ?>">トップページに戻る</a>
            </div>
        </section>

    <?php elseif ($slug === 'privacy') : ?>
        <?php tpi_render_page_hero('Privacy Policy', 'プライバシーポリシー', '', true); ?>
        <section class="section-light">
            <div class="section-inner prose-block narrow">
                <?php
                if (trim((string) get_the_content()) !== '') {
                    the_content();
                } else {
                    ?>
                    <p>東京精密工業株式会社は、お問い合わせ・採用応募などで取得した個人情報を、回答・連絡・採用選考の目的にのみ利用します。</p>
                    <p>取得した情報は適切に管理し、法令に基づく場合を除き第三者へ提供しません。お問い合わせ窓口は <a href="mailto:<?php echo esc_attr($profile['mail']); ?>"><?php echo esc_html($profile['mail']); ?></a> です。</p>
                    <?php
                }
                ?>
            </div>
        </section>

    <?php else : ?>
        <?php tpi_render_page_hero('Page', get_the_title(), '', true); ?>
        <section class="section-light">
            <div class="section-inner prose-block narrow">
                <?php the_content(); ?>
            </div>
        </section>
    <?php endif; ?>
</main>
<?php
get_footer();
