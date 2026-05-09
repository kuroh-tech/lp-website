<?php
get_header();
?>
<main>
    <?php while (have_posts()) : the_post(); ?>
        <?php
        $primary = haru_get_primary_news_category(get_post());
        $color = $primary ? haru_get_news_badge_color($primary->slug) : '#6B4226';
        ?>
        <section class="crumbs">
            <div class="section-inner"><a href="<?php echo esc_url(home_url('/')); ?>">TOP</a> &gt; <a href="<?php echo esc_url(haru_get_news_url()); ?>">お知らせ</a> &gt; <?php the_title(); ?></div>
        </section>
        <section class="section-plain article-hero">
            <div class="section-inner narrow">
                <div class="article-meta">
                    <?php if ($primary) : ?>
                        <span class="news-badge" style="background:<?php echo esc_attr($color); ?>"><?php echo esc_html($primary->name); ?></span>
                    <?php endif; ?>
                    <span class="news-date"><?php echo esc_html(get_the_date('Y.m.d')); ?></span>
                </div>
                <h1 class="page-title article-title"><?php the_title(); ?></h1>
            </div>
        </section>
        <section>
            <div class="section-inner prose-block narrow article-body">
                <?php the_content(); ?>
            </div>
        </section>
        <section class="section-plain article-footer">
            <div class="section-inner narrow">
                <?php haru_render_post_navigation(); ?>
            </div>
        </section>
    <?php endwhile; ?>
    <?php haru_render_common_cta(); ?>
</main>
<?php
get_footer();
