<?php
get_header();
?>
<main>
    <?php haru_render_page_hero('News', 'お知らせ', '', true); ?>
    <section>
        <div class="section-inner centered">
            <div class="filter-row">
                <a href="<?php echo esc_url(haru_get_news_url()); ?>" class="filter-pill <?php echo !is_category() ? 'active' : ''; ?>">すべて</a>
                <?php foreach (haru_get_news_categories() as $category) : ?>
                    <a href="<?php echo esc_url(get_category_link($category)); ?>" class="filter-pill <?php echo is_category($category->term_id) ? 'active' : ''; ?>"><?php echo esc_html($category->name); ?></a>
                <?php endforeach; ?>
            </div>
        </div>
        <div class="section-inner news-list">
            <?php if (have_posts()) : ?>
                <?php while (have_posts()) : the_post(); ?>
                    <?php haru_render_news_row(get_post()); ?>
                <?php endwhile; ?>
                <?php haru_render_posts_pagination(); ?>
            <?php else : ?>
                <article class="info-card">公開中のお知らせはありません。</article>
            <?php endif; ?>
        </div>
    </section>
    <?php haru_render_common_cta(); ?>
</main>
<?php
get_footer();
