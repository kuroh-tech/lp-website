<?php
get_header();

while (have_posts()) :
    the_post();
    $post_id = get_the_ID();
    $category_name = livesta_get_primary_category_name($post_id);
    $previous_post = get_previous_post();
    $next_post = get_next_post();
    ?>
    <main id="content">
        <?php livesta_render_breadcrumb(); ?>

        <section class="content-section">
            <header class="single-news-header">
                <div class="meta-row">
                    <span class="news-cat"><?php echo esc_html($category_name); ?></span>
                    <span class="news-date"><?php echo esc_html(get_the_date('Y.m.d')); ?></span>
                </div>
                <h1><?php the_title(); ?></h1>
            </header>
        </section>

        <section class="content-section single-news-body">
            <article class="entry-content">
                <?php
                $content = trim((string) get_the_content());
                if ($content !== '') {
                    the_content();
                } else {
                    ?>
                    <p>日頃よりLIVESTAをご利用いただき、ありがとうございます。</p>
                    <p>当社では、物件情報の更新に加えて、売却査定や賃貸管理に関するご相談内容も順次発信しています。</p>
                    <h2>情報発信で大切にしていること</h2>
                    <p>お問い合わせ前の段階でも判断しやすいよう、物件の特徴だけでなく、検討時に確認しておきたいポイントもあわせて整理しています。</p>
                    <h3>掲載情報の見直し</h3>
                    <p>物件情報は、価格、間取り、交通、周辺環境など、比較検討に必要な情報が追いやすい構成に整えています。</p>
                    <h3>相談導線の整理</h3>
                    <p>物件問い合わせ、売却査定、来店予約など、目的ごとに相談内容を整理しやすいフォーム構成にしています。</p>
                    <h3>継続的な更新</h3>
                    <p>新着物件、営業日のお知らせ、購入前のチェックポイントなど、更新が必要な情報を運用しやすい形で掲載しています。</p>
                    <p>気になる点がございましたら、お気軽にお問い合わせフォームよりご連絡ください。</p>
                    <?php
                }
                ?>
            </article>
        </section>

        <section class="post-nav-wrap">
            <div class="post-nav">
                <?php if ($previous_post) : ?>
                    <a href="<?php echo esc_url(get_permalink($previous_post)); ?>">
                        <span class="label">← 前の記事</span>
                        <span class="title"><?php echo esc_html(get_the_title($previous_post)); ?></span>
                    </a>
                <?php else : ?>
                    <div class="empty"><span class="label">← 前の記事</span><span class="title">記事はありません</span></div>
                <?php endif; ?>

                <?php if ($next_post) : ?>
                    <a href="<?php echo esc_url(get_permalink($next_post)); ?>">
                        <span class="label">次の記事 →</span>
                        <span class="title"><?php echo esc_html(get_the_title($next_post)); ?></span>
                    </a>
                <?php else : ?>
                    <div class="empty"><span class="label">次の記事 →</span><span class="title">記事はありません</span></div>
                <?php endif; ?>
            </div>
        </section>

        <section class="content-section">
            <div class="related-posts">
                <h2 class="section-heading">関連記事</h2>
                <div class="related-posts-grid">
                    <?php
                    $category_ids = wp_get_post_categories($post_id);
                    $related_args = [
                        'post_type'      => 'post',
                        'post_status'    => 'publish',
                        'posts_per_page' => 3,
                        'post__not_in'   => [$post_id],
                    ];

                    if (!empty($category_ids)) {
                        $related_args['category__in'] = $category_ids;
                    }

                    $related_query = new WP_Query($related_args);

                    if ($related_query->have_posts()) :
                        while ($related_query->have_posts()) :
                            $related_query->the_post();
                            ?>
                            <article class="related-post">
                                <a href="<?php the_permalink(); ?>">
                                    <div class="related-post-thumb">
                                        <?php if (has_post_thumbnail()) : ?>
                                            <?php the_post_thumbnail('medium_large'); ?>
                                        <?php else : ?>
                                            <img src="<?php echo esc_url(livesta_get_theme_image_url('news-default-thumb.png')); ?>" alt="<?php the_title_attribute(); ?>" loading="lazy">
                                        <?php endif; ?>
                                    </div>
                                    <div class="related-post-body">
                                        <div class="meta">
                                            <span class="date"><?php echo esc_html(get_the_date('Y.m.d')); ?></span>
                                            <span class="news-cat"><?php echo esc_html(livesta_get_primary_category_name(get_the_ID())); ?></span>
                                        </div>
                                        <h3><?php the_title(); ?></h3>
                                    </div>
                                </a>
                            </article>
                            <?php
                        endwhile;
                        wp_reset_postdata();
                    else :
                        $fallback_news = array_slice(livesta_get_sample_news(), 0, 3);
                        foreach ($fallback_news as $item) :
                            ?>
                            <article class="related-post">
                                <div class="related-post-thumb">
                                    <img src="<?php echo esc_url(livesta_get_theme_image_url('news-default-thumb.png')); ?>" alt="<?php echo esc_attr($item['title']); ?>" loading="lazy">
                                </div>
                                <div class="related-post-body">
                                    <div class="meta">
                                        <span class="date"><?php echo esc_html($item['date']); ?></span>
                                        <span class="news-cat"><?php echo esc_html($item['category']); ?></span>
                                    </div>
                                    <h3><?php echo esc_html($item['title']); ?></h3>
                                </div>
                            </article>
                            <?php
                        endforeach;
                    endif;
                    ?>
                </div>
            </div>
        </section>

        <?php livesta_render_common_cta(); ?>
    </main>
    <?php
endwhile;

get_footer();
