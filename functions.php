<?php
function header_bc_scripts() {
	wp_enqueue_style( 'style-name', get_stylesheet_uri() );
	wp_enqueue_style( 'bc-style',  get_template_directory_uri() .'/fonts/brandcandy/bc-style.css', array(), null, 'all' );
	wp_enqueue_style( 'lb-style',  get_template_directory_uri() .'/js/lightbox/lightbox.min.css', array(), null, 'all' );
}
add_action( 'wp_enqueue_scripts', 'header_bc_scripts' );
function footer_bc_scripts() {
	wp_enqueue_script( 'google-js', 'https://ajax.googleapis.com/ajax/libs/jquery/1/jquery.min.js', array(), '1.0.0', true );
	wp_enqueue_script( 'lb-plus', get_template_directory_uri() . '/js/lightbox/lightbox-plus-jquery.min.js', array(), '1.0.0', true );
	wp_enqueue_script( 'flexslider', get_template_directory_uri() . '/js/jquery.flexslider-min.js', array(), '1.0.0', true );
	wp_enqueue_script( 'custom-js', get_template_directory_uri() . '/js/custom.js', array(), '1.0.0', true );
}
add_action( 'wp_enqueue_scripts', 'footer_bc_scripts' );
// BC Theme Options
if( function_exists('acf_add_options_page') ) {
	$option_page = acf_add_options_page(array(
		'page_title' 	=> 'Theme General Settings',
		'menu_title' 	=> 'Theme Settings',
		'menu_slug' 	=> 'theme-general-settings',
		'capability' 	=> 'edit_posts',
		'icon_url' => get_template_directory_uri(). '/images/admin/bc-theme.png' ,
		'redirect' 	=> false,
		'position' => 2
	));
}
// Add RSS links to <head> section
	automatic_feed_links();

// Clean up the <head>
	function removeHeadLinks() {
    	remove_action('wp_head', 'rsd_link');
    	remove_action('wp_head', 'wlwmanifest_link');
    }
    add_action('init', 'removeHeadLinks');
    remove_action('wp_head', 'wp_generator');

// Declare sidebar widget zone
    if (function_exists('register_sidebar')) {
    	register_sidebar(array(
    		'name' => 'Sidebar',
    		'id'   => 'sidebar',
    		'description'   => 'These are widgets for the sidebar.',
    		'before_widget' => '<div class="sidebar-widget">',
			'before_title'  => '<h3>',
    		'after_title'   => '</h3>',
    		'after_widget'  => '</div>'

    	));

        register_sidebar(array(
            'name' => 'Header Widget',
            'id'   => 'header-widget',
            'description'   => 'A widget for small amounts of content in the header',
            'before_widget' => '<div class="header-widget">',
            'before_title'  => '<h3>',
            'after_title'   => '</h3>',
            'after_widget'  => '</div>'

        ));

        register_sidebar(array(
            'name' => 'Footer Column Left',
            'id'   => 'footer-column-left',
            'description'   => 'Widgets for the left footer column',
            'before_widget' => '',
            'before_title'  => '<h3>',
            'after_title'   => '</h3>',
            'after_widget'  => ''

        ));

        register_sidebar(array(
            'name' => 'Footer Column Middle',
            'id'   => 'footer-column-middle',
            'description'   => 'Widgets for the middle footer column',
            'before_widget' => '',
            'before_title'  => '<h3>',
            'after_title'   => '</h3>',
            'after_widget'  => ''

        ));

        register_sidebar(array(
            'name' => 'Footer Column Right',
            'id'   => 'footer-column-right',
            'description'   => 'Widgets for the right footer column',
            'before_widget' => '',
            'before_title'  => '<h3>',
            'after_title'   => '</h3>',
            'after_widget'  => ''

        ));

    }


// Register multiple menus
	function register_my_menus() {
	  register_nav_menus(
		array(
		  'main-menu' => __( 'Main Menu' ),
		)
	  );
	}
	add_action( 'init', 'register_my_menus' );


// Master excerpt length change
    function custom_excerpt_length( $length ) {
          return 35;
    }
    add_filter( 'excerpt_length', 'custom_excerpt_length', 999 );


