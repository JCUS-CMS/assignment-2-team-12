<?php
/*
===============================================================================================================
One ARK - control-radio-image.php
===============================================================================================================
The control-radio-image.php gives the user the ability to change features within the customizer. This has been 
setup to allow users to set different layouts within the theme and other features that the WordPress functionality 
is included.

@package        One ARK WordPress Theme
@copyright      Copyright (C) 2018. Benjamin Lu
@license        GNU General Public License v2 or later (http://www.gnu.org/licenses/gpl-2.0.html)
@author         Benjamin Lu (https://www.getbenonit.com/)
===============================================================================================================
*/

/*
===============================================================================================================
Table of Content
===============================================================================================================
 1.0 - Control Radio Image (Class)
 2.0 - Control Radio Image (Layout)
 3.0 - Control Radio Image (Validation)
===============================================================================================================
*/

/*
===============================================================================================================
 1.0 - Control Radio Image (Class)
===============================================================================================================
*/
function one_ark_control_radio_image_class_setup() {
    class One_ARK_Control_Radio_Image extends WP_Customize_Control {
        public $type = 'radio-image';
        
        public function enqueue() {
            wp_enqueue_script('one-ark-customize-controls', get_template_directory_uri() . '/js/control-radio-image.js', array('jquery'));
            wp_enqueue_style('one-ark-customize-controls', get_template_directory_uri() . '/css/control-radio-image.css');
        }
        
		public function render_content() {
			if (empty($this->choices)) {
				return;
			}			
			
			$name = '_customize-radio-' . $this->id;
			?>
			<span class="customize-control-title">
				<?php echo esc_attr($this->label); ?>
			</span>
			<?php if (!empty($this->description)) : ?>
				<span class="description customize-control-description"><?php echo esc_html($this->description); ?></span>
			<?php endif; ?>

			<div id="input_<?php echo esc_attr($this->id); ?>" class="image">
				<?php foreach ($this->choices as $value => $label) : ?>
						<label for="<?php echo esc_attr($this->id . $value); ?>">
							<input class="image-select" type="radio" value="<?php echo esc_attr($value); ?>" id="<?php echo esc_attr($this->id . $value); ?>" name="<?php echo esc_attr($name); ?>" <?php esc_attr($this->link()); checked($this->value(), esc_attr($value)); ?>>
							<img src="<?php echo esc_url($label); ?>" alt="<?php echo esc_attr($value); ?>" title="<?php echo esc_attr($value); ?>">
						</label>
					</input>
				<?php endforeach; ?>
			</div>
			<?php
		}
    }
}
add_action('customize_register', 'one_ark_control_radio_image_class_setup');

/*
===============================================================================================================
 2.0 - Control Radio Image (Layout)
===============================================================================================================
*/
function one_ark_control_radio_image_layout_setup($wp_customize) {
    $wp_customize->add_panel('general_layouts', array(
        'title'     => esc_html__('General Layouts', 'one-ark'),
        'priority'  => 5,
    ));
    
    /*
    ===========================================================================================================
    Enable and activate General Layouts for One ARK. The General Layout should only be used under Posts, 
    Pages, and Archives.
    ===========================================================================================================
    */    
    $wp_customize->add_section('global_layout', array(
        'title'     => esc_html__('General Layout', 'one-ark'),
        'panel'     => 'general_layouts',
        'priority'  => 5,
    ));
    
    $wp_customize->add_setting('global_layout', array(
        'default'           => 'left-sidebar',
        'sanitize_callback' => 'one_ark_sanitize_layout',
        'transport'         => 'refresh',
        'type'              => 'theme_mod',
    ));
    
    $wp_customize->add_control(new One_ARK_Control_Radio_Image($wp_customize, 'global_layout', array(
        'label'         => esc_html__('General Layout', 'one-ark'),
        'description'   => esc_html__('General Layout applies to all layouts that supports in this theme.', 'one-ark'),
        'section'       => 'global_layout',
        'settings'      => 'global_layout',
        'type'          => 'radio-image',
        'choices'       => array(
			'left-sidebar'  => trailingslashit( get_template_directory_uri() ) . 'images/2cl.png',
            'right-sidebar' => trailingslashit( get_template_directory_uri() ) . 'images/2cr.png',
			'no-sidebar'    => trailingslashit( get_template_directory_uri() ) . 'images/1col.png',
        ),
    )));
    
    /*
    ===========================================================================================================
    Enable and activate Custom Layout for One ARK. The Custom Layout should only be used under Custom 
    Templates that is registered as part of the theme.
    ===========================================================================================================
    */
    $wp_customize->add_section('custom_layout', array(
        'title'     => esc_html__('Custom Layout', 'one-ark'),
        'panel'     => 'general_layouts',
        'priority'  => 5,
    ));
    
    $wp_customize->add_setting('custom_layout', array(
        'default'           => 'no-sidebar',
        'sanitize_callback' => 'one_ark_sanitize_layout',
        'transport'         => 'refresh',
        'type'              => 'theme_mod',
    ));
    
    $wp_customize->add_control(new One_ARK_Control_Radio_Image($wp_customize, 'custom_layout', array(
        'label'         => esc_html__('Custom Layout', 'one-ark'),
        'description'   => esc_html__('Custom Layout applies to all layouts that supports in this theme.', 'one-ark'),
        'section'       => 'custom_layout',
        'settings'      => 'custom_layout',
        'type'          => 'radio-image',
        'choices'       => array(
			'left-sidebar'  => trailingslashit( get_template_directory_uri() ) . 'images/2cl.png',
            'right-sidebar' => trailingslashit( get_template_directory_uri() ) . 'images/2cr.png',
			'no-sidebar'    => trailingslashit( get_template_directory_uri() ) . 'images/1col.png',
        ),
    )));
}
add_action('customize_register', 'one_ark_control_radio_image_layout_setup');

/*
================================================================================================
 3.0 - Customize Register (Validation)
================================================================================================
*/
function one_ark_sanitize_layout($value) {
    if (!in_array($value, array('left-sidebar', 'right-sidebar', 'no-sidebar'))) {
        $value = 'left-sidebar';
    }
    return $value;
}