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
    <a class="site-logo" href="<?php echo esc_url(home_url('/')); ?>">
        東京精密工業
        <span>Tokyo Precision Industry Co., Ltd.</span>
    </a>
    <button class="menu-toggle" type="button" aria-expanded="false" aria-controls="site-nav">Menu</button>
    <nav class="site-nav" id="site-nav" aria-label="Primary navigation">
        <a href="<?php echo esc_url(home_url('/company/')); ?>" class="<?php echo is_page('company') ? 'active' : ''; ?>">会社概要</a>
        <a href="<?php echo esc_url(home_url('/products/')); ?>" class="<?php echo is_page('products') ? 'active' : ''; ?>">製品紹介</a>
        <a href="<?php echo esc_url(home_url('/equipment/')); ?>" class="<?php echo is_page('equipment') ? 'active' : ''; ?>">設備紹介</a>
        <a href="<?php echo esc_url(home_url('/quality/')); ?>" class="<?php echo is_page('quality') ? 'active' : ''; ?>">品質管理</a>
        <a href="<?php echo esc_url(home_url('/recruit/')); ?>" class="<?php echo is_page('recruit') ? 'active' : ''; ?>">採用情報</a>
        <a href="<?php echo esc_url(home_url('/contact/')); ?>" class="nav-cta <?php echo (is_page('contact') || is_page('thanks')) ? 'active' : ''; ?>">お問い合わせ</a>
    </nav>
</header>

