<?php if( have_posts() ) : while( have_posts() ) : the_post(); ?>
    
    <div class="card mb-4">
        <div class="card-body d-flex justify-content-center align-items-center">

            <?php if(has_post_thumbnail()): ?>
                <img src="<?php the_post_thumbnail_url('blog-small'); ?>" class="img-fluid mr-3 img-thumbnail" alt="<?php the_title(); ?>">
            <?php endif; ?>

            <div class="blog-content">
                <h3><?php the_title(); ?></h3>
                <?php the_excerpt(); ?>
                <a href="<?php the_permalink(); ?>" class="btn btn-success">More</a>
            </div>

        </div>
    </div>
    
<?php endwhile; else:  ?>

    There's no results for "<?php echo get_search_query(); ?>" 🤷🏻‍♂️

<?php endif; ?>