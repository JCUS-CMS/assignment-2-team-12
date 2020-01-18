<?php if( have_posts() ): while( have_posts() ): the_posts();?>

    <?php the_content();?>

<?php endwhile; else: endif;?>