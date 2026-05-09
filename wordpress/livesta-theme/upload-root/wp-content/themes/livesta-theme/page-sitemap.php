<?php
get_header();

$news_page_url = livesta_get_news_page_url();
$property_archive = livesta_get_property_archive_url();
$news_cat = get_category_by_slug('news');
$property_cat = get_category_by_slug('property');
$media_cat = get_category_by_slug('media');
?>
<main id="content">
    <?php livesta_render_page_hero([
        'title' => 'サイトマップ',
        'mini' => true,
    ]); ?>

    <?php livesta_render_breadcrumb(); ?>

    <section class="content-section">
        <div class="sitemap-grid">
            <div class="sitemap-column">
                <h3>会社情報</h3>
                <ul>
                    <li><a href="<?php echo esc_url(home_url('/')); ?>">トップページ</a></li>
                    <li><a href="<?php echo esc_url(home_url('/about/')); ?>">会社概要</a></li>
                    <li><a href="<?php echo esc_url(home_url('/service/')); ?>">事業内容</a></li>
                    <li><a href="<?php echo esc_url(home_url('/contact/')); ?>">お問い合わせ</a></li>
                    <li><a href="<?php echo esc_url(home_url('/privacy/')); ?>">プライバシーポリシー</a></li>
                </ul>
            </div>

            <div class="sitemap-column">
                <h3>物件・お知らせ</h3>
                <ul>
                    <li><a href="<?php echo esc_url($property_archive); ?>">物件情報</a></li>
                    <li><a href="<?php echo esc_url($news_page_url); ?>">お知らせ</a></li>
                    <li class="child"><a href="<?php echo esc_url($news_cat ? get_category_link($news_cat) : home_url('/category/news/')); ?>">お知らせ（カテゴリ）</a></li>
                    <li class="child"><a href="<?php echo esc_url($property_cat ? get_category_link($property_cat) : home_url('/category/property/')); ?>">物件情報（カテゴリ）</a></li>
                    <li class="child"><a href="<?php echo esc_url($media_cat ? get_category_link($media_cat) : home_url('/category/media/')); ?>">メディア（カテゴリ）</a></li>
                </ul>
            </div>
        </div>
    </section>
</main>
<?php
get_footer();
