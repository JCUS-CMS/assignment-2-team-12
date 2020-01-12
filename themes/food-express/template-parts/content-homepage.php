<?php
/**
 * Template part for displaying page content in homepage.php.
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package Food Express
 */
$container_class_homepage_content =  get_theme_mod( 'food_express_homepage_container_class', true ) ? 'container' : 'fluid';
?>
<section class="homepage-content <?php echo esc_attr( $container_class_homepage_content ) ; ?>">
	<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
		<div class="entry-content">
			<?php
				the_content();

				wp_link_pages( array(
					'before' => '<div class="page-links">' . esc_html__( 'Pages:', 'food-express' ),
					'after'  => '</div>',
				) );
			?>
		</div><!-- .entry-content -->

		<footer class="entry-footer">
			<?php
				edit_post_link(
					sprintf(
						/* translators: %s: Name of current post */
						esc_html__( 'Edit %s', 'food-express' ),
						the_title( '<span class="screen-reader-text">"', '"</span>', false )
					),
					'<span class="edit-link">',
					'</span>'
				);
			?>
		</footer><!-- .entry-footer -->
	</article><!-- #post-## -->
</section>
