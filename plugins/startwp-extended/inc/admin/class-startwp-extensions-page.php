<?php
/* StartWP Extensions Settings Page */
class startwpextensions_Settings_Page {
	public function __construct() {
		add_action( 'admin_menu', array( $this, 'wph_create_settings' ) );
		add_action( 'admin_init', array( $this, 'wph_setup_sections' ) );
		add_action( 'admin_init', array( $this, 'wph_setup_fields' ) );
	}
	public function wph_create_settings() {
		$page_title = 'StartWP Extended';
		$menu_title = 'StartWP Extended';
		$capability = 'manage_options';
		$slug = 'startwpextensions';
		$callback = array($this, 'wph_settings_content');
		add_theme_page($page_title, $menu_title, $capability, $slug, $callback);
	}
	public function wph_settings_content() { ?>
		<div class="swp-wrap">
			<h1>StartWP Theme Extended</h1>
			 <h4><?php _e( '<i>This plugin is in beta right now, please test and provide feedback.</i>', 'start' ); ?></h4>
			<?php settings_errors(); ?>
			<form method="POST" action="options.php">
				<?php
					settings_fields( 'startwpextensions' );
					do_settings_sections( 'startwpextensions' );
					submit_button();
				?>
			</form>
		</div> <?php
	}
	public function wph_setup_sections() {
		add_settings_section( 'startwpextensions_section', 'Enable desired extensions.', array(), 'startwpextensions' );
	}
	public function wph_setup_fields() {
		$fields = array(
			array(
				'label' => 'Woo Customizer - Lite',
				'id' => 'swp_woo',
				'type' => 'radio',
				'section' => 'startwpextensions_section',
				'options' => array(
					'Enable' => 'Enable',
					'Disable' => 'Disable',
				),
				'desc' => 'Once enabled you will see new panels in the customizer specific to WooCommerce. Make sur you have WooCommerce plugin enabled.',
			),
			array(
				'label' => 'EDD Customizer - Lite',
				'id' => 'swp_edd',
				'type' => 'radio',
				'section' => 'startwpextensions_section',
				'options' => array(
					'Enable' => 'Enable',
					'Disable' => 'Disable',
				),
				'desc' => 'Once enabled you will see new panels in the customizer specific to EDD. Make sur you have EDD plugin enabled.',
			),
		);
		foreach( $fields as $field ){
			add_settings_field( $field['id'], $field['label'], array( $this, 'wph_field_callback' ), 'startwpextensions', $field['section'], $field );
			register_setting( 'startwpextensions', $field['id'] );
		}
	}
	public function wph_field_callback( $field ) {
		$value = get_option( $field['id'] );
		
		switch ( $field['type'] ) {
				case 'radio':
				case 'checkbox':
					if( ! empty ( $field['options'] ) && is_array( $field['options'] ) ) {
						$options_markup = '';
						$iterator = 0;
						foreach( $field['options'] as $key => $label ) {
							$iterator++;
							$options_markup.= sprintf('<label for="%1$s_%6$s"><input id="%1$s_%6$s" name="%1$s[]" type="%2$s" value="%3$s" %4$s /> %5$s</label><br/>',
							$field['id'],
							$field['type'],
							$key,
							($value === '' ? 'checked' : checked($value[array_search($key, $value, true)], $key, false)),
							$label,
							$iterator
							);
							}
							printf( '<fieldset>%s</fieldset>',
							$options_markup
							);
					}
					break;
			default:
				printf( '<input name="%1$s" id="%1$s" type="%2$s" placeholder="%3$s" value="%4$s" />',
					$field['id'],
					$field['type'],
					$field['placeholder'],
					$value
				);
		}
		if( $desc = $field['desc'] ) {
			printf( '<p class="description">%s </p>', $desc );
		}
	}
}
new startwpextensions_Settings_Page();