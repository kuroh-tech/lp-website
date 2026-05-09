<?php
$is_front = is_front_page();
$about_url = home_url('/about/');
$service_url = home_url('/service/');
$property_url = livesta_get_property_archive_url();
$news_url = livesta_get_news_page_url();
$contact_url = home_url('/contact/');
?>
<!doctype html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>
<header class="header <?php echo $is_front ? '' : 'scrolled subpage'; ?>" id="site-header">
    <a href="<?php echo esc_url(home_url('/')); ?>" class="logo">LIVESTA</a>
    <button class="nav-toggle" type="button" aria-expanded="false" aria-controls="site-nav" aria-label="メニューを開く">
        <span class="nav-toggle-line"></span>
        <span class="nav-toggle-line"></span>
        <span class="nav-toggle-line"></span>
        <span class="nav-toggle-text">Menu</span>
    </button>
    <button class="nav-backdrop" type="button" aria-label="メニューを閉じる" hidden></button>
    <nav class="nav" id="site-nav" aria-label="Primary navigation">
        <button class="nav-close" type="button" aria-label="メニューを閉じる">Close</button>
        <a href="<?php echo esc_url($about_url); ?>" class="<?php echo is_page('about') ? 'active' : ''; ?>">About</a>
        <a href="<?php echo esc_url($service_url); ?>" class="<?php echo is_page('service') ? 'active' : ''; ?>">Service</a>
        <a href="<?php echo esc_url($property_url); ?>" class="<?php echo (is_post_type_archive('property') || is_singular('property')) ? 'active' : ''; ?>">Property</a>
        <a href="<?php echo esc_url($news_url); ?>" class="<?php echo (is_home() || is_category() || is_singular('post')) ? 'active' : ''; ?>">News</a>
        <a href="<?php echo esc_url($contact_url); ?>" class="nav-cta <?php echo (is_page('contact') || is_page('thanks')) ? 'active' : ''; ?>">Contact</a>
    </nav>
</header>
