<?php $profile = haru_get_profile(); ?>
<footer class="site-footer">
    <div class="section-inner footer-inner">
        <div class="footer-logo"><?php echo esc_html($profile['site_name']); ?></div>
        <div class="footer-links">
            <a href="<?php echo esc_url(home_url('/menu/')); ?>">メニュー</a>
            <a href="<?php echo esc_url(home_url('/access/')); ?>">店舗情報</a>
            <a href="<?php echo esc_url(home_url('/privacy/')); ?>">プライバシーポリシー</a>
        </div>
        <div class="footer-copy">&copy; <?php echo esc_html(wp_date('Y')); ?> HARU COFFEE.</div>
    </div>
</footer>
<?php wp_footer(); ?>
</body>
</html>

