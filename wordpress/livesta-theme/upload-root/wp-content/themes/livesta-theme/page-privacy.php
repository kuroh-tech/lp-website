<?php
get_header();
$company = livesta_get_company_profile();
$phone_href = livesta_get_company_phone_href();
?>
<main id="content">
    <?php livesta_render_page_hero([
        'title' => 'プライバシーポリシー',
        'mini' => true,
    ]); ?>

    <?php livesta_render_breadcrumb(); ?>

    <section class="content-section">
        <div class="legal-content">
            <p>LIVESTA（以下「当社」といいます）は、お客様の個人情報の保護を重要な責務と考え、以下の方針に基づき個人情報の適切な取り扱いと保護に努めます。</p>

            <h2>1. 個人情報の定義</h2>
            <p>個人情報とは、氏名、住所、電話番号、メールアドレス等、特定の個人を識別できる情報をいいます。</p>

            <h2>2. 個人情報の収集</h2>
            <p>当社は、以下の場合に個人情報を収集することがあります。</p>
            <ul>
                <li>お問い合わせフォームからのご連絡時</li>
                <li>物件のご相談・お申し込み時</li>
                <li>売却査定のご依頼時</li>
                <li>セミナー・イベントへのお申し込み時</li>
                <li>その他、当社サービスのご利用時</li>
            </ul>

            <h2>3. 個人情報の利用目的</h2>
            <p>収集した個人情報は、以下の目的のために利用いたします。</p>
            <ul>
                <li>お問い合わせへの回答およびご連絡</li>
                <li>物件情報のご案内</li>
                <li>不動産取引に関する各種手続き</li>
                <li>当社サービスに関する情報提供</li>
                <li>サービスの改善および新サービスの開発</li>
                <li>法令に基づく場合</li>
            </ul>

            <h2>4. 個人情報の第三者提供</h2>
            <p>当社は、以下の場合を除き、お客様の個人情報を第三者に提供いたしません。</p>
            <ul>
                <li>お客様の同意がある場合</li>
                <li>法令に基づく場合</li>
                <li>人の生命、身体または財産の保護のために必要がある場合</li>
                <li>不動産取引の遂行に必要な範囲で、関係事業者に提供する場合</li>
            </ul>

            <h2>5. 個人情報の管理</h2>
            <p>当社は、収集した個人情報について、不正アクセス、紛失、改ざん、漏洩等を防止するため、適切な安全管理措置を講じます。</p>

            <h2>6. 個人情報の開示・訂正・削除</h2>
            <p>お客様ご本人から個人情報の開示、訂正、削除等のご請求があった場合は、ご本人確認の上、合理的な期間内に対応いたします。</p>

            <h2>7. Cookieの使用について</h2>
            <p>当社のウェブサイトでは、利便性の向上およびアクセス分析のためにCookieを使用しています。ブラウザの設定によりCookieを無効にすることが可能ですが、一部の機能がご利用いただけなくなる場合があります。</p>

            <h2>8. アクセス解析ツールの利用</h2>
            <p>当社のウェブサイトでは、アクセス解析ツールを導入する場合があります。解析用データは、Cookie等を通じて匿名で収集され、個人を特定するものではありません。</p>

            <h2>9. プライバシーポリシーの変更</h2>
            <p>当社は、必要に応じて本ポリシーを変更することがあります。変更後のプライバシーポリシーは、当社ウェブサイトに掲載した時点から効力を生じるものとします。</p>

            <h2>10. お問い合わせ窓口</h2>
            <p>
                <?php echo esc_html($company['company_name']); ?><br>
                <?php echo esc_html($company['postal_code'] . ' ' . $company['address']); ?><br>
                TEL: <a href="<?php echo esc_url($phone_href); ?>"><?php echo esc_html($company['phone']); ?></a><br>
                受付時間: <?php echo esc_html($company['hours']); ?> / <?php echo esc_html($company['closed']); ?><br>
                お問い合わせフォームからもご連絡いただけます。<br><br>
                最終改定日: <?php echo esc_html($company['privacy_updated']); ?>
            </p>
        </div>
    </section>
</main>
<?php
get_footer();
