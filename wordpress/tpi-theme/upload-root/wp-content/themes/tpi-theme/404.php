<?php
get_header();
?>
<main>
    <?php tpi_render_page_hero('404', 'ページが見つかりませんでした', 'お探しのページは存在しないか、移動した可能性があります。', true, '', false); ?>
    <section class="section-light error-section">
        <div class="section-inner narrow-box centered">
            <p class="error-code">404</p>
            <a class="button button-primary" href="<?php echo esc_url(home_url('/')); ?>">トップへ戻る</a>
        </div>
    </section>
</main>
<?php
get_footer();
