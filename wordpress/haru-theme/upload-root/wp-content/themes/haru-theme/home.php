<?php
get_header();
?>
<main>
    <?php haru_render_page_hero('News', 'お知らせ', '', true); ?>
    <section>
        <?php haru_render_news_filters(); ?>
        <?php haru_render_news_listing(); ?>
    </section>
    <?php haru_render_common_cta(); ?>
</main>
<?php
get_footer();
