<?php
get_header();

while (have_posts()) :
    the_post();
    ?>
    <main id="content">
        <?php livesta_render_page_hero([
            'title' => get_the_title(),
            'mini' => true,
        ]); ?>

        <?php livesta_render_breadcrumb(); ?>

        <section class="content-section">
            <div class="section-inner narrow">
                <article class="entry-content">
                    <?php the_content(); ?>
                </article>
            </div>
        </section>
    </main>
    <?php
endwhile;

get_footer();
