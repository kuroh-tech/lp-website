<?php
get_header();
?>
<main>
    <?php if (have_posts()) : ?>
        <?php while (have_posts()) : the_post(); ?>
            <?php
            $slug = get_post_field('post_name', get_the_ID());
            $profile = haru_get_profile();
            $story = haru_get_story_content();
            $owner = haru_get_owner_profile();
            ?>

            <?php if ($slug === 'concept') : ?>
                <?php haru_render_page_hero('Concept', 'コンセプト', 'ていねいに淹れる、ちいさな幸せ', false, 'concept-hero'); ?>
                <section>
                    <div class="section-inner centered story-block narrow">
                        <p class="section-eyebrow">Story</p>
                        <h2 class="section-title">HARUのはじまり</h2>
                        <p class="story-quote"><?php echo esc_html($story['quote']); ?></p>
                        <div class="story-copy">
                            <?php foreach ($story['body'] as $paragraph) : ?>
                                <p><?php echo esc_html($paragraph); ?></p>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </section>

                <section class="section-accent">
                    <div class="section-inner">
                        <div class="section-header centered reveal">
                            <p class="section-eyebrow">Commitment</p>
                            <h2 class="section-title">HARUの3つのこだわり</h2>
                        </div>
                        <div class="card-grid card-grid-3">
                            <?php foreach (haru_get_commitments() as $item) : ?>
                                <a class="info-card commitment-card centered reveal" href="<?php echo esc_url($item['anchor']); ?>">
                                    <span class="commitment-number"><?php echo esc_html($item['number']); ?></span>
                                    <h3><?php echo esc_html($item['title']); ?></h3>
                                    <p><?php echo esc_html($item['body']); ?></p>
                                </a>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </section>

                <?php foreach (haru_get_concept_sections() as $section) : ?>
                    <?php if ($section['layout'] === 'wide') : ?>
                        <section id="<?php echo esc_attr($section['id']); ?>" class="section-accent concept-wide-section">
                            <div class="section-inner">
                                <div class="section-header centered narrow reveal">
                                    <p class="section-eyebrow"><?php echo esc_html($section['eyebrow']); ?></p>
                                    <h2 class="section-title"><?php echo esc_html($section['title']); ?></h2>
                                    <div class="prose-block centered">
                                        <?php foreach ($section['body'] as $paragraph) : ?>
                                            <p><?php echo esc_html($paragraph); ?></p>
                                        <?php endforeach; ?>
                                    </div>
                                </div>
                                <div class="reveal">
                                    <?php haru_render_placeholder($section['image'], 'wide-banner'); ?>
                                </div>
                            </div>
                        </section>
                    <?php else : ?>
                        <section id="<?php echo esc_attr($section['id']); ?>" class="<?php echo $section['layout'] === 'reverse' ? 'section-plain' : ''; ?>">
                            <div class="section-inner two-column <?php echo $section['layout'] === 'reverse' ? 'reverse' : ''; ?>">
                                <div class="reveal">
                                    <?php haru_render_placeholder($section['image'], 'portrait'); ?>
                                </div>
                                <div class="reveal prose-block">
                                    <p class="section-eyebrow"><?php echo esc_html($section['eyebrow']); ?></p>
                                    <h2 class="section-title"><?php echo esc_html($section['title']); ?></h2>
                                    <?php foreach ($section['body'] as $paragraph) : ?>
                                        <p><?php echo esc_html($paragraph); ?></p>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                        </section>
                    <?php endif; ?>
                <?php endforeach; ?>

                <section>
                    <div class="section-inner two-column">
                        <div class="reveal">
                            <?php haru_render_placeholder('owner-portrait', 'portrait'); ?>
                        </div>
                        <div class="reveal prose-block">
                            <p class="section-eyebrow">Owner</p>
                            <h2 class="section-title"><?php echo esc_html($owner['name']); ?></h2>
                            <p class="meta owner-role"><?php echo esc_html($owner['role']); ?></p>
                            <p><?php echo esc_html($owner['body']); ?></p>
                        </div>
                    </div>
                </section>

                <?php haru_render_common_cta(); ?>

            <?php elseif ($slug === 'menu') : ?>
                <?php $sections = haru_get_menu_sections(); ?>
                <?php haru_render_page_hero('Menu', 'メニュー', '季節とともに変わる味わいを、一杯ずつお届けします', false, 'menu-hero'); ?>
                <section>
                    <div class="section-inner centered prose-block narrow">
                        <p class="section-eyebrow">Our Menu</p>
                        <h2 class="section-title">素材を活かした、シンプルなメニュー</h2>
                        <p>HARUでは、自家焙煎のスペシャルティコーヒーを中心に、素材の味わいを大切にしたメニューをご用意しています。</p>
                        <p>コーヒー豆は季節ごとに産地を厳選し、それぞれの個性が引き立つ焙煎度合いで仕上げています。</p>
                    </div>
                </section>

                <?php foreach (['coffee', 'drink'] as $key) : ?>
                    <?php $section = $sections[$key]; ?>
                    <section class="<?php echo $key === 'drink' ? 'section-accent' : 'section-plain'; ?>" id="<?php echo esc_attr($key); ?>">
                        <div class="section-inner">
                            <div class="section-header reveal">
                                <p class="section-eyebrow"><?php echo esc_html($section['eyebrow']); ?></p>
                                <h2 class="section-title"><?php echo esc_html($section['title']); ?></h2>
                            </div>
                            <div class="menu-list">
                                <?php foreach ($section['items'] as $item) : ?>
                                    <article class="menu-row reveal">
                                        <div>
                                            <h3><?php echo esc_html($item['name']); ?></h3>
                                            <p><?php echo esc_html($item['desc']); ?></p>
                                        </div>
                                        <div class="menu-row-side">
                                            <strong><?php echo esc_html($item['price']); ?></strong>
                                            <span><?php echo esc_html($item['note']); ?></span>
                                        </div>
                                    </article>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    </section>
                <?php endforeach; ?>

                <section id="food">
                    <div class="section-inner">
                        <div class="section-header reveal">
                            <p class="section-eyebrow"><?php echo esc_html($sections['food']['eyebrow']); ?></p>
                            <h2 class="section-title"><?php echo esc_html($sections['food']['title']); ?></h2>
                            <p class="section-desc slim">すべて店内で手づくり。素材にこだわったやさしい味わいです。</p>
                        </div>
                        <div class="card-grid card-grid-2">
                            <?php foreach ($sections['food']['cards'] as $item) : ?>
                                <article class="menu-card reveal">
                                    <?php haru_render_placeholder($item['image'], 'wide'); ?>
                                    <div class="menu-card-body">
                                        <h3><?php echo esc_html($item['title']); ?></h3>
                                        <p><?php echo esc_html($item['body']); ?></p>
                                        <p class="price"><?php echo esc_html($item['price']); ?></p>
                                    </div>
                                </article>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </section>

                <section class="section-accent">
                    <div class="section-inner narrow-box centered menu-note-box">
                        <p class="section-eyebrow">Takeout</p>
                        <h2 class="section-title">テイクアウトもご利用いただけます</h2>
                        <?php foreach (haru_get_takeout_copy() as $paragraph) : ?>
                            <p class="section-desc slim centered"><?php echo esc_html($paragraph); ?></p>
                        <?php endforeach; ?>
                    </div>
                </section>

                <?php haru_render_common_cta(); ?>

            <?php elseif ($slug === 'access') : ?>
                <?php haru_render_page_hero('Access', 'アクセス・店舗情報', '蔵前の路地裏で、お待ちしています', false, 'access-hero'); ?>
                <section>
                    <div class="section-inner narrow-box">
                        <div class="section-header reveal">
                            <p class="section-eyebrow">Shop Info</p>
                            <h2 class="section-title">店舗情報</h2>
                        </div>
                        <table class="data-table reveal">
                            <tbody>
                                <?php foreach (haru_get_access_rows() as $row) : ?>
                                    <tr>
                                        <th><?php echo esc_html($row['label']); ?></th>
                                        <td>
                                            <?php if (!empty($row['href'])) : ?>
                                                <a href="<?php echo esc_url($row['href']); ?>"><?php echo esc_html($row['value']); ?></a>
                                            <?php else : ?>
                                                <?php echo esc_html($row['value']); ?>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </section>

                <section class="section-plain">
                    <div class="section-inner two-column route-layout">
                        <div class="reveal prose-block">
                            <p class="section-eyebrow">Map</p>
                            <h2 class="section-title">アクセス</h2>
                            <div class="route-stack">
                                <?php foreach (haru_get_access_routes() as $route) : ?>
                                    <div class="route-block">
                                        <h3><?php echo esc_html($route['title']); ?></h3>
                                        <ul class="bullet-list route-list">
                                            <?php foreach ($route['items'] as $item) : ?>
                                                <li><?php echo esc_html($item); ?></li>
                                            <?php endforeach; ?>
                                        </ul>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                        <div class="reveal map-frame">
                            <iframe src="<?php echo esc_url(haru_get_map_embed_url()); ?>" loading="lazy" referrerpolicy="no-referrer-when-downgrade" title="HARU COFFEEへのアクセスマップ"></iframe>
                        </div>
                    </div>
                </section>

                <section class="section-accent">
                    <div class="section-inner">
                        <div class="section-header reveal">
                            <p class="section-eyebrow">Inside</p>
                            <h2 class="section-title">店内のご案内</h2>
                        </div>
                        <div class="card-grid card-grid-3">
                            <?php foreach (haru_get_inside_cards() as $item) : ?>
                                <article class="menu-card reveal">
                                    <?php haru_render_placeholder($item['image'], 'wide'); ?>
                                    <div class="menu-card-body">
                                        <h3><?php echo esc_html($item['title']); ?></h3>
                                        <p><?php echo esc_html($item['body']); ?></p>
                                    </div>
                                </article>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </section>

                <section>
                    <div class="section-inner narrow-box centered">
                        <p class="section-eyebrow">Notice</p>
                        <h2 class="section-title">ご来店の前に</h2>
                        <div class="notice-panel">
                            <ul class="notice-box">
                                <?php foreach (haru_get_notices() as $item) : ?>
                                    <li><?php echo esc_html($item); ?></li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                    </div>
                </section>

                <?php haru_render_common_cta(); ?>

            <?php elseif ($slug === 'contact') : ?>
                <?php haru_render_page_hero('Contact', 'お問い合わせ', 'ご質問・取材依頼などお気軽にどうぞ', true); ?>
                <section>
                    <div class="section-inner">
                        <div class="section-header centered reveal">
                            <p class="section-eyebrow">Contact Method</p>
                            <h2 class="section-title">お問い合わせ方法</h2>
                        </div>
                        <div class="card-grid card-grid-2">
                            <?php foreach (haru_get_contact_methods() as $item) : ?>
                                <article class="info-card centered reveal contact-method-card">
                                    <h3><?php echo esc_html($item['title']); ?></h3>
                                    <p><?php echo nl2br(esc_html($item['body'])); ?></p>
                                </article>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </section>

                <section class="section-plain">
                    <div class="section-inner narrow-box">
                        <div class="section-header centered reveal">
                            <p class="section-eyebrow">Form</p>
                            <h2 class="section-title">メールフォーム</h2>
                            <p class="section-desc slim centered">以下のフォームに必要事項をご記入の上、送信してください。デモ環境では送信後に完了ページへ遷移します。</p>
                        </div>
                        <?php haru_render_contact_form(); ?>
                    </div>
                </section>

                <section class="section-accent">
                    <div class="section-inner two-column">
                        <div class="reveal prose-block">
                            <p class="section-eyebrow">Shop Info</p>
                            <h2 class="section-title"><?php echo esc_html($profile['site_name']); ?></h2>
                            <ul class="detail-list">
                                <li><span>住所</span><?php echo esc_html($profile['address']); ?></li>
                                <li><span>電話番号</span><a href="<?php echo esc_url(haru_get_phone_href()); ?>"><?php echo esc_html($profile['phone']); ?></a></li>
                                <li><span>営業時間</span><?php echo esc_html($profile['hours']); ?></li>
                                <li><span>定休日</span><?php echo esc_html($profile['closed']); ?></li>
                            </ul>
                            <div class="inline-actions">
                                <a class="button button-secondary" href="<?php echo esc_url($profile['instagram']); ?>" target="_blank" rel="noreferrer noopener">Instagram DM</a>
                            </div>
                        </div>
                        <div class="reveal map-frame compact">
                            <iframe src="<?php echo esc_url(haru_get_map_embed_url()); ?>" loading="lazy" referrerpolicy="no-referrer-when-downgrade" title="HARU COFFEEへのアクセスマップ"></iframe>
                        </div>
                    </div>
                </section>

            <?php elseif ($slug === 'thanks') : ?>
                <?php haru_render_page_hero('Thanks', '送信が完了しました', 'お問い合わせありがとうございます。3営業日以内に返信いたします。', true); ?>
                <section>
                    <div class="section-inner narrow-box centered">
                        <div class="success-badge">✓</div>
                        <p class="section-desc slim centered">お急ぎの場合はInstagram DMよりご連絡ください。内容確認のうえ、順次ご返信いたします。</p>
                        <div class="inline-actions centered">
                            <a class="button button-primary" href="<?php echo esc_url(home_url('/')); ?>">トップページに戻る</a>
                            <a class="button button-secondary" href="<?php echo esc_url($profile['instagram']); ?>" target="_blank" rel="noreferrer noopener">Instagramを見る</a>
                        </div>
                    </div>
                </section>

            <?php elseif ($slug === 'privacy') : ?>
                <?php haru_render_page_hero('Privacy Policy', 'プライバシーポリシー', '', true); ?>
                <section>
                    <div class="section-inner prose-block narrow">
                        <?php if (trim((string) get_the_content()) !== '') : ?>
                            <?php the_content(); ?>
                        <?php else : ?>
                            <p>HARU COFFEEは、お問い合わせ時にご提供いただいた個人情報を、返信・ご案内・取材調整などの目的にのみ使用します。</p>
                            <p>取得した情報は適切に管理し、法令に基づく場合を除き第三者へ提供しません。お問い合わせ先: <a href="mailto:info@haru-coffee.example">info@haru-coffee.example</a></p>
                        <?php endif; ?>
                    </div>
                </section>

            <?php else : ?>
                <?php haru_render_page_hero('Page', get_the_title(), '', true); ?>
                <section>
                    <div class="section-inner prose-block narrow">
                        <?php the_content(); ?>
                    </div>
                </section>
            <?php endif; ?>
        <?php endwhile; ?>
    <?php endif; ?>
</main>
<?php
get_footer();
