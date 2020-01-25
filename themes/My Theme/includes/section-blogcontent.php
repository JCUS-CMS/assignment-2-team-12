<?php if( have_posts() ) : while( have_posts() ) : the_post(); ?>
<section class = "row">

    <div class="col-lg-9">
    <p> <?php echo get_the_date('d/m/Y h:i:s'); ?> </p>


    <?php 
        //the_author();
        $fname = get_the_author_meta('first_name');
        $lname = get_the_author_meta('last_name');
        // echo $fname . ' ' . $lname;
    ?>

    <p> Posted by <strong> <?php echo $fname;?> <?php echo $lname;?> </strong> </p>

    <?php 
    $tags = get_the_tags();
    if($tags) :
    foreach ($tags as $tag) : ?>
        <a href="<?php echo get_tag_link($tag->term_id); ?>" class="badge badge-success">
            <?php echo $tag->name; ?>
        </a>
    <?php endforeach; endif; ?>



    <?php 
    $categories = get_the_category();
    foreach ($categories as $cat) : ?>
        
            <a href="<?php echo get_category_link($cat->term_id); ?>" class="badge badge-pill badge-primary font-weight-bold" >
                <?php echo $cat->name; ?>
            </a>

    <?php endforeach; ?>

    <?php the_content(); ?>

     <?php comment_form(array('comment_notes_after' => '')); ?>
     
</div>

    <div class="col-lg-3" >
            <div class=card>
                <div class = "card-body">
                    <?php if( is_active_sidebar('blog-sidebar') ) : ?>
                        <?php dynamic_sidebar('blog-sidebar'); ?>
                    <?php endif; ?>
                </div>  
            </div>
        </div>
</section>



    
    
<?php endwhile; endif; ?>