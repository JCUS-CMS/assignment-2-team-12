<?php
/*
================================================================================================
Perfect Choice - content.php
================================================================================================
This is the most generic template file in a WordPress theme and is one required files to display
content. This content.php is the main content that will be displayed when is on front page, home
or index.

@package        Perfect Choice WordPress Theme
@copyright      Copyright (C) 2017. Benjamin Lu
@license        GNU General Public License v2 or later (http://www.gnu.org/licenses/gpl-2.0.html)
@author         Benjamin Lu (https://www.benjlu.com/)
================================================================================================
*/
?>
<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
    <?php if (has_post_thumbnail()) { ?>
        <div class="entry-post-thumbnail">
            <?php the_post_thumbnail('one-ark-medium-thumbnails'); ?>
        </div>
    <?php } ?>
    <header class="entry-header">
        <?php the_title(sprintf('<h1 class="entry-title"><a href="%s">', esc_url(get_permalink())), '</a></h1>'); ?>
        <span class="entry-timestamp"><?php echo one_ark_entry_time_stamp_setup(); ?></span>
    </header>
    <div class="entry-excerpt">
        <?php the_excerpt(); ?>
        <div class="continue-reading">
            <a href="<?php echo esc_url( get_permalink() ); ?>" rel="bookmark">
                <?php
                    printf(
                        wp_kses(__('Continue Reading %s', 'one-ark'), array('span' => array('class' => array()))),
                        the_title('<span class="screen-reader-text">"', '"</span>', false)
                    );
                ?>
            </a>
        </div>
    </div>
</article>