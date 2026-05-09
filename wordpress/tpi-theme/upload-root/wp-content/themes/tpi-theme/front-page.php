<?php
get_header();
$profile = tpi_get_company_profile();
$home_hero_url = tpi_get_image_url('home-hero');
$home_hero_classes = 'hero hero-home';
$home_hero_style = '';

if ($home_hero_url !== '') {
    $home_hero_classes .= ' has-image';
    $home_hero_style = '--hero-image: url("' . esc_url_raw($home_hero_url) . '");';
}
?>
<main>
    <section class="<?php echo esc_attr($home_hero_classes); ?>"<?php echo $home_hero_style !== '' ? ' style="' . esc_attr($home_hero_style) . '"' : ''; ?>>
        <div class="hero-grid-lines" aria-hidden="true"></div>
        <div class="section-inner hero-inner">
            <div class="hero-copy">
                <p class="section-eyebrow">Precision Manufacturing</p>
                <h1><em>精度</em>が、<br>信頼をつくる。</h1>
                <p class="hero-lead">金属精密加工のプロフェッショナル。自動車・航空宇宙・医療機器分野で、ミクロン単位の精度を追求し続けています。</p>
                <div class="hero-actions">
                    <a class="button button-primary" href="<?php echo esc_url(home_url('/products/')); ?>">製品を見る</a>
                    <a class="button button-secondary" href="<?php echo esc_url(home_url('/contact/')); ?>">お問い合わせ</a>
                </div>
            </div>
            <div class="hero-stats">
                <div><strong>50年+</strong><span>創業</span></div>
                <div><strong>±0.001mm</strong><span>加工精度</span></div>
                <div><strong>300社+</strong><span>取引先</span></div>
            </div>
        </div>
    </section>

    <section class="section-light">
        <div class="section-inner">
            <div class="section-header reveal">
                <p class="section-eyebrow">Strength</p>
                <h2 class="section-title">選ばれる3つの理由</h2>
                <p class="section-desc">半世紀にわたり磨き上げた技術力と、変わらぬ品質へのこだわり。</p>
            </div>
            <div class="card-grid card-grid-3">
                <?php foreach (tpi_get_strengths() as $index => $item) : ?>
                    <article class="feature-card reveal">
                        <span class="feature-card-number"><?php echo esc_html(sprintf('%02d', $index + 1)); ?></span>
                        <h3><?php echo esc_html($item['title']); ?></h3>
                        <p><?php echo esc_html($item['body']); ?></p>
                    </article>
                <?php endforeach; ?>
            </div>
        </div>
    </section>

    <section>
        <div class="section-inner">
            <div class="section-header reveal">
                <p class="section-eyebrow">Products</p>
                <h2 class="section-title">製品紹介</h2>
                <p class="section-desc">自動車・航空宇宙・医療機器など、多様な分野の精密部品を手がけています。</p>
            </div>
            <div class="card-grid card-grid-3">
                <?php foreach (tpi_get_featured_products() as $item) : ?>
                    <article class="panel-card reveal">
                        <?php tpi_render_media($item['image'], $item['title'], 'dark', 'ratio-16-10'); ?>
                        <div class="panel-card-body">
                            <h3><?php echo esc_html($item['title']); ?></h3>
                            <p><?php echo esc_html($item['body']); ?></p>
                            <a class="inline-link" href="<?php echo esc_url(home_url($item['anchor'])); ?>">詳しく見る</a>
                        </div>
                    </article>
                <?php endforeach; ?>
            </div>
        </div>
    </section>

    <section class="section-light">
        <div class="section-inner two-column">
            <div class="reveal">
                <?php tpi_render_media('company-top', '会社紹介イメージ', 'silver', 'ratio-4-3'); ?>
            </div>
            <div class="reveal">
                <p class="section-eyebrow">Company</p>
                <h2 class="section-title">会社概要</h2>
                <table class="data-table">
                    <tbody>
                    <?php foreach (array_slice(tpi_get_company_overview_rows(), 0, 8) as $row) : ?>
                        <tr>
                            <th><?php echo esc_html($row['label']); ?></th>
                            <td><?php echo esc_html($row['value']); ?></td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
                <div class="stack-actions">
                    <a class="button button-primary" href="<?php echo esc_url(home_url('/company/')); ?>">会社概要へ</a>
                    <a class="button button-secondary" href="<?php echo esc_url(home_url('/equipment/')); ?>">設備紹介へ</a>
                </div>
            </div>
        </div>
    </section>

    <?php tpi_render_common_cta(); ?>
</main>
<?php
get_footer();
