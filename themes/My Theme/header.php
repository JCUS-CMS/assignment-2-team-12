<!DOCTYPE html>
<html lang="en">
<head>

	<meta charset="UTF-8">
	<title>Document</title>


	<?php wp_head();?>


	
</head>
<body>
	
<header>
	<?php
	wp_nav_menu(
		array(
			'top-menu' => 'Top Menu Location',
		'mobile-menu' => 'Mobile Menu Location',
		)
	);
	?>
	
</header>
