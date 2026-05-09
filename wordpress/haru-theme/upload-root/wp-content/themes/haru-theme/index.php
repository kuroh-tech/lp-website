<?php
get_header();
?>
<main>
    <?php haru_render_page_hero('Top', 'HARU COFFEE', 'トップページの表示に問題がある場合は、固定ページ設定をご確認ください。', true); ?>
    <section>
        <div class="section-inner narrow-box centered">
            <a class="button button-primary" href="<?php echo esc_url(home_url('/')); ?>">トップページへ戻る</a>
        </div>
    </section>
</main>
<?php
get_footer();

