<?php $profile = tpi_get_company_profile(); ?>
<footer class="site-footer">
    <div class="section-inner footer-inner">
        <div class="footer-brand">
            <div class="footer-logo"><?php echo esc_html($profile['site_short_name']); ?></div>
            <p><?php echo esc_html($profile['address']); ?></p>
        </div>
        <div class="footer-links">
            <a href="<?php echo esc_url(home_url('/company/')); ?>">会社概要</a>
            <a href="<?php echo esc_url(home_url('/products/')); ?>">製品紹介</a>
            <a href="<?php echo esc_url(home_url('/contact/')); ?>">お問い合わせ</a>
            <a href="<?php echo esc_url(home_url('/privacy/')); ?>">プライバシーポリシー</a>
        </div>
        <div class="footer-copy">&copy; <?php echo esc_html(wp_date('Y')); ?> Tokyo Precision Industry Co., Ltd.</div>
    </div>
</footer>
<?php wp_footer(); ?>
</body>
</html>

