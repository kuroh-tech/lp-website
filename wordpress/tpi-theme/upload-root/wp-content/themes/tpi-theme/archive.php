<?php
get_header();
?>
<main>
    <?php tpi_render_page_hero('Archive', post_type_archive_title('', false) ?: get_the_archive_title(), get_the_archive_description(), true); ?>
    <section class="section-light">
        <div class="section-inner stack-grid">
            <?php if (have_posts()) : ?>
                <?php while (have_posts()) : the_post(); ?>
                    <article class="info-card reveal">
                        <p class="meta"><?php echo esc_html(get_the_date('Y.m.d')); ?></p>
                        <h2><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
                        <p><?php echo esc_html(wp_trim_words(get_the_excerpt(), 30)); ?></p>
                    </article>
                <?php endwhile; ?>
            <?php else : ?>
                <article class="info-card">
                    <p>公開中の記事はありません。</p>
                </article>
            <?php endif; ?>
        </div>
    </section>
</main>
<?php
get_footer();

