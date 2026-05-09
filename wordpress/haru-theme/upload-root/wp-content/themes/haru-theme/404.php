<?php
get_header();
?>
<main>
    <?php haru_render_page_hero('404', 'ページが見つかりません', 'お探しのページは移動または削除された可能性があります。', true); ?>
    <section>
        <div class="section-inner narrow-box centered">
            <a class="button button-primary" href="<?php echo esc_url(home_url('/')); ?>">トップへ戻る</a>
        </div>
    </section>
</main>
<?php
get_footer();

