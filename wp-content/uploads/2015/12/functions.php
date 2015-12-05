<?php
/**
 * Omega functions and definitions
 *
 * @package Omega
 */

/**
 * Sets up theme defaults and registers support for various WordPress features.
 *
 * Note that this function is hooked into the after_setup_theme hook, which runs
 * before the init hook. The init hook is too late for some features, such as indicating
 * support post thumbnails.
 */
function shopping_theme_setup() {

	add_theme_support( 'omega-footer-widgets', 3 );

	add_action ('omega_header', 'shopping_header_right');
	
	add_theme_support( 'plugin-activation' );
	add_theme_support( 'woocommerce' );

	remove_action( 'omega_before_header', 'omega_get_primary_menu' );	
	add_action( 'omega_after_header', 'omega_get_primary_menu' );

	add_action('init', 'shopping_init', 1);

	add_action( 'widgets_init', 'shopping_widgets_init', 15 );

	add_theme_support( 'color-palette', array( 'callback' => 'shopping_register_colors' ) );
}

add_action( 'after_setup_theme', 'shopping_theme_setup', 11  );

function shopping_header_right() {
	?>	

	<aside class="header-right widget-area sidebar">
		
		<?php 
		if ( is_active_sidebar( 'header-right' ) ) {
			dynamic_sidebar( 'header-right' ); 
		} else {
			?>
			<section class="widget widget_search widget-widget_search">

	    		<div class="widget-wrap">
					<form role="search" method="get" class="search-form" action="<?php echo esc_url( home_url( '/' ) ); ?>">
			
						<input type="search" class="search-field" placeholder="<?php echo esc_attr_x( 'Search &hellip;', 'placeholder', 'shopping' ); ?>" value="<?php echo esc_attr( get_search_query() ); ?>" name="s" title="<?php _ex( 'Search for:', 'label', 'shopping' ); ?>">
						
					</form>
				</div>
			</section>
		<?php
		}
		?>

  	</aside><!-- .sidebar -->

	<?php
}

/**
 * Register widgetized area and update sidebar with default widgets
 */
function shopping_widgets_init() {
	register_sidebar( array(
		'name'          => __( 'Header Right', 'shopping' ),
		'id'            => 'header-right',
		'before_widget' => '<section id="%1$s" class="widget %2$s"><div class="widget-wrap">',
		'after_widget'  => '</div></section>',
		'before_title'  => '<h3 class="widget-title">',
		'after_title'   => '</h3>',
	) );
}

function shopping_init() {
	if(!is_admin()){
		wp_enqueue_script("tinynav", get_stylesheet_directory_uri() . '/js/tinynav.js', array('jquery'));
	} 
}

function shopping_register_colors( $color_palette ) {
	/* Add custom colors. */
	$color_palette->add_color(
		array( 'id' => 'primary', 'label' => __( 'Primary Color', 'shopping' ), 'default' => '96588a' )
	);
	$color_palette->add_color(
		array( 'id' => 'secondary', 'label' => __( 'Secondary Background', 'shopping' ), 'default' => 'AD74A2' )
	);
	$color_palette->add_color(
		array( 'id' => 'link', 'label' => __( 'Link Color', 'shopping' ), 'default' => 'AD74A2' )
	);

	/* Add rule sets for colors. */

	$color_palette->add_rule_set(
		'primary',
		array(
			'color'               => 'h1.site-title a, .site-description, .entry-meta, .header-right',
			'background-color'    => '.nav-primary, .tinynav, .footer-widgets, button, input[type="button"], input[type="reset"], input[type="submit"]'
		)
	);
	$color_palette->add_rule_set(
		'secondary',
		array(
			'background-color'    => '.omega-nav-menu .current_page_item a, .omega-nav-menu a:hover, .omega-nav-menu li:hover, .omega-nav-menu li:hover ul'
		)
	);
	$color_palette->add_rule_set(
		'link',
		array(
			'color'    => '.site-inner .entry-meta a, .site-inner .entry-content a, .site-inner .sidebar a, .site-footer a'
		)
	);
}

add_action( 'tgmpa_register', 'shopping_register_plugins' );
/**
 * Register the required plugins for this theme.
 *
 * In this example, we register two plugins - one included with the TGMPA library
 * and one from the .org repo.
 *
 * The variable passed to tgmpa_register_plugins() should be an array of plugin
 * arrays.
 *
 * This function is hooked into tgmpa_init, which is fired within the
 * TGM_Plugin_Activation class constructor.
 */