// remove the brackets and three dots at end of excerpt and replace with below:
function new_excerpt_more( $more ) {
    return ' ...';
}
add_filter('excerpt_more', 'new_excerpt_more');


// Post Thumbnails
    add_theme_support( 'post-thumbnails' );


// Add new image sizes
    add_image_size('full-width', 1200, 400, true);
    add_image_size('half-width', 594, 186, true);
    add_image_size('third-width', 392, 280, true);
    add_image_size('quarter-width', 291, 200, true);
    add_image_size('fifth-width', 230, 160, true);
    add_image_size('sixth-width', 190, 115, true);


// Show custom images in media library
    add_filter( 'image_size_names_choose', 'my_custom_sizes' );

    function my_custom_sizes( $sizes ) {
        return array_merge( $sizes, array(
            'custom-image-size' => __('Custom Image Size'),
        ) );
    }


// Add placeholder text for Gravity Forms
    add_filter( 'gform_enable_field_label_visibility_settings', '__return_true' );

//add_filter("gform_confirmation_anchor", create_function("","return true;"));  - OLD DEPRECATED CODE
add_filter("gform_confirmation_anchor", fn() => true); // NEW CODE Added
// Scroll to Gravity Form confirmation message
add_filter( 'gform_confirmation_anchor', function() {
    return 20; //adds an additional 20 pixels from the top - handy for fixed navigation bars
} );


// Login Logo
function custom_loginlogo() {
echo '<style type="text/css">
h1 a {background-image: url('.get_bloginfo('template_directory').'/images/logo.png) !important; }
</style>';
}
add_action('login_head', 'custom_loginlogo');
// Custom Admin Style
	add_action('admin_head', 'rd_admin_custom_css');
	function rd_admin_custom_css() {
		echo '<style>
			#adminmenu .wp-menu-image img {
			 	padding: 0px !important;
			 	opacity: 1 !important;
			}
		</style>';
	}
// Add theme support for Woocommerce
    add_action( 'after_setup_theme', 'woocommerce_support' );
    function woocommerce_support() {
        add_theme_support( 'woocommerce' );
    }


// Remove Showing results functionality site-wide
    function woocommerce_result_count() {
            return;
    }


// Change number or products per row to 3
    add_filter('loop_shop_columns', 'loop_columns');
    if (!function_exists('loop_columns')) {
        function loop_columns() {
            return 3; // 3 products per row
        }
    }


// Remove Woo tabs
    add_filter( 'woocommerce_product_tabs', 'woo_remove_product_tabs', 98 );
    function woo_remove_product_tabs( $tabs ) {

        unset( $tabs['additional_information'] );   // Remove the additional information tab
        unset( $tabs['reviews'] );   // Remove the reviews tab

        return $tabs;
    }


// Rename Woocommerce tabs
    add_filter( 'woocommerce_product_tabs', 'woo_rename_tabs', 98 );
    function woo_rename_tabs( $tabs ) {

        $tabs['description']['title'] = __( 'More Info' );      // Rename the description tab

        return $tabs;
    }


// Add Gallery functionality to Woocommerce 3.0 */
add_action( 'after_setup_theme', 'yourtheme_setup' );

function yourtheme_setup() {
add_theme_support( 'wc-product-gallery-zoom' );
add_theme_support( 'wc-product-gallery-lightbox' );
add_theme_support( 'wc-product-gallery-slider' );
}
/*************************************************************/
/*   Friendly Block Titles                                  */
/***********************************************************/

function my_layout_title($title, $field, $layout, $i) {
	if($value = get_sub_field('layout_title')) {
		return $value;
	} else {
		foreach($layout['sub_fields'] as $sub) {
			if($sub['name'] == 'layout_title') {
				$key = $sub['key'];
				if(array_key_exists($i, $field['value']) && $value = $field['value'][$i][$key])
					return $value;
			}
		}
	}
	return $title;
}
add_filter('acf/fields/flexible_content/layout_title', 'my_layout_title', 10, 4);
// BC Social Shortcode
	function bc_social( $attr ) {
		ob_start();
		get_template_part( 'parts/social' );
		return ob_get_clean();
	}
	add_shortcode( 'bc-social', 'bc_social' );
?>
