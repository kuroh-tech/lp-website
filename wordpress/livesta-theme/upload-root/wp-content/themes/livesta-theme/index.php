<?php
get_header();
?>
<main id="content">
    <?php livesta_render_page_hero(['title' => wp_get_document_title(), 'mini' => true]); ?>
    <?php livesta_render_breadcrumb(); ?>
    <section class="content-section">
        <div class="section-inner narrow">
            <?php if (have_posts()) : ?>
                <?php while (have_posts()) : the_post(); ?>
                    <article <?php post_class('entry-card'); ?>>
                        <h2><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
                        <div class="entry-content"><?php the_excerpt(); ?></div>
                    </article>
                <?php endwhile; ?>
                <div class="pagination-wrap"><?php the_posts_pagination(); ?></div>
            <?php else : ?>
                <p>投稿が見つかりませんでした。</p>
            <?php endif; ?>
        </div>
    </section>
</main>
<?php
get_footer();
