<?php
$active_filters = livesta_get_property_filters();
$area_options = livesta_get_property_area_options();
$type_options = livesta_get_property_type_options();
$price_options = livesta_get_property_price_options();
$layout_options = livesta_get_property_layout_options();
$property_items = [];
$property_posts = get_posts(
    [
        'post_type'      => 'property',
        'post_status'    => 'publish',
        'posts_per_page' => -1,
        'orderby'        => 'date',
        'order'          => 'DESC',
    ]
);

if (!empty($property_posts)) {
    foreach ($property_posts as $property_post) {
        $post_id = (int) $property_post->ID;
        $area_terms = wp_get_post_terms($post_id, 'property_area', ['fields' => 'names']);
        $type_terms = wp_get_post_terms($post_id, 'property_type', ['fields' => 'names']);

        $item = [
            'post_id' => $post_id,
            'permalink' => get_permalink($post_id),
            'title' => get_the_title($post_id),
            'badge' => livesta_get_property_meta($post_id, 'badge', ''),
            'location' => livesta_get_property_meta($post_id, 'location', '都心6エリア'),
            'price' => livesta_get_property_meta($post_id, 'price', '価格はお問い合わせください'),
            'layout' => livesta_get_property_meta($post_id, 'layout', 'ー'),
            'area' => livesta_get_property_meta($post_id, 'area_size', 'ー'),
            'station' => livesta_get_property_meta($post_id, 'station', '駅情報はお問い合わせください'),
            'built_date' => livesta_get_property_meta($post_id, 'built_date', '築年情報はお問い合わせください'),
            'area_term' => !empty($area_terms) && !is_wp_error($area_terms) ? (string) $area_terms[0] : '',
            'type' => !empty($type_terms) && !is_wp_error($type_terms) ? (string) $type_terms[0] : livesta_get_property_meta($post_id, 'property_kind', ''),
        ];

        if (livesta_property_item_matches_filters($item, $active_filters)) {
            $property_items[] = $item;
        }
    }
} else {
    foreach (livesta_get_sample_properties() as $item) {
        $item['post_id'] = 0;
        $item['permalink'] = '';

        if (livesta_property_item_matches_filters($item, $active_filters)) {
            $property_items[] = $item;
        }
    }
}

get_header();
?>
<main id="content">
    <?php livesta_render_page_hero([
        'eyebrow' => 'Property',
        'title' => '物件情報',
        'subtitle' => '都心6エリアの掲載物件をご紹介しています',
        'class' => 'property-hero',
    ]); ?>

    <?php livesta_render_breadcrumb(); ?>

    <section class="property-filter-area">
        <form class="filter-form" action="<?php echo esc_url(livesta_get_property_archive_url()); ?>" method="get" aria-label="物件検索フィルター">
            <div class="filter-item">
                <label for="area">エリア</label>
                <select id="area" name="area">
                    <option value="">すべて</option>
                    <?php foreach ($area_options as $option) : ?>
                        <option value="<?php echo esc_attr($option); ?>" <?php selected($active_filters['area'], $option); ?>><?php echo esc_html($option); ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="filter-item">
                <label for="type">種別</label>
                <select id="type" name="type">
                    <option value="">すべて</option>
                    <?php foreach ($type_options as $option) : ?>
                        <option value="<?php echo esc_attr($option); ?>" <?php selected($active_filters['type'], $option); ?>><?php echo esc_html($option); ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="filter-item">
                <label for="price">価格帯</label>
                <select id="price" name="price">
                    <option value="">すべて</option>
                    <?php foreach ($price_options as $value => $label) : ?>
                        <option value="<?php echo esc_attr($value); ?>" <?php selected($active_filters['price'], $value); ?>><?php echo esc_html($label); ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="filter-item">
                <label for="layout">間取り</label>
                <select id="layout" name="layout">
                    <option value="">すべて</option>
                    <?php foreach ($layout_options as $option) : ?>
                        <option value="<?php echo esc_attr($option); ?>" <?php selected($active_filters['layout'], $option); ?>><?php echo esc_html($option); ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="filter-item">
                <button class="filter-submit" type="submit">この条件で検索</button>
            </div>
        </form>
    </section>

    <section class="content-section property-list-section">
        <div class="section-inner">
            <div class="property-result-bar">
                <p class="property-result-summary">該当物件 <?php echo esc_html((string) count($property_items)); ?> 件</p>
                <?php if (array_filter($active_filters)) : ?>
                    <a class="property-reset-link" href="<?php echo esc_url(livesta_get_property_archive_url()); ?>">条件をリセット</a>
                <?php endif; ?>
            </div>
            <div class="property-list-grid">
                <?php if (!empty($property_items)) : ?>
                    <?php foreach ($property_items as $item) : ?>
                        <article class="property-card">
                            <?php if ($item['permalink'] !== '') : ?>
                            <a href="<?php echo esc_url($item['permalink']); ?>">
                            <?php endif; ?>
                                <div class="property-thumb">
                                    <?php if ($item['badge'] !== '') : ?>
                                        <span class="property-tag"><?php echo esc_html($item['badge']); ?></span>
                                    <?php endif; ?>
                                    <?php if ((int) $item['post_id'] > 0) : ?>
                                        <?php livesta_render_property_image((int) $item['post_id'], (string) $item['title']); ?>
                                    <?php else : ?>
                                        <img src="<?php echo esc_url(livesta_get_theme_image_url((string) $item['image'])); ?>" alt="<?php echo esc_attr((string) $item['title']); ?>" loading="lazy">
                                    <?php endif; ?>
                                </div>
                                <div class="property-body">
                                    <h3><?php echo esc_html((string) $item['title']); ?></h3>
                                    <p class="location"><?php echo esc_html((string) $item['location']); ?></p>
                                    <div class="property-info">
                                        <span class="price"><?php echo esc_html((string) $item['price']); ?></span>
                                        <span><?php echo esc_html((string) $item['layout']); ?></span>
                                        <span><?php echo esc_html((string) $item['area']); ?></span>
                                    </div>
                                    <p class="property-extra"><?php echo esc_html((string) $item['built_date']); ?><br><?php echo esc_html((string) $item['station']); ?></p>
                                    <?php if ($item['permalink'] !== '') : ?>
                                        <span class="card-link">詳細を見る <?php echo livesta_svg_arrow(14); ?></span>
                                    <?php endif; ?>
                                </div>
                            <?php if ($item['permalink'] !== '') : ?>
                            </a>
                            <?php endif; ?>
                        </article>
                    <?php endforeach; ?>
                <?php else : ?>
                    <div class="property-empty-state">
                        <h2>条件に合う物件は見つかりませんでした</h2>
                        <p>条件を変更するか、お問い合わせフォームからご希望をお知らせください。</p>
                        <a class="primary-btn" href="<?php echo esc_url(home_url('/contact/')); ?>">条件を相談する <?php echo livesta_svg_arrow(); ?></a>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </section>

    <?php livesta_render_common_cta(); ?>
</main>
<?php
get_footer();