function shopping_register_plugins() {

	/**
	 * Array of plugin arrays. Required keys are name and slug.
	 * If the source is NOT from the .org repo, then source is also required.
	 */
	$plugins = array(

		// This is an example of how to include a plugin from the WordPress Plugin Repository
		array(
			'name' 		=> 'WooCommerce',
			'slug' 		=> 'woocommerce',
			'required' 	=> false,
		),
		array(
			'name' 		=> 'inSite for WP: personalization made easy',
			'slug' 		=> 'insite-for-wp-personalization-made-easy',
			'required' 	=> false,
		),

	);

	/**
	 * Array of configuration settings. Amend each line as needed.
	 * If you want the default strings to be available under your own theme domain,
	 * leave the strings uncommented.
	 * Some of the strings are added into a sprintf, so see the comments at the
	 * end of each line for what each argument will be.
	 */
	$config = array(
		'default_path' 		=> '',                         	// Default absolute path to pre-packaged plugins
		'parent_menu_slug' 	=> 'themes.php', 				// Default parent menu slug
		'parent_url_slug' 	=> 'themes.php', 				// Default parent URL slug
		'menu'         		=> 'install-required-plugins', 	// Menu slug
		'has_notices'      	=> true,                       	// Show admin notices or not
		'is_automatic'    	=> false,					   	// Automatically activate plugins after installation or not
		'message' 			=> '',							// Message to output right before the plugins table
		'strings'      		=> array(
			'page_title'                       			=> __( 'Install Required Plugins', 'shopping' ),
			'menu_title'                       			=> __( 'Install Plugins', 'shopping' ),
			'installing'                       			=> __( 'Installing Plugin: %s', 'shopping' ), // %1$s = plugin name
			'oops'                             			=> __( 'Something went wrong with the plugin API.', 'shopping' ),
			'notice_can_install_required'     			=> _n_noop( 'This theme requires the following plugin: %1$s.', 'This theme requires the following plugins: %1$s.', 'shopping' ), // %1$s = plugin name(s)
			'notice_can_install_recommended'			=> _n_noop( 'This theme recommends the following plugin: %1$s.', 'This theme recommends the following plugins: %1$s.', 'shopping' ), // %1$s = plugin name(s)
			'notice_cannot_install'  					=> _n_noop( 'Sorry, but you do not have the correct permissions to install the %s plugin. Contact the administrator of this site for help on getting the plugin installed.', 'Sorry, but you do not have the correct permissions to install the %s plugins. Contact the administrator of this site for help on getting the plugins installed.', 'shopping' ), // %1$s = plugin name(s)
			'notice_can_activate_required'    			=> _n_noop( 'The following required plugin is currently inactive: %1$s.', 'The following required plugins are currently inactive: %1$s.', 'shopping' ), // %1$s = plugin name(s)
			'notice_can_activate_recommended'			=> _n_noop( 'The following recommended plugin is currently inactive: %1$s.', 'The following recommended plugins are currently inactive: %1$s.', 'shopping' ), // %1$s = plugin name(s)
			'notice_cannot_activate' 					=> _n_noop( 'Sorry, but you do not have the correct permissions to activate the %s plugin. Contact the administrator of this site for help on getting the plugin activated.', 'Sorry, but you do not have the correct permissions to activate the %s plugins. Contact the administrator of this site for help on getting the plugins activated.', 'shopping' ), // %1$s = plugin name(s)
			'notice_ask_to_update' 						=> _n_noop( 'The following plugin needs to be updated to its latest version to ensure maximum compatibility with this theme: %1$s.', 'The following plugins need to be updated to their latest version to ensure maximum compatibility with this theme: %1$s.', 'shopping' ), // %1$s = plugin name(s)
			'notice_cannot_update' 						=> _n_noop( 'Sorry, but you do not have the correct permissions to update the %s plugin. Contact the administrator of this site for help on getting the plugin updated.', 'Sorry, but you do not have the correct permissions to update the %s plugins. Contact the administrator of this site for help on getting the plugins updated.', 'shopping' ), // %1$s = plugin name(s)
			'install_link' 					  			=> _n_noop( 'Begin installing plugin', 'Begin installing plugins', 'shopping' ),
			'activate_link' 				  			=> _n_noop( 'Activate installed plugin', 'Activate installed plugins', 'shopping' ),
			'return'                           			=> __( 'Return to Required Plugins Installer', 'shopping' ),
			'plugin_activated'                 			=> __( 'Plugin activated successfully.', 'shopping' ),
			'complete' 									=> __( 'All plugins installed and activated successfully. %s', 'shopping' ), // %1$s = dashboard link
			'nag_type'									=> 'updated' // Determines admin notice type - can only be 'updated' or 'error'
		)
	);

	if (function_exists('tgmpa')) {
		tgmpa( $plugins, $config );
	}

}

function shopping_load_theme_textdomain() {
  load_child_theme_textdomain( 'shopping', get_stylesheet_directory() . '/languages' );
}

add_action( 'after_setup_theme', 'shopping_load_theme_textdomain' );