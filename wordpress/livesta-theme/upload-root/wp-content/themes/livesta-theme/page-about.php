<?php
get_header();
$company = livesta_get_company_profile();
$phone_href = livesta_get_company_phone_href();
$mail_address = antispambot($company['mail']);
$mail_href = 'mailto:' . $mail_address;
?>
<main id="content">
    <?php livesta_render_page_hero([
        'eyebrow' => 'About Us',
        'title' => '会社概要',
        'subtitle' => '人と街の未来をつなぐ不動産パートナー',
        'class' => 'about-hero',
    ]); ?>

    <?php livesta_render_breadcrumb(); ?>

    <section class="content-section">
        <div class="section-inner">
            <div class="message-grid">
                <div>
                    <div class="message-photo">
                        <img src="<?php echo esc_url(livesta_get_theme_image_url('ceo-portrait.png')); ?>" alt="<?php echo esc_attr($company['message_name']); ?>" loading="lazy">
                    </div>
                    <div class="message-caption">
                        <p><?php echo esc_html($company['message_title']); ?></p>
                        <p class="name"><?php echo esc_html($company['message_name']); ?></p>
                        <p class="roman"><?php echo esc_html($company['message_name_en']); ?></p>
                    </div>
                </div>
                <div>
                    <p class="section-eyebrow">Message</p>
                    <h2 class="section-heading">ご挨拶</h2>
                    <div class="prose">
                        <p>私たちは、住まいを探す方と街の未来を考える方、そのどちらにも誠実であることを大切にしています。</p>
                        <p>物件のご紹介だけでなく、売却査定や賃貸管理、用地検討まで一つの窓口で整理し、判断しやすい情報提供を心がけています。</p>
                        <p>初めてのご相談でも、状況を丁寧にヒアリングし、次に取るべき行動が見える状態まで伴走することがLIVESTAの役割です。</p>
                        <p>地域に根ざした目線と実務の確かさを両立させ、長く相談できる不動産パートナーを目指しています。</p>
                    </div>
                    <p class="message-sign">
                        <?php echo esc_html($company['company_name']); ?><br>
                        <?php echo esc_html($company['message_title'] . ' ' . $company['message_name']); ?>
                    </p>
                </div>
            </div>
        </div>
    </section>

    <section class="content-section cream text-center">
        <div class="section-inner">
            <p class="section-eyebrow">Philosophy</p>
            <h2 class="section-heading">企業理念</h2>
            <p class="center-copy">暮らしに、確かな価値を。</p>
            <p class="sub-desc">私たちは、不動産を通じて人々の暮らしに確かな価値を届けることを使命としています。お客様の信頼に応え、地域社会の発展に貢献し、持続可能な街づくりに取り組みます。</p>

            <div class="value-grid">
                <article class="value-card">
                    <p class="card-num">01</p>
                    <h3>Trust — 信頼</h3>
                    <p>お客様との信頼関係を大切にし、透明性のある情報提供と誠実な対応を心がけています。</p>
                </article>
                <article class="value-card">
                    <p class="card-num">02</p>
                    <h3>Quality — 品質</h3>
                    <p>物件選定から契約・アフターフォローまで、すべてのプロセスにおいて高い品質を追求します。</p>
                </article>
                <article class="value-card">
                    <p class="card-num">03</p>
                    <h3>Community — 地域貢献</h3>
                    <p>地域に根ざした事業活動を通じて、街の魅力と暮らしの質を高めることに貢献します。</p>
                </article>
            </div>
        </div>
    </section>

    <section class="content-section">
        <div class="section-inner narrow">
            <p class="section-eyebrow">Company</p>
            <h2 class="section-heading">会社概要</h2>

            <table class="company-table">
                <tbody>
                <tr><th>会社名</th><td><?php echo esc_html($company['company_name']); ?></td></tr>
                <tr><th>代表者</th><td><?php echo esc_html($company['representative']); ?></td></tr>
                <tr><th>設立</th><td><?php echo esc_html($company['founded']); ?></td></tr>
                <tr><th>資本金</th><td><?php echo esc_html($company['capital']); ?></td></tr>
                <tr><th>従業員数</th><td><?php echo esc_html($company['employees']); ?></td></tr>
                <tr><th>所在地</th><td><?php echo esc_html($company['postal_code'] . ' ' . $company['address']); ?></td></tr>
                <tr><th>電話番号</th><td><a href="<?php echo esc_url($phone_href); ?>"><?php echo esc_html($company['phone']); ?></a></td></tr>
                <tr><th>FAX</th><td><?php echo esc_html($company['fax']); ?></td></tr>
                <tr><th>メール</th><td><a href="<?php echo esc_url($mail_href); ?>"><?php echo esc_html($mail_address); ?></a></td></tr>
                <tr><th>営業時間</th><td><?php echo esc_html($company['hours']); ?> / <?php echo esc_html($company['closed']); ?></td></tr>
                <tr><th>事業内容</th><td><?php echo esc_html($company['business']); ?></td></tr>
                <tr><th>免許番号</th><td><?php echo esc_html($company['license']); ?></td></tr>
                <tr><th>所属団体</th><td><?php echo esc_html($company['associations']); ?></td></tr>
                </tbody>
            </table>
        </div>
    </section>

    <section class="content-section cream">
        <div class="section-inner narrow">
            <p class="section-eyebrow">History</p>
            <h2 class="section-heading">沿革</h2>
            <ul class="history-list">
                <li><span class="history-year">2016年4月</span><span class="history-text">渋谷区恵比寿にてLIVESTAを設立。都心居住向けの売買仲介を開始。</span></li>
                <li><span class="history-year">2018年7月</span><span class="history-text">オーナー向けの賃貸管理サービスを開始し、住居系資産の運用支援を拡充。</span></li>
                <li><span class="history-year">2020年10月</span><span class="history-text">住み替え相談と売却査定の窓口を一本化し、相談導線を再設計。</span></li>
                <li><span class="history-year">2022年5月</span><span class="history-text">用地取得と企画支援を担う開発領域を立ち上げ、外部パートナー連携を強化。</span></li>
                <li><span class="history-year">2024年9月</span><span class="history-text">代官山・目黒・世田谷を中心に、掲載物件と管理相談の対応エリアを拡大。</span></li>
                <li><span class="history-year">2026年3月</span><span class="history-text">コーポレートサイトを刷新し、物件情報と問い合わせ導線を統合。</span></li>
            </ul>
        </div>
    </section>

    <section class="content-section">
        <div class="section-inner">
            <div class="access-grid">
                <div>
                    <p class="section-eyebrow">Access</p>
                    <h2 class="section-heading">アクセス</h2>
                    <ul class="access-list">
                        <li>
                            <span class="label">住所</span>
                            <?php echo esc_html($company['postal_code'] . ' ' . $company['address']); ?>
                        </li>
                        <li>
                            <span class="label">電話番号</span>
                            <a href="<?php echo esc_url($phone_href); ?>"><?php echo esc_html($company['phone']); ?></a>
                        </li>
                        <li>
                            <span class="label">メール</span>
                            <a href="<?php echo esc_url($mail_href); ?>"><?php echo esc_html($mail_address); ?></a>
                        </li>
                        <li>
                            <span class="label">営業時間</span>
                            <?php echo esc_html($company['hours']); ?> / <?php echo esc_html($company['closed']); ?>
                        </li>
                        <li>
                            <span class="label">最寄り駅</span>
                            <?php echo esc_html($company['access']); ?>
                        </li>
                    </ul>
                </div>
                <div class="access-map">
                    <?php livesta_render_access_panel(['title' => '本社アクセス']); ?>
                </div>
            </div>
        </div>
    </section>

    <?php livesta_render_common_cta(); ?>
</main>
<?php
get_footer();
