<?php
get_header();

$news_url = livesta_get_news_page_url();
$news_cat = get_category_by_slug('news');
$property_cat = get_category_by_slug('property');
$media_cat = get_category_by_slug('media');

$news_cat_url = $news_cat ? get_category_link($news_cat) : home_url('/category/news/');
$property_cat_url = $property_cat ? get_category_link($property_cat) : home_url('/category/property/');
$media_cat_url = $media_cat ? get_category_link($media_cat) : home_url('/category/media/');

$current_slug = is_category() ? (string) get_queried_object()->slug : 'all';
?>
<main id="content">
    <?php livesta_render_page_hero([
        'eyebrow' => 'News',
        'title' => 'お知らせ',
        'subtitle' => '最新の情報をお届けします',
        'mini' => true,
        'class' => 'news-hero',
    ]); ?>

    <?php livesta_render_breadcrumb(); ?>

    <section class="category-tabs-wrap">
        <div class="category-tabs">
            <a class="category-tab <?php echo ($current_slug === 'all') ? 'active' : ''; ?>" href="<?php echo esc_url($news_url); ?>">すべて</a>
            <a class="category-tab <?php echo ($current_slug === 'news') ? 'active' : ''; ?>" href="<?php echo esc_url($news_cat_url); ?>">お知らせ</a>
            <a class="category-tab <?php echo ($current_slug === 'property') ? 'active' : ''; ?>" href="<?php echo esc_url($property_cat_url); ?>">物件情報</a>
            <a class="category-tab <?php echo ($current_slug === 'media') ? 'active' : ''; ?>" href="<?php echo esc_url($media_cat_url); ?>">メディア</a>
        </div>
    </section>

    <section class="content-section news-list-section">
        <div class="section-inner narrow">
            <div class="news-list">
                <?php if (have_posts()) : ?>
                    <?php while (have_posts()) : the_post(); ?>
                        <a href="<?php the_permalink(); ?>" class="news-item">
                            <span class="news-date"><?php echo esc_html(get_the_date('Y.m.d')); ?></span>
                            <span class="news-cat"><?php echo esc_html(livesta_get_primary_category_name(get_the_ID())); ?></span>
                            <span class="news-title"><?php the_title(); ?></span>
                        </a>
                    <?php endwhile; ?>
                <?php else : ?>
                    <?php
                    $fallback_news = livesta_get_sample_news();

                    if ($current_slug !== 'all') {
                        $fallback_news = array_values(
                            array_filter(
                                $fallback_news,
                                static fn(array $item): bool => livesta_get_sample_news_category_slug($item) === $current_slug
                            )
                        );
                    }
                    ?>
                    <?php foreach ($fallback_news as $item) : ?>
                        <div class="news-item">
                            <span class="news-date"><?php echo esc_html($item['date']); ?></span>
                            <span class="news-cat"><?php echo esc_html($item['category']); ?></span>
                            <span class="news-title"><?php echo esc_html($item['title']); ?></span>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>

            <?php if ($GLOBALS['wp_query']->max_num_pages > 1) : ?>
                <div class="pagination-wrap">
                    <?php
                    echo wp_kses_post(
                        paginate_links(
                            [
                                'type'      => 'list',
                                'prev_text' => '‹',
                                'next_text' => '›',
                            ]
                        )
                    );
                    ?>
                </div>
            <?php endif; ?>
        </div>
    </section>
</main>
<?php
get_footer();
