<?php
get_header();
?>
<main>
    <?php tpi_render_page_hero('Top', '東京精密工業', 'トップページの表示に問題がある場合は、固定ページ設定をご確認ください。', true); ?>
    <section class="section-light">
        <div class="section-inner prose-block narrow">
            <p><a href="<?php echo esc_url(home_url('/')); ?>">トップページへ戻る</a></p>
        </div>
    </section>
</main>
<?php
get_footer();

