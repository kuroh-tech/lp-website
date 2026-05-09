<?php
get_header();
?>
<main id="content">
    <?php livesta_render_breadcrumb(); ?>

    <section class="content-section">
        <div class="not-found">
            <p class="code">404</p>
            <h1>ページが見つかりませんでした</h1>
            <p>お探しのページは存在しないか、移動した可能性があります。</p>
            <a href="<?php echo esc_url(home_url('/')); ?>" class="primary-btn">
                トップページに戻る
                <?php echo livesta_svg_arrow(); ?>
            </a>
        </div>
    </section>
</main>
<?php
get_footer();
