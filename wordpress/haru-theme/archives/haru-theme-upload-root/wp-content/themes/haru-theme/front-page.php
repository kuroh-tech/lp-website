<?php
get_header();
$profile = haru_get_profile();
$latest_news = haru_get_latest_news(3);
?>
<main>
    <section class="hero hero-home">
        <div class="hero-content">
            <p class="section-eyebrow">Specialty Coffee</p>
            <h1>一杯に、<br>ていねいな時間を。</h1>
            <p class="hero-lead">自家焙煎のスペシャルティコーヒーと、手づくりのお菓子でお迎えするちいさな珈琲店です。</p>
            <div class="hero-actions">
                <a class="button button-primary" href="<?php echo esc_url(home_url('/menu/')); ?>">メニューを見る</a>
                <a class="button button-ghost" href="<?php echo esc_url($profile['instagram']); ?>" target="_blank" rel="noreferrer noopener">Instagram</a>
            </div>
            <div class="hero-hours">
                <div><span>Open</span><strong>8:00 – 18:00</strong></div>
                <div><span>Closed</span><strong><?php echo esc_html($profile['closed']); ?></strong></div>
                <div><span>Location</span><strong>東京・蔵前</strong></div>
            </div>
        </div>
        <div class="hero-scroll">Scroll</div>
    </section>

    <section>
        <div class="section-inner two-column">
            <div class="reveal">
                <?php haru_render_placeholder('concept-hero', 'arch'); ?>
            </div>
            <div class="reveal prose-block">
                <p class="section-eyebrow">Concept</p>
                <h2 class="section-title">素材と向き合い、<br>一杯ずつ、ていねいに。</h2>
                <p>HARUは、2019年に蔵前の路地裏にオープンしたちいさな珈琲店です。産地から届いたコーヒー豆を自家焙煎し、一杯ずつハンドドリップでお淹れしています。</p>
                <p>コーヒーに合わせた手づくりのお菓子とともに、ゆっくりとした時間をお過ごしいただける空間を目指しています。</p>
                <div class="inline-actions">
                    <a class="button button-secondary" href="<?php echo esc_url(home_url('/concept/')); ?>">コンセプトを見る</a>
                </div>
            </div>
        </div>
    </section>

    <section class="section-accent">
        <div class="section-inner">
            <div class="section-header centered reveal">
                <p class="section-eyebrow">Menu</p>
                <h2 class="section-title">おすすめメニュー</h2>
                <p class="section-desc slim centered">季節ごとに変わるシングルオリジンと、定番のブレンドをご用意しています。</p>
            </div>
            <div class="card-grid card-grid-3">
                <?php foreach (haru_get_featured_menu() as $item) : ?>
                    <article class="menu-card reveal">
                        <?php haru_render_placeholder($item['image'], 'square'); ?>
                        <div class="menu-card-body">
                            <p class="menu-cat"><?php echo esc_html($item['category']); ?></p>
                            <h3><?php echo esc_html($item['title']); ?></h3>
                            <p class="price"><?php echo esc_html($item['price']); ?> <span>(tax in)</span></p>
                        </div>
                    </article>
                <?php endforeach; ?>
            </div>
        </div>
    </section>

    <section class="gallery-section">
        <div class="gallery-grid">
            <?php foreach (haru_get_gallery_items() as $item) : ?>
                <div class="gallery-card reveal">
                    <?php haru_render_placeholder($item['key'], 'gallery-square'); ?>
                    <p class="gallery-caption"><?php echo esc_html($item['title']); ?></p>
                </div>
            <?php endforeach; ?>
        </div>
    </section>

    <section class="section-plain">
        <div class="section-inner">
            <div class="section-header centered reveal">
                <p class="section-eyebrow">News</p>
                <h2 class="section-title">最近のお知らせ</h2>
                <p class="section-desc slim centered">営業時間の変更や季節のメニューは、こちらとInstagramでご案内しています。</p>
            </div>
            <?php if ($latest_news) : ?>
                <div class="news-list home-news-list">
                    <?php foreach ($latest_news as $news_post) : ?>
                        <?php haru_render_news_row($news_post); ?>
                    <?php endforeach; ?>
                </div>
            <?php else : ?>
                <article class="info-card centered">公開中のお知らせはありません。</article>
            <?php endif; ?>
            <div class="centered inline-actions">
                <a class="button button-secondary" href="<?php echo esc_url(haru_get_news_url()); ?>">すべてのお知らせを見る</a>
            </div>
        </div>
    </section>

    <section>
        <div class="section-inner two-column">
            <div class="reveal map-frame">
                <iframe src="<?php echo esc_url(haru_get_map_embed_url()); ?>" loading="lazy" referrerpolicy="no-referrer-when-downgrade" title="HARU COFFEEへのアクセスマップ"></iframe>
            </div>
            <div class="reveal">
                <p class="section-eyebrow">Access</p>
                <h2 class="section-title">店舗情報</h2>
                <ul class="detail-list">
                    <li><span>店名</span><?php echo esc_html($profile['site_name']); ?></li>
                    <li><span>住所</span><?php echo esc_html($profile['address']); ?></li>
                    <li><span>営業時間</span><?php echo esc_html($profile['hours']); ?></li>
                    <li><span>定休日</span><?php echo esc_html($profile['closed']); ?></li>
                    <li><span>席数</span><?php echo esc_html($profile['seats']); ?></li>
                </ul>
                <div class="inline-actions">
                    <a class="button button-secondary" href="<?php echo esc_url(home_url('/access/')); ?>">アクセス詳細へ</a>
                </div>
            </div>
        </div>
    </section>

    <?php haru_render_common_cta(); ?>
</main>
<?php
get_footer();
