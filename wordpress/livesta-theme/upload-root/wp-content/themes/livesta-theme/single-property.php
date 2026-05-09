<?php
get_header();

while (have_posts()) :
    the_post();

    $post_id = get_the_ID();
    $title = get_the_title();
    $badge = livesta_get_property_meta($post_id, 'badge', '');
    $location = livesta_get_property_meta($post_id, 'location', '渋谷区代官山町');
    $description = livesta_get_property_meta($post_id, 'description', '駅徒歩圏の利便性と落ち着いた住環境を兼ね備えた住戸です。資金計画から内見調整まで、検討初期のご相談にも丁寧に対応します。');

    $price = livesta_get_property_meta($post_id, 'price', '8,980万円');
    $layout = livesta_get_property_meta($post_id, 'layout', '2LDK');
    $area_size = livesta_get_property_meta($post_id, 'area_size', '72.4㎡');
    $balcony_size = livesta_get_property_meta($post_id, 'balcony_size', '12.8㎡');
    $floor = livesta_get_property_meta($post_id, 'floor', '8階 / 12階建');
    $built_date = livesta_get_property_meta($post_id, 'built_date', '2024年3月');
    $station = livesta_get_property_meta($post_id, 'station', '東急東横線「代官山」駅 徒歩4分');
    $staff_comment = livesta_get_property_meta($post_id, 'staff_comment', '都心アクセスと住環境のバランスが良く、初めての住み替え相談にもご案内しやすい住戸です。内見前の比較ポイントも整理してご説明します。');
    $feature_list = array_filter(
        array_map(
            'trim',
            explode('/', livesta_get_property_meta($post_id, 'features', 'オートロック / 宅配ボックス / 床暖房 / 食器洗浄乾燥機 / 浴室乾燥機 / ペット相談'))
        )
    );

    $detail_rows = [
        '物件種別' => livesta_get_property_meta($post_id, 'property_kind', '中古マンション'),
        '所在地' => livesta_get_property_meta($post_id, 'address_full', '東京都渋谷区代官山町'),
        '交通' => livesta_get_property_meta($post_id, 'traffic', '東急東横線「代官山」駅 徒歩4分 / JR山手線「恵比寿」駅 徒歩8分'),
        '価格' => $price,
        '管理費' => livesta_get_property_meta($post_id, 'management_fee', '月額 19,800円'),
        '修繕積立金' => livesta_get_property_meta($post_id, 'repair_fund', '月額 14,200円'),
        '間取り' => $layout,
        '専有面積' => $area_size,
        'バルコニー面積' => $balcony_size,
        '所在階' => $floor,
        '構造' => livesta_get_property_meta($post_id, 'structure', '鉄筋コンクリート造'),
        '築年月' => $built_date,
        '総戸数' => livesta_get_property_meta($post_id, 'total_units', '42戸'),
        '駐車場' => livesta_get_property_meta($post_id, 'parking', '空き状況はお問い合わせください'),
        '土地権利' => livesta_get_property_meta($post_id, 'right_type', '所有権'),
        '引渡時期' => livesta_get_property_meta($post_id, 'handover_time', '即入居可'),
        '取引態様' => livesta_get_property_meta($post_id, 'transaction_type', '仲介'),
    ];

    $gallery_ids = [];
    if (has_post_thumbnail($post_id)) {
        $gallery_ids[] = get_post_thumbnail_id($post_id);
    }

    $gallery_meta = get_post_meta($post_id, 'gallery', true);
    if (is_string($gallery_meta) && strpos($gallery_meta, ',') !== false) {
        $gallery_meta = array_map('intval', explode(',', $gallery_meta));
    }

    if (is_array($gallery_meta)) {
        foreach ($gallery_meta as $item) {
            if (is_numeric($item)) {
                $gallery_ids[] = (int) $item;
            } elseif (is_array($item) && isset($item['ID'])) {
                $gallery_ids[] = (int) $item['ID'];
            }
        }
    }

    $gallery_ids = array_values(array_unique(array_filter($gallery_ids)));
    $default_gallery_files = [
        'property-01-detail-01.png',
        'property-01-detail-02.png',
        'property-01-detail-03.png',
        'property-01-detail-04.png',
    ];
    ?>
    <main id="content">
        <?php livesta_render_breadcrumb(); ?>

        <section class="content-section property-single-wrap">
            <div class="section-inner">
                <div class="property-gallery-main">
                    <?php if (!empty($gallery_ids)) : ?>
                        <?php echo wp_get_attachment_image($gallery_ids[0], 'large', false, ['data-main-gallery' => '1']); ?>
                    <?php else : ?>
                        <img src="<?php echo esc_url(livesta_get_theme_image_url($default_gallery_files[0])); ?>" alt="<?php echo esc_attr($title); ?>" data-main-gallery="1" loading="lazy">
                    <?php endif; ?>
                </div>

                <?php if (count($gallery_ids) > 1) : ?>
                    <div class="property-thumbs">
                        <?php foreach ($gallery_ids as $index => $gallery_id) :
                            $thumb_url = wp_get_attachment_image_url($gallery_id, 'medium_large');
                            if (!$thumb_url) {
                                continue;
                            }
                            ?>
                            <button class="thumb-btn <?php echo $index === 0 ? 'active' : ''; ?>" type="button" data-gallery-thumb="1" data-src="<?php echo esc_url($thumb_url); ?>" data-alt="<?php echo esc_attr($title); ?>">
                                <?php echo wp_get_attachment_image($gallery_id, 'thumbnail'); ?>
                            </button>
                        <?php endforeach; ?>
                    </div>
                <?php elseif (empty($gallery_ids)) : ?>
                    <div class="property-thumbs">
                        <?php foreach ($default_gallery_files as $index => $filename) :
                            $image_url = livesta_get_theme_image_url($filename);
                            ?>
                            <button class="thumb-btn <?php echo $index === 0 ? 'active' : ''; ?>" type="button" data-gallery-thumb="1" data-src="<?php echo esc_url($image_url); ?>" data-alt="<?php echo esc_attr($title); ?>">
                                <img src="<?php echo esc_url($image_url); ?>" alt="<?php echo esc_attr($title); ?>" loading="lazy">
                            </button>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
        </section>

        <section class="content-section">
            <div class="section-inner">
                <div class="property-summary-grid">
                    <div class="property-main">
                        <?php if ($badge !== '') : ?>
                            <span class="property-tag"><?php echo esc_html($badge); ?></span>
                        <?php endif; ?>
                        <h1><?php echo esc_html($title); ?></h1>
                        <p class="location"><?php echo esc_html($location); ?></p>
                        <p class="detail-lead"><?php echo esc_html($description); ?></p>
                    </div>

                    <aside class="property-info-box">
                        <p class="info-price"><?php echo esc_html($price); ?></p>
                        <ul class="info-list">
                            <li>間取り: <?php echo esc_html($layout); ?></li>
                            <li>専有面積: <?php echo esc_html($area_size); ?></li>
                            <li>バルコニー面積: <?php echo esc_html($balcony_size); ?></li>
                            <li>所在階: <?php echo esc_html($floor); ?></li>
                            <li>築年月: <?php echo esc_html($built_date); ?></li>
                            <li>最寄駅: <?php echo esc_html($station); ?></li>
                        </ul>
                        <a href="<?php echo esc_url(add_query_arg('inquiry_property', $title, home_url('/contact/'))); ?>" class="primary-btn">
                            お問い合わせ
                            <?php echo livesta_svg_arrow(); ?>
                        </a>
                    </aside>
                </div>
            </div>
        </section>

        <section class="content-section cream">
            <div class="section-inner narrow">
                <h2 class="section-heading">物件詳細</h2>
                <table class="property-detail-table">
                    <tbody>
                    <?php foreach ($detail_rows as $label => $value) : ?>
                        <tr>
                            <th><?php echo esc_html($label); ?></th>
                            <td><?php echo esc_html($value); ?></td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </section>

        <section class="content-section">
            <div class="section-inner">
                <div class="property-point-grid">
                    <div class="property-comment-box">
                        <h2 class="section-heading">担当者コメント</h2>
                        <p><?php echo esc_html($staff_comment); ?></p>
                    </div>
                    <div class="property-feature-box">
                        <h2 class="section-heading">設備・特徴</h2>
                        <ul class="property-feature-list">
                            <?php foreach ($feature_list as $feature) : ?>
                                <li><?php echo esc_html($feature); ?></li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                </div>
            </div>
        </section>

        <section class="content-section">
            <div class="section-inner">
                <h2 class="section-heading">周辺環境</h2>
                <div class="nearby-grid">
                    <article class="nearby-card"><p class="cat">買い物</p><p class="name">ピーコックストア代官山店</p><p class="dist">徒歩4分</p></article>
                    <article class="nearby-card"><p class="cat">買い物</p><p class="name">セブン-イレブン恵比寿西2丁目店</p><p class="dist">徒歩3分</p></article>
                    <article class="nearby-card"><p class="cat">医療</p><p class="name">代官山内科クリニック</p><p class="dist">徒歩6分</p></article>
                    <article class="nearby-card"><p class="cat">教育</p><p class="name">区立猿楽小学校</p><p class="dist">徒歩9分</p></article>
                    <article class="nearby-card"><p class="cat">公園</p><p class="name">西郷山公園</p><p class="dist">徒歩7分</p></article>
                    <article class="nearby-card"><p class="cat">金融</p><p class="name">三菱UFJ銀行 恵比寿支店</p><p class="dist">徒歩8分</p></article>
                </div>
            </div>
        </section>

        <?php livesta_render_common_cta([
            'title' => 'この物件のご相談を受け付けています',
            'description' => '内見予約、資金計画の相談、周辺物件との比較など、検討段階に応じてご案内します。',
            'actions' => [
                [
                    'label' => 'この物件を問い合わせ',
                    'url' => add_query_arg(
                        [
                            'inquiry_type' => '物件について',
                            'inquiry_property' => $title,
                        ],
                        home_url('/contact/')
                    ),
                    'variant' => 'primary',
                ],
                [
                    'label' => '内見を予約する',
                    'url' => add_query_arg(
                        [
                            'inquiry_type' => '来店予約',
                            'inquiry_property' => $title,
                        ],
                        home_url('/contact/')
                    ),
                    'variant' => 'secondary',
                ],
                [
                    'label' => '物件一覧へ戻る',
                    'url' => livesta_get_property_archive_url(),
                    'variant' => 'secondary',
                ],
            ],
        ]); ?>

        <section class="content-section cream">
            <div class="section-inner">
                <h2 class="section-heading">この物件に興味がある方へ</h2>
                <div class="related-grid">
                    <?php
                    $area_terms = wp_get_post_terms($post_id, 'property_area', ['fields' => 'ids']);
                    $related_args = [
                        'post_type'      => 'property',
                        'post_status'    => 'publish',
                        'posts_per_page' => 3,
                        'post__not_in'   => [$post_id],
                    ];

                    if (!is_wp_error($area_terms) && !empty($area_terms)) {
                        $related_args['tax_query'] = [
                            [
                                'taxonomy' => 'property_area',
                                'field'    => 'term_id',
                                'terms'    => $area_terms,
                            ],
                        ];
                    }

                    $related_query = new WP_Query($related_args);

                    if ($related_query->have_posts()) :
                        while ($related_query->have_posts()) :
                            $related_query->the_post();
                            $related_id = get_the_ID();
                            ?>
                            <article class="related-card">
                                <a href="<?php the_permalink(); ?>">
                                    <div class="related-thumb"><?php livesta_render_property_image($related_id, get_the_title()); ?></div>
                                    <div class="related-body">
                                        <h3><?php the_title(); ?></h3>
                                        <p class="card-location"><?php echo esc_html(livesta_get_property_meta($related_id, 'location', '都心6エリア')); ?></p>
                                        <div class="card-meta">
                                            <span class="price-text"><?php echo esc_html(livesta_get_property_meta($related_id, 'price', '価格はお問い合わせください')); ?></span>
                                            <span><?php echo esc_html(livesta_get_property_meta($related_id, 'layout', 'ー')); ?></span>
                                            <span><?php echo esc_html(livesta_get_property_meta($related_id, 'area_size', 'ー')); ?></span>
                                        </div>
                                    </div>
                                </a>
                            </article>
                            <?php
                        endwhile;
                        wp_reset_postdata();
                    else :
                        $fallback_properties = array_slice(livesta_get_sample_properties(), 0, 3);
                        foreach ($fallback_properties as $item) :
                            ?>
                            <article class="related-card">
                                <div class="related-thumb">
                                    <img src="<?php echo esc_url(livesta_get_theme_image_url($item['image'])); ?>" alt="<?php echo esc_attr($item['title']); ?>" loading="lazy">
                                </div>
                                <div class="related-body">
                                    <h3><?php echo esc_html($item['title']); ?></h3>
                                    <p class="card-location"><?php echo esc_html($item['location']); ?></p>
                                    <div class="card-meta">
                                        <span class="price-text"><?php echo esc_html($item['price']); ?></span>
                                        <span><?php echo esc_html($item['layout']); ?></span>
                                        <span><?php echo esc_html($item['area']); ?></span>
                                    </div>
                                </div>
                            </article>
                            <?php
                        endforeach;
                    endif;
                    ?>
                </div>
            </div>
        </section>
    </main>
    <?php
endwhile;

get_footer();
