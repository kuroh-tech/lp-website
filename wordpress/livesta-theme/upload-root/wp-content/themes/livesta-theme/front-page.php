<?php
get_header();

$property_url = livesta_get_property_archive_url();
$news_url = livesta_get_news_page_url();
$contact_url = home_url('/contact/');
$company = livesta_get_company_profile();
$phone_href = livesta_get_company_phone_href();
?>
<main id="content">
    <section class="hero">
        <div class="hero-bg"></div>
        <div class="hero-content">
            <p class="hero-eyebrow">Real Estate &amp; Development</p>
            <h1>暮らしに、<br>確かな価値を。</h1>
            <p class="hero-sub">
                不動産の売買・仲介から開発まで。<br>
                一人ひとりの理想の暮らしに寄り添い、<br>
                確かな価値を届けます。
            </p>
            <div class="hero-actions">
                <a href="<?php echo esc_url($property_url); ?>" class="hero-btn">
                    物件を探す
                    <?php echo livesta_svg_arrow(18); ?>
                </a>
                <a href="<?php echo esc_url(add_query_arg('inquiry_type', '売却査定', $contact_url)); ?>" class="hero-btn hero-btn-secondary">
                    売却査定を相談
                    <?php echo livesta_svg_arrow(18); ?>
                </a>
            </div>
        </div>
        <div class="hero-scroll">
            <div class="hero-scroll-line"></div>
            Scroll
        </div>
    </section>

    <section class="about" id="about">
        <div class="section-inner">
            <div class="about-grid">
                <div class="about-image reveal">
                    <img src="<?php echo esc_url(livesta_get_theme_image_url('top-about.png')); ?>" alt="LIVESTAのオフィスイメージ" loading="lazy">
                </div>
                <div class="reveal about-text">
                    <p class="section-eyebrow">About Us</p>
                    <h3>人と街の未来をつなぐ<br>不動産パートナー</h3>
                    <p>LIVESTAは、代官山・目黒・世田谷を中心に、住まい探しから資産活用までを支援する不動産パートナーです。</p>
                    <p>売買仲介、賃貸管理、不動産開発、コンサルティングを一つの窓口でつなぎ、購入・売却・運用の各フェーズに応じた提案を行っています。</p>
                    <div class="about-numbers">
                        <div class="about-num">
                            <div class="num"><?php echo esc_html($company['managed_units']); ?></div>
                            <div class="label">管理・募集サポート戸数</div>
                        </div>
                        <div class="about-num">
                            <div class="num">都心6区</div>
                            <div class="label">代官山・目黒・世田谷ほか</div>
                        </div>
                        <div class="about-num">
                            <div class="num"><?php echo esc_html($company['development_count']); ?></div>
                            <div class="label">開発・企画支援の実績</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="service" id="service">
        <div class="section-inner">
            <div class="reveal">
                <p class="section-eyebrow">Service</p>
                <h2 class="section-title">事業内容</h2>
                <p class="section-desc">売買仲介から開発まで、不動産に関する各種ご相談に対応しています。</p>
            </div>
            <div class="service-grid">
                <a class="service-card reveal" href="<?php echo esc_url(home_url('/service/#brokerage')); ?>">
                    <p class="service-num">01</p>
                    <h3>売買仲介</h3>
                    <p>マンション・戸建・土地の売買仲介。物件探しから契約手続きまで、丁寧にご案内します。</p>
                </a>
                <a class="service-card reveal" href="<?php echo esc_url(home_url('/service/#management')); ?>">
                    <p class="service-num">02</p>
                    <h3>賃貸管理</h3>
                    <p>入居者募集から建物管理、退去対応まで。オーナー様の資産価値を守る総合管理サービスです。</p>
                </a>
                <a class="service-card reveal" href="<?php echo esc_url(home_url('/service/#development')); ?>">
                    <p class="service-num">03</p>
                    <h3>不動産開発</h3>
                    <p>土地の仕入れから企画・設計・施工管理まで。街の魅力を引き出すプロジェクトを手がけています。</p>
                </a>
                <a class="service-card reveal" href="<?php echo esc_url(home_url('/service/#consulting')); ?>">
                    <p class="service-num">04</p>
                    <h3>コンサルティング</h3>
                    <p>相続・資産活用・投資判断など、不動産に関する複雑な課題整理をサポートします。</p>
                </a>
            </div>
        </div>
    </section>

    <section class="property" id="property">
        <div class="section-inner">
            <div class="reveal">
                <p class="section-eyebrow">Property</p>
                <h2 class="section-title">物件情報</h2>
                <p class="section-desc">代官山・目黒・世田谷など、都心6エリアの掲載物件をご紹介しています。</p>
            </div>
            <div class="property-grid">
                <?php
                $property_query = new WP_Query(
                    [
                        'post_type'      => 'property',
                        'post_status'    => 'publish',
                        'posts_per_page' => 3,
                    ]
                );

                if ($property_query->have_posts()) :
                    while ($property_query->have_posts()) :
                        $property_query->the_post();
                        $post_id = get_the_ID();
                        $badge = livesta_get_property_meta($post_id, 'badge', '');
                        $location = livesta_get_property_meta($post_id, 'location', '都心6エリア');
                        $price = livesta_get_property_meta($post_id, 'price', '価格はお問い合わせください');
                        $layout = livesta_get_property_meta($post_id, 'layout', 'ー');
                        $area_size = livesta_get_property_meta($post_id, 'area_size', 'ー');
                        ?>
                        <article class="property-card reveal">
                            <a href="<?php the_permalink(); ?>">
                                <div class="property-thumb">
                                    <?php if ($badge !== '') : ?>
                                        <span class="property-tag"><?php echo esc_html($badge); ?></span>
                                    <?php endif; ?>
                                    <?php livesta_render_property_image($post_id, get_the_title()); ?>
                                </div>
                                <div class="property-body">
                                    <h3><?php the_title(); ?></h3>
                                    <p class="location"><?php echo esc_html($location); ?></p>
                                    <div class="property-info">
                                        <span class="price"><?php echo esc_html($price); ?></span>
                                        <span><?php echo esc_html($layout); ?></span>
                                        <span><?php echo esc_html($area_size); ?></span>
                                    </div>
                                </div>
                            </a>
                        </article>
                        <?php
                    endwhile;
                    wp_reset_postdata();
                else :
                    $fallback_properties = array_slice(livesta_get_sample_properties(), 0, 3);
                    foreach ($fallback_properties as $item) :
                        ?>
                        <article class="property-card reveal">
                            <div class="property-thumb">
                                <?php if ($item['badge'] !== '') : ?>
                                    <span class="property-tag"><?php echo esc_html($item['badge']); ?></span>
                                <?php endif; ?>
                                <img src="<?php echo esc_url(livesta_get_theme_image_url($item['image'])); ?>" alt="<?php echo esc_attr($item['title']); ?>" loading="lazy">
                            </div>
                            <div class="property-body">
                                <h3><?php echo esc_html($item['title']); ?></h3>
                                <p class="location"><?php echo esc_html($item['location']); ?></p>
                                <div class="property-info">
                                    <span class="price"><?php echo esc_html($item['price']); ?></span>
                                    <span><?php echo esc_html($item['layout']); ?></span>
                                    <span><?php echo esc_html($item['area']); ?></span>
                                </div>
                            </div>
                        </article>
                        <?php
                    endforeach;
                endif;
                ?>
            </div>
            <a href="<?php echo esc_url($property_url); ?>" class="view-all reveal">
                すべての物件を見る
                <?php echo livesta_svg_arrow(); ?>
            </a>
        </div>
    </section>

    <section class="news" id="news">
        <div class="section-inner">
            <div class="reveal">
                <p class="section-eyebrow">News</p>
                <h2 class="section-title">お知らせ</h2>
            </div>
            <div class="news-list reveal">
                <?php
                $news_query = new WP_Query(
                    livesta_get_news_query_args(4)
                );

                if ($news_query->have_posts()) :
                    while ($news_query->have_posts()) :
                        $news_query->the_post();
                        ?>
                        <a href="<?php the_permalink(); ?>" class="news-item">
                            <span class="news-date"><?php echo esc_html(get_the_date('Y.m.d')); ?></span>
                            <span class="news-cat"><?php echo esc_html(livesta_get_primary_category_name(get_the_ID())); ?></span>
                            <span class="news-title"><?php the_title(); ?></span>
                        </a>
                        <?php
                    endwhile;
                    wp_reset_postdata();
                else :
                    $fallback_news = array_slice(livesta_get_sample_news(), 0, 4);
                    foreach ($fallback_news as $item) :
                        ?>
                        <div class="news-item">
                            <span class="news-date"><?php echo esc_html($item['date']); ?></span>
                            <span class="news-cat"><?php echo esc_html($item['category']); ?></span>
                            <span class="news-title"><?php echo esc_html($item['title']); ?></span>
                        </div>
                        <?php
                    endforeach;
                endif;
                ?>
            </div>
            <a href="<?php echo esc_url($news_url); ?>" class="view-all reveal news-view-all">
                すべてのお知らせ
                <?php echo livesta_svg_arrow(); ?>
            </a>
        </div>
    </section>

    <section class="contact" id="contact">
        <div class="contact-inner">
            <div>
                <p class="section-eyebrow">Contact</p>
                <h2 class="section-title">お問い合わせ</h2>
                <p class="section-desc">物件のご相談・ご質問など、お気軽にお問い合わせください。</p>
                <div class="contact-info">
                    <p class="contact-line"><strong>TEL</strong> <a href="<?php echo esc_url($phone_href); ?>"><?php echo esc_html($company['phone']); ?></a></p>
                    <p class="contact-line"><strong>受付時間</strong> <?php echo esc_html($company['hours']); ?> / <?php echo esc_html($company['closed']); ?></p>
                    <p class="contact-line"><strong>アクセス</strong> <?php echo esc_html($company['access']); ?></p>
                    <p class="mt-sm"><?php echo esc_html($company['office_note']); ?></p>
                </div>
                <a href="<?php echo esc_url($contact_url); ?>" class="contact-btn">
                    お問い合わせフォーム
                    <?php echo livesta_svg_arrow(); ?>
                </a>
            </div>
            <div class="contact-map">
                <?php livesta_render_access_panel([
                    'title' => 'ご来店・ご相談',
                    'compact' => true,
                ]); ?>
            </div>
        </div>
    </section>
</main>
<?php
get_footer();
