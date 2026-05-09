<?php
get_header();
$submitted = strtoupper($_SERVER['REQUEST_METHOD'] ?? '') === 'POST';
$name = $submitted && isset($_POST['inquiry_name']) ? sanitize_text_field(wp_unslash($_POST['inquiry_name'])) : '';
$type = $submitted && isset($_POST['inquiry_type']) ? sanitize_text_field(wp_unslash($_POST['inquiry_type'])) : '';
$property = $submitted && isset($_POST['inquiry_property']) ? sanitize_text_field(wp_unslash($_POST['inquiry_property'])) : '';
$email = $submitted && isset($_POST['inquiry_email']) ? sanitize_email(wp_unslash($_POST['inquiry_email'])) : '';
$phone_href = livesta_get_company_phone_href();
?>
<main id="content">
    <?php livesta_render_breadcrumb(); ?>

    <section class="content-section">
        <div class="thanks-wrap">
            <div class="thanks-icon">✓</div>
            <h1>送信が完了しました</h1>
            <p>
                お問い合わせいただき、ありがとうございます。<br>
                内容を確認のうえ、<?php echo esc_html(livesta_get_company_value('response_time')); ?><br>
                担当より順次ご連絡いたします。<br>
                しばらく経っても返信がない場合は、<a href="<?php echo esc_url($phone_href); ?>"><?php echo esc_html(livesta_get_company_value('phone')); ?></a> までご連絡ください。
            </p>
            <?php if ($submitted && ($name !== '' || $type !== '' || $property !== '' || $email !== '')) : ?>
                <ul class="thanks-summary">
                    <?php if ($name !== '') : ?>
                        <li><span>お名前</span><?php echo esc_html($name); ?></li>
                    <?php endif; ?>
                    <?php if ($type !== '') : ?>
                        <li><span>お問い合わせ種別</span><?php echo esc_html($type); ?></li>
                    <?php endif; ?>
                    <?php if ($property !== '') : ?>
                        <li><span>対象物件</span><?php echo esc_html($property); ?></li>
                    <?php endif; ?>
                    <?php if ($email !== '') : ?>
                        <li><span>メールアドレス</span><?php echo esc_html($email); ?></li>
                    <?php endif; ?>
                </ul>
            <?php endif; ?>
            <a href="<?php echo esc_url(home_url('/')); ?>" class="primary-btn">
                トップページに戻る
                <?php echo livesta_svg_arrow(); ?>
            </a>
        </div>
    </section>
</main>
<?php
get_footer();
