<?php get_header();?>

<section class="page-wrap">

	<div class="container">

		<section class="row">

		<div class="col-lg-9" >
		<div class = "bigbox">
		<div class = "smallbox">		
			<h1 class = "site-title">Doctor's Connect - Malaysia </h1>
			<h4 class = "site-tagline">Caring with Passion!</h4>
		</div>
		</div>
			<?php get_template_part('includes/section','content');?>

			</div>


		<div class="col-lg-3" >

	    		<div class=card>

	    			<div class = "card-body">

	        			<?php if( is_active_sidebar('page-sidebar')):?>

	            			<?php dynamic_sidebar('page-sidebar'); ?>

	        			<?php endif; ?>

	        		</div>	

	    		</div>
	    	</div>

	    </section>
	    		        

	</div>
</section>

<?php get_footer();?>  
