<?php
$company = livesta_get_company_profile();
$phone_href = livesta_get_company_phone_href();
?>
<footer class="footer">
    <div class="footer-inner">
        <div class="footer-logo">LIVESTA</div>
        <div class="footer-links">
            <a href="<?php echo esc_url(home_url('/about/')); ?>">会社概要</a>
            <a href="<?php echo esc_url(home_url('/privacy/')); ?>">プライバシーポリシー</a>
            <a href="<?php echo esc_url(home_url('/sitemap/')); ?>">サイトマップ</a>
        </div>
        <div class="footer-copy-wrap">
            <div class="footer-copy">&copy; <?php echo esc_html(wp_date('Y')); ?> LIVESTA Inc. All rights reserved.</div>
            <div class="footer-note"><?php echo esc_html($company['address']); ?> / <a href="<?php echo esc_url($phone_href); ?>"><?php echo esc_html($company['phone']); ?></a></div>
        </div>
    </div>
</footer>
<?php wp_footer(); ?>
</body>
</html>
