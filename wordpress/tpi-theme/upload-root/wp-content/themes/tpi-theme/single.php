<?php
get_header();
?>
<main>
    <?php tpi_render_page_hero('Article', get_the_title(), get_the_date('Y.m.d'), true); ?>
    <section class="section-light">
        <div class="section-inner prose-block narrow">
            <?php while (have_posts()) : the_post(); ?>
                <?php the_content(); ?>
            <?php endwhile; ?>
        </div>
    </section>
</main>
<?php
get_footer();

