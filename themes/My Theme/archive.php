<?php get_header(); ?>
<div class="container"> 

    <section class="row">

        <div class="col-lg-9">

            <h1> <?php echo single_cat_title(); ?> </h1>

            <?php get_template_part('includes/section', 'archive'); ?>

            <?php previous_posts_link(); ?>
            <?php next_posts_link(); ?>

        </div>


        <div class="col-lg-3" >
    		<div class=card>
    			<div class = "card-body">
        			<?php if( is_active_sidebar('page-sidebar') ) : ?>
            			<?php dynamic_sidebar('page-sidebar'); ?>
        			<?php endif; ?>
        		</div>	
    		</div>
    	</div>

    </section>

</div>


<?php get_footer(); ?>
