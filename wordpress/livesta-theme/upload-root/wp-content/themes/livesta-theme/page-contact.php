<?php
get_header();
$company = livesta_get_company_profile();
$phone_href = livesta_get_company_phone_href();
$mail_address = antispambot($company['mail']);
$mail_href = 'mailto:' . $mail_address;
?>
<main id="content">
    <?php livesta_render_page_hero([
        'eyebrow' => 'Contact',
        'title' => 'お問い合わせ',
        'subtitle' => 'お気軽にご相談ください',
        'mini' => true,
    ]); ?>

    <?php livesta_render_breadcrumb(); ?>

    <section class="content-section">
        <div class="section-inner">
            <div class="contact-method-grid">
                <article class="contact-method-card">
                    <p class="icon">📞</p>
                    <h3>お電話</h3>
                    <p><a class="phone-link" href="<?php echo esc_url($phone_href); ?>"><?php echo esc_html($company['phone']); ?></a></p>
                    <p><?php echo esc_html($company['hours']); ?> / <?php echo esc_html($company['closed']); ?></p>
                </article>
                <article class="contact-method-card">
                    <p class="icon">✉️</p>
                    <h3>メール</h3>
                    <p><a class="office-link" href="<?php echo esc_url($mail_href); ?>"><?php echo esc_html($mail_address); ?></a><br><?php echo esc_html($company['response_time']); ?></p>
                </article>
                <article class="contact-method-card">
                    <p class="icon">📍</p>
                    <h3>ご来店</h3>
                    <p><?php echo esc_html($company['postal_code'] . ' ' . $company['address']); ?><br><?php echo esc_html($company['office_note']); ?></p>
                </article>
            </div>
        </div>
    </section>

    <section class="content-section cream">
        <div class="section-inner">
            <div class="contact-form-wrap">
                <p class="section-eyebrow">Form</p>
                <h2 class="section-heading">お問い合わせフォーム</h2>
                <p class="sub-desc">以下のフォームに必要事項をご入力の上、送信してください。「*」は必須項目です。</p>

                <?php
                $content = get_post_field('post_content', get_the_ID());
                if ($content && has_shortcode($content, 'contact-form-7')) {
                    the_content();
                } else {
                    livesta_render_fallback_form();
                }
                ?>
            </div>
        </div>
    </section>

    <section class="content-section">
        <div class="section-inner">
            <div class="contact-office-grid">
                <div class="contact-office">
                    <p class="section-eyebrow">Office</p>
                    <h3>本社</h3>
                    <p>
                        <?php echo esc_html($company['company_name']); ?><br>
                        <?php echo esc_html($company['postal_code'] . ' ' . $company['address']); ?>
                    </p>
                    <p>
                        TEL: <a class="office-link" href="<?php echo esc_url($phone_href); ?>"><?php echo esc_html($company['phone']); ?></a><br>
                        FAX: <?php echo esc_html($company['fax']); ?><br>
                        MAIL: <a class="office-link" href="<?php echo esc_url($mail_href); ?>"><?php echo esc_html($mail_address); ?></a><br>
                        受付時間: <?php echo esc_html($company['hours']); ?> / <?php echo esc_html($company['closed']); ?>
                    </p>
                </div>
                <div class="contact-office-stack">
                    <div class="office-map-card">
                        <iframe src="<?php echo esc_url(livesta_get_company_map_embed_url()); ?>" loading="lazy" referrerpolicy="no-referrer-when-downgrade" allowfullscreen title="<?php echo esc_attr($company['company_name'] . 'の所在地地図'); ?>"></iframe>
                    </div>
                    <div class="office-access-card">
                        <p class="section-eyebrow">Access</p>
                        <h3>本社アクセス</h3>
                        <ul class="office-access-list">
                            <li>
                                <span>住所</span>
                                <?php echo esc_html($company['postal_code'] . ' ' . $company['address']); ?>
                            </li>
                            <li>
                                <span>アクセス</span>
                                <?php echo esc_html($company['access']); ?>
                            </li>
                            <li>
                                <span>受付時間</span>
                                <?php echo esc_html($company['hours'] . ' / ' . $company['closed']); ?>
                            </li>
                        </ul>
                        <p class="office-access-note"><?php echo esc_html($company['office_note']); ?></p>
                        <a class="access-visual-link" href="<?php echo esc_url(livesta_get_company_map_url()); ?>" target="_blank" rel="noopener noreferrer">Google Mapsで開く<?php echo livesta_svg_arrow(14); ?></a>
                    </div>
                </div>
            </div>
        </div>
    </section>
</main>
<?php
get_footer();
