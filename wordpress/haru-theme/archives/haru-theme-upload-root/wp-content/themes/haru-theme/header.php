<?php
$is_front = is_front_page();
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
<header class="site-header <?php echo $is_front ? '' : 'scrolled subpage'; ?>" id="site-header">
    <a class="site-logo" href="<?php echo esc_url(home_url('/')); ?>">HARU</a>
    <button class="menu-toggle" type="button" aria-expanded="false" aria-controls="site-nav" aria-label="Open navigation">
        <span class="menu-toggle-line"></span>
        <span class="menu-toggle-line"></span>
        <span class="menu-toggle-line"></span>
        <span class="menu-toggle-label">Menu</span>
    </button>
    <nav class="site-nav" id="site-nav" aria-label="Primary navigation">
        <a href="<?php echo esc_url(home_url('/concept/')); ?>" class="<?php echo is_page('concept') ? 'active' : ''; ?>">Concept</a>
        <a href="<?php echo esc_url(home_url('/menu/')); ?>" class="<?php echo is_page('menu') ? 'active' : ''; ?>">Menu</a>
        <a href="<?php echo esc_url(home_url('/access/')); ?>" class="<?php echo is_page('access') ? 'active' : ''; ?>">Access</a>
        <a href="<?php echo esc_url(haru_get_news_url()); ?>" class="<?php echo (is_home() || is_archive() || is_single()) ? 'active' : ''; ?>">News</a>
        <a href="<?php echo esc_url(home_url('/contact/')); ?>" class="nav-cta <?php echo (is_page('contact') || is_page('thanks')) ? 'active' : ''; ?>">Contact</a>
    </nav>
</header>
