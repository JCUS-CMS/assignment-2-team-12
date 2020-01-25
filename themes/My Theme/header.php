<!DOCTYPE html>
<html lang="en">
<head>

	<meta charset="UTF-8">
	<title></title>


	<?php wp_head();?>


	
</head>
<body>


<header>


	<div class="container">
		<?php if( has_custom_logo() ) { 
  the_custom_logo(); 
	} else { ?>
	<h1 class="navbar-brand mb-0"><a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home"><?php bloginfo( 'name' ); ?></a></h1>
<?php } ?>
		
		<?php 
		wp_nav_menu(
				array(

					'theme_location' => 'top-menu',
					//'menu' => 'Top Bar'
					'menu_class' => 'top-bar'	
				)
		);
		?>



	</div>

</header>
	
