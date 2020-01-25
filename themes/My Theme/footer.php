<footer class="footer-group">

				<div class="section-inner">

					<div class="footer-credits">

						<p class="footer-copyright">&copy;
							<?php
							echo date_i18n(
								
								_x( 'Y', 'copyright date format')
							);
							?>
							<a href="<?php echo esc_url( home_url( '/' ) ); ?>"style="text-decoration: none; color: inherit;"><?php echo bloginfo( 'name' ); ?></a>
						</p>

						<p class="Developed-by-">
							<a href="<?php echo esc_url( __( 'https://github.com/JCUS-CMS/assignment-2-team-12/' ) ); ?>"style="text-decoration: none; color: inherit;">
								<?php _e( 'Developed By CMS_Team 12' ); ?>
							</a>
						</p>

					</div>
				<div class ="topp">
					<a class="to-the-top" href="#top">
						<span class="to-the-top-long" style="text-decoration: none; color: inherit;">
							<?php
							
							printf( __( 'To the top %s' ), '<span class="arrow" >
							<?phparia-hidden="true">&uarr;</span>' );
							?>
						</span>
						
					</a>
				</div>

				</div>


</footer>

	<?php wp_footer();?>
</body	>
<html>