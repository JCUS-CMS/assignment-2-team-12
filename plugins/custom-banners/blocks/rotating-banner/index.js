( 
	function( wp ) {
	/**
	 * Registers a new block provided a unique name and an object defining its behavior.
	 * @see https://github.com/WordPress/gutenberg/tree/master/blocks#api
	 */
	var registerBlockType = wp.blocks.registerBlockType;
	/**
	 * Returns a new element of given type. Element is an abstraction layer atop React.
	 * @see https://github.com/WordPress/gutenberg/tree/master/element#element
	 */
	var el = wp.element.createElement;
	/**
	 * Retrieves the translation of text.
	 * @see https://github.com/WordPress/gutenberg/tree/master/i18n#api
	 */
	var __ = wp.i18n.__;
	
	var get_theme_group_label = function(theme_group_key) {
		if ( typeof(custom_banners_admin_single_banner.theme_group_labels[theme_group_key]) !== 'undefined' ) {
			return custom_banners_admin_single_banner.theme_group_labels[theme_group_key];
		}
		return 'Themes';
	};	

	var build_category_options = function(categories) {
		var opts = [
			{
				label: 'All Categories',
				value: ''
			}
		];

		// build list of options from goals
		for( var i in categories ) {
			cat = categories[i];
			opts.push( 
			{
				label: cat.name,
				value: cat.slug
			});
		}
		return opts;
	};	

	var build_transition_options = function(transitions) {
		var opts = [];

		// build list of options from goals
		for( var i in transitions ) {
			label = transitions[i];
			opts.push( 
			{
				label: label,
				value: i
			});
		}
		return opts;
	};	

	var build_count_options = function() {
		var opts = [
			{
				label: 'All Banners',
				value: '-1'
			}
		];

		// build list of options from goals
		for( var i = 1; i <= 20; i++ ) {
			opts.push( 
			{
				label: i,
				value: i
			});
		}
		return opts;
	};	

	var get_theme_options = function() {
		var theme_opts = [];
		for ( theme_name in custom_banners_admin_single_banner.themes ) {
			theme_opts.push({
				label: custom_banners_admin_single_banner.themes[theme_name],
				value: theme_name,
			});				
		}
		return theme_opts;
	};
	
	var extract_label_from_options = function (opts, val) {
		var label = '';
		for (j in opts) {
			if ( opts[j].value == val ) {
				label = opts[j].label;
				break;
			}										
		}
		return label;
	};
	
	var update_height_panel = function () {
		setTimeout( function () {
			var field_groups =  jQuery('.janus_editor_field_group');
			field_groups.each(function () {
				field_group = jQuery(this);
				var val = field_group.find(':checked').val();
				if ( 'specify' == val ) {
					field_group.find('.banner_height_px').show();
				}
				else {
					field_group.find('.banner_height_px').hide();
				}							
				return true;
			});
		}, 100 );
	};	
	
	var update_width_panel = function () {
		setTimeout( function () {
			var field_groups =  jQuery('.janus_editor_field_group');
			field_groups.each(function () {
				field_group = jQuery(this);
				var val = field_group.find(':checked').val();
				if ( 'specify' == val ) {
					field_group.find('.banner_width_px').show();
				}
				else {
					field_group.find('.banner_width_px').hide();
				}							
				return true;
			});
		}, 100 );
	};	
	
	var checkbox_control = function (label, checked, onChangeFn) {
		// add checkboxes for which fields to display
		var controlOptions = {
			checked: checked,
			label: label,
			value: '1',
			onChange: onChangeFn,
		};	
		return el(  wp.components.CheckboxControl, controlOptions );
	};


	var text_control = function (label, value, className, onChangeFn) {
		var controlOptions = {
			label: label,
			value: value,
			className: className,
			onChange: onChangeFn,
		};
		return el(  wp.components.TextControl, controlOptions );
	};

	var radio_control = function (label, value, options, className, onChangeFn) {
		var controlOptions = {
			label: label,
			onChange: onChangeFn,
			options: options,
			selected: value,
			className: '',
		};
		return el(  wp.components.RadioControl, controlOptions );
	};

	var iconGroup = [];
	iconGroup.push(	el(
			'path',
			{ d: "M0 0h24v24H0z", fill: 'none' }
		)
	);
	iconGroup.push(	el(
			'path',
			{ d: "M10 8v8l5-4-5-4zm9-5H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zm0 16H5V5h14v14z"}
		)
	);
	
	var iconEl = el(
		'svg', 
		{ width: 24, height: 24 },
		iconGroup
	);	
	
	/**
	 * Every block starts by registering a new block type definition.
	 * @see https://wordpress.org/gutenberg/handbook/block-api/
	 */
	registerBlockType( 'custom-banners/rotating-banner', {
		/**
		 * This is the display title for your block, which can be translated with `i18n` functions.
		 * The block inserter will show this name.
		 */
		title: __( 'Slideshow' ),

		/**
		 * Blocks are grouped into categories to help users browse and discover them.
		 * The categories provided by core are `common`, `embed`, `formatting`, `layout` and `widgets`.
		 */
		category: 'custom-banners',

		/**
		 * Optional block extended support features.
		 */
		supports: {
			// Removes support for an HTML mode.
			html: false,
		},

		/**
		 * The edit function describes the structure of your block in the context of the editor.
		 * This represents what the editor will render when the block is used.
		 * @see https://wordpress.org/gutenberg/handbook/block-edit-save/#edit
		 *
		 * @param {Object} [props] Properties passed from the editor.
		 * @return {Element}       Element to render.
		 */
		edit: wp.data.withSelect( function( select ) {
					return {
						categories: select( 'core' ).getEntityRecords( 'taxonomy', 'banner_groups', {
							order: 'asc',
							orderby: 'id'
						})
					};
				} ) ( function( props ) {
							var retval = [];
							var inspector_controls = [],
								id = props.attributes.id || '',
								banner_title = props.attributes.banner_title || '',								
								caption_position = props.attributes.caption_position || 'bottom',
								use_image_tag = typeof(props.attributes.use_image_tag) != 'undefined' ? props.attributes.use_image_tag : true,
								banner_height = props.attributes.banner_height || 'auto',
								banner_height_px = props.attributes.banner_height_px || '',
								banner_width = props.attributes.banner_width || 'auto',
								banner_width_px = props.attributes.banner_width_px || '',
								link_entire_banner = typeof(props.attributes.link_entire_banner) != 'undefined' ? props.attributes.link_entire_banner : false,
								open_link_in_new_window = typeof(props.attributes.open_link_in_new_window) != 'undefined' ? props.attributes.open_link_in_new_window : false,
								show_caption = typeof(props.attributes.show_caption) != 'undefined' ? props.attributes.show_caption : true,
								show_cta_button = typeof(props.attributes.show_cta_button) != 'undefined' ? props.attributes.show_cta_button : true,
								theme = props.attributes.theme || 'default_style',																
								group = props.attributes.group || '',
								count = typeof(props.attributes.count) != 'undefined' ? props.attributes.count : '-1',
								transition = props.attributes.transition || 'fade',
								pager = typeof(props.attributes.pager) != 'undefined' ? props.attributes.pager : false,
								timer = props.attributes.timer || '4000',
								show_pager_icons = typeof(props.attributes.show_pager_icons) != 'undefined' ? props.attributes.show_pager_icons : false,
								use_pager_thumbnails = typeof(props.attributes.use_pager_thumbnails) != 'undefined' ? props.attributes.use_pager_thumbnails : false,
								hide = typeof(props.attributes.hide) != 'undefined' ? props.attributes.hide : false,
								pause_on_hover = typeof(props.attributes.pause_on_hover) != 'undefined' ? props.attributes.pause_on_hover : false,
								auto_height = typeof(props.attributes.auto_height) != 'undefined' ? props.attributes.auto_height : true,
								prev_next = typeof(props.attributes.prev_next) != 'undefined' ? props.attributes.prev_next : false,
								paused = typeof(props.attributes.paused) != 'undefined' ? props.attributes.paused : false,								
								focus = props.isSelected;
								
						if ( !! focus || ! id.length ) {
							
							//retval.push( el('h3', { className: 'block-heading' }, __('Custom Banners - Rotating Banner') ) );

							/* Add Theme options fields */
							var theme_fields = [];
							
							// add <select> to choose the Theme
							// note: Gutenburg's select control does not currently support optgroups
							var controlOptions = {
								label: __('Select a Theme:'),
								value: theme,
								onChange: function( newVal ) {
									props.setAttributes({
										theme: newVal
									});
								},
								options: get_theme_options(),
							};
						
							theme_fields.push(
								el(  wp.components.SelectControl, controlOptions )
							);

							if ( !custom_banners_admin_single_banner.is_pro ) {
								theme_fields.push(
									el(  
										'a',
										{ 
											className: 'gp-upgrade-link',
											href: 'http://goldplugins.com/our-plugins/custom-banners-details/upgrade-to-custom-banners-pro/?utm_source=gutenburg_inspector&utm_campaign=pro_themes',
											target: '_blank',
										},
										__('Unlock All 100+ Pro Themes!') )
								);
							}
							
							inspector_controls.push(							
								el (
									wp.components.PanelBody,
									{
										title: __('Theme'),
										className: 'gp-panel-body',
										initialOpen: true,
									},
									theme_fields
								)
							);
							// end Theme options
							
							/* Caption Position Options */
							var caption_position_fields = [];
							var caption_position_options = [
								{
									label: 'Left',
									value: 'left',
								},
								{
									label: 'Right',
									value: 'right',
								},
								{
									label: 'Top',
									value: 'top',
								},
								{
									label: 'Bottom',
									value: 'bottom',
								},
							];
							caption_position_fields.push( 
								radio_control( __('Caption Position:'), caption_position, caption_position_options, 'caption_position', function( newVal ) {
									props.setAttributes({
										caption_position: newVal,
									});
								})
							);


							inspector_controls.push( 
								el (
									wp.components.PanelBody,
									{
										title: __('Caption Position'),
										className: 'gp-panel-body',
										initialOpen: false,
									},
									el('div', { className: 'janus_editor_field_group' }, caption_position_fields)
								)
							);

							//  End Caption Position Options
							
							/* Height Options */
							var height_fields = [];
							var height_opts = [
								{
									label: __('Auto'),
									value: 'auto'
								},
								{
									label: __('Specify (px)'),
									value: 'specify'
								},
							];

							height_fields.push(
								radio_control( __('Height:'), banner_height, height_opts, 'height', function( newVal ) {
									props.setAttributes({
										banner_height: newVal
									});
									update_height_panel();
								})
							);

							height_fields.push(
								text_control( __('Height (in px):'), banner_height_px, 'banner_height_px', function( newVal ) {
									props.setAttributes({
										banner_height_px: newVal
									});
									update_height_panel();
								})
							);

							inspector_controls.push( 
								el (
									wp.components.PanelBody,
									{
										title: __('Height'),
										className: 'gp-panel-body',
										initialOpen: false,
										onToggle: update_height_panel,
									},
									el('div', { className: 'janus_editor_field_group janus_editor_field_group_no_heading' }, height_fields)
								)
							);
							// End Height Options
							
							/* Width Options */
							var width_fields = [];
							var width_opts = [
								{
									label: __('Auto'),
									value: 'auto'
								},
								{
									label: __('100%'),
									value: '100_percent'
								},
								{
									label: __('Specify (px)'),
									value: 'specify'
								},
							];

							width_fields.push(
								radio_control( __('Width:'), banner_width, width_opts, 'width', function( newVal ) {
									props.setAttributes({
										banner_width: newVal
									});
									update_width_panel();
								})
							);

							width_fields.push(
								text_control( __('Width (in px):'), banner_width_px, 'banner_width_px', function( newVal ) {
									props.setAttributes({
										banner_width_px: newVal
									});
									update_width_panel();
								})
							);

							inspector_controls.push( 
								el (
									wp.components.PanelBody,
									{
										title: __('Width'),
										className: 'gp-panel-body',
										initialOpen: false,
										onToggle: update_width_panel,
									},
									el('div', { className: 'janus_editor_field_group janus_editor_field_group_no_heading' }, width_fields)
								)
							);
							// End Width Options
							
							/* Category Options */
							var category_fields = [];
							var controlOptions = {
								label: __('Select a Banner Group:'),
								value: group,
								onChange: function( newVal ) {
									props.setAttributes({
										group: newVal
									});
								},
								options: build_category_options(props.categories),
							};

							category_fields.push(
								el(  wp.components.SelectControl, controlOptions )
							);

							var controlOptions = {
								label: __('Number of Banners To Display:'),
								value: count,
								onChange: function( newVal ) {
									props.setAttributes({
										count: newVal
									});
								},
								options: build_count_options(),
							};

							category_fields.push(
								el(  wp.components.SelectControl, controlOptions )
							);

							inspector_controls.push(							
								el (
									wp.components.PanelBody,
									{
										title: __('Select Banners To Display'),
										className: 'gp-panel-body',
										initialOpen: false,
									},
									category_fields
								)
							);
							// End Category Options
							
							/* Slideshow Options */	
							var slideshow_fields = [];
							var timer_options = [];
							for (var ii = 0; ii <= 60; ii++) {
								timer_options.push({
									label: ii + ' ' + __('seconds'),
									value : ii,
								});
							}

							// transition
							var controlOptions = {
								label: __('Transition:'),
								value: transition,
								onChange: function( newVal ) {
									props.setAttributes({
										transition: newVal
									});
								},
								options: build_transition_options(custom_banners_admin_rotating_banner.transitions),
							};

							slideshow_fields.push(
								el(  wp.components.SelectControl, controlOptions )
							);						

							// timer
							controlOptions = {
								label: __('Time Between Transitions:'),
								value: timer,
								onChange: function( newVal ) {
									props.setAttributes({
										timer: newVal
									});
								},
								options: timer_options,
							};

							slideshow_fields.push(
								el(  wp.components.SelectControl, controlOptions )
							);

							// pause on hover
							slideshow_fields.push(
								checkbox_control( __('Pause Slideshow on Hover'), pause_on_hover, function( newVal ) {
									props.setAttributes({
										pause_on_hover: newVal,
									});
								})
							);

							slideshow_fields.push(
								el('p', { className: 'description'}, __('If checked, the height of the slideshow will be adjusted to the height of each slide.') )
							);

							// show pager icons
							slideshow_fields.push(
								checkbox_control( __('Show Pager Icons'), show_pager_icons, function( newVal ) {
									props.setAttributes({
										show_pager_icons: newVal,
									});
								})
							);

							// use pager thumbnails
							slideshow_fields.push(
								checkbox_control( __('Use Pager Thumbnails'), use_pager_thumbnails, function( newVal ) {
									props.setAttributes({
										use_pager_thumbnails: newVal,
									});
								})
							);

							slideshow_fields.push(
								el('p', { className: 'description'}, __('If checked, thumbnails of the banners will be used for the pagers instead of round dots.') )
							);

							// show prev next
							slideshow_fields.push(
								checkbox_control( __('Show Previous and Next Buttons'), prev_next, function( newVal ) {
									props.setAttributes({
										prev_next: newVal,
									});
								})
							);

							// disable auto transition
							slideshow_fields.push(
								checkbox_control( __('Disable Auto Transition'), paused, function( newVal ) {
									props.setAttributes({
										paused: newVal,
									});
								})
							);

							slideshow_fields.push(
								el('p', { className: 'description'}, __('If checked, the slideshow will only transition to the next slide when manually triggered (i.e., via the Pager Icons or Previous/Next buttons)') )
							);


							inspector_controls.push(							
								el (
									wp.components.PanelBody,
									{
										title: __('Slideshow Options'),
										className: 'gp-panel-body',
										initialOpen: false,
									},
									slideshow_fields
								)
							);							
							// End Slideshow Options
							
							
							
							/* Advanced Options */
							var advanced_options_fields = [];							
							advanced_options_fields.push( 
								checkbox_control( __('Show Caption Box:'), show_caption, function( newVal ) {
									props.setAttributes({
										show_caption: newVal,
									});
								})
							);

							advanced_options_fields.push( 
								checkbox_control( __('Show Button:'), show_cta_button, function( newVal ) {
									props.setAttributes({
										show_cta_button: newVal,
									});
								})
							);

							advanced_options_fields.push( 
								checkbox_control( __('Link Entire Banner:'), link_entire_banner, function( newVal ) {
									props.setAttributes({
										link_entire_banner: newVal,
									});
								})
							);

							advanced_options_fields.push( 
								checkbox_control( __('Open Link In New Window:'), open_link_in_new_window, function( newVal ) {
									props.setAttributes({
										open_link_in_new_window: newVal,
									});
								})
							);

							advanced_options_fields.push( 
								checkbox_control( __('Use Image Tag:'), use_image_tag, function( newVal ) {
									props.setAttributes({
										use_image_tag: newVal,
									});
								})
							);


							inspector_controls.push( 
								el (
									wp.components.PanelBody,
									{
										title: __('Advanced Options'),
										className: 'gp-panel-body',
										initialOpen: false,
									},
									el('div', { className: 'janus_editor_field_group' }, advanced_options_fields)
								)
							);
							// End Advanced Options
							

							retval.push(
								el( wp.editor.InspectorControls, {}, inspector_controls ) 
							);

						}

						var inner_fields = [];
						inner_fields.push( el('h3', { className: 'block-heading' }, 'Custom Banners - Rotating Banner') );							
						inner_fields.push( el('blockquote', {}, 'A slideshow of banners from your database.') );
						retval.push( el('div', {'className': 'custom-banners-editor-not-selected'}, inner_fields ) );
						
				return el( 'div', { className: 'custom-banners-rotating-banner-editor'}, retval );
			} ),

		/**
		 * The save function defines the way in which the different attributes should be combined
		 * into the final markup, which is then serialized by Gutenberg into `post_content`.
		 * @see https://wordpress.org/gutenberg/handbook/block-edit-save/#save
		 *
		 * @return {Element}       Element to render.
		 */
		save: function() {
			return null;
		},
		attributes: {
			id: {
				type: 'string',
			},
			banner_title: {
				type: 'string',
			},			
			banner_height: {
				type: 'string',
			},
			banner_height_px: {
				type: 'string',
			},
			banner_width: {
				type: 'string',
			},
			banner_width_px: {
				type: 'string',
			},
			link_entire_banner: {
				type: 'boolean',
			},
			open_link_in_new_window: {
				type: 'boolean',
			},
			show_caption: {
				type: 'boolean',
			},
			show_cta_button: {
				type: 'boolean',
			},
			use_image_tag: {
				type: 'boolean',
			},
			caption_position: {
				type: 'string',
			},
			theme: {
				type: 'string',
			},
			transition: {
				type: 'string',
			},
			pager: {
				type: 'string',
			},
			timer: {
				type: 'string',
			},
			show_pager_icons: {
				type: 'string',
			},
			use_pager_thumbnails: {
				type: 'string',
			},
			hide: {
				type: 'string',
			},
			pause_on_hover: {
				type: 'string',
			},
			auto_height: {
				type: 'string',
			},
			prev_next: {
				type: 'string',
			},
			paused: {
				type: 'string',
			},			
		},
		icon: iconEl,
	} );
} )(
	window.wp
);
