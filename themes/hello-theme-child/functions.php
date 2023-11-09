<?php
/**
 * Theme functions and definitions
 *
 * @package HelloElementorChild
 */

/**
 * Load child theme css and optional scripts
 *
 * @return void
 */
function hello_elementor_child_enqueue_scripts() {
	wp_enqueue_style(
		'hello-elementor-child-style',
		get_stylesheet_directory_uri() . '/style.css',
		[
			'hello-elementor-theme-style',
		],
		'1.0.0'
	);
    wp_enqueue_style('tabscss', get_stylesheet_directory_uri() . '/assets/css/tabs.css');
    //wp_enqueue_script('tabsjs', get_stylesheet_directory_uri() . '/assets/js/tabs.js', array('jquery'), true);
    wp_register_script('tabs', get_stylesheet_directory_uri() . '/assets/js/tabs.js', false, null, true);
    wp_enqueue_script('tabs');
}
add_action( 'wp_enqueue_scripts', 'hello_elementor_child_enqueue_scripts', 20 );

require_once('inc/tabsmenu.php');
// 1. customize ACF path
add_filter('acf/settings/path', 'my_acf_settings_path');

function my_acf_settings_path($path) {

    // update path
    $path = get_stylesheet_directory() . '/acf/';

    // return
    return $path;
}

// 2. customize ACF dir
add_filter('acf/settings/dir', 'my_acf_settings_dir');

function my_acf_settings_dir($dir) {

    // update path
    $dir = get_stylesheet_directory_uri() . '/acf/';

    // return
    return $dir;
}

// 3. Hide ACF field group menu item
//add_filter('acf/settings/show_admin', '__return_false');


// 4. Include ACF
include_once( get_stylesheet_directory() . '/acf/acf.php' );

add_filter('manage_posts_columns', 'posts_columns', 5);
add_action('manage_posts_custom_column', 'posts_custom_columns', 5, 2);

function posts_columns($defaults) {
    $defaults['riv_post_thumbs'] = __('Thumbs');
    return $defaults;
}

function posts_custom_columns($column_name, $id) {
    if ($column_name === 'riv_post_thumbs') {
        echo the_post_thumbnail('admin-thumbnail');
    }
}

/* ----------- Menu Items----------- */
add_action('init', 'dwk_cpt', 9);

function dwk_cpt() {
    register_post_type('dwk_menu', array(
        'labels' => array(
            'name' => __('Randwick Menu'),
            'singular_name' => __('dwk_menu')
        ),
        'public' => true,
        'has_archive' => true,
        'menu_icon' => 'dashicons-editor-ol',
        'rewrite' => array('slug' => 'dwk_menu'),
        'supports' => array('title', 'thumbnail'),
            )
    );
   
}
/** custom category type */
add_action( 'init', 'creat_randwickmenu_type' );

function creat_randwickmenu_type() {
	register_taxonomy(
		'dwk_menu_cat',
		'dwk_menu',
		array(
			'label' => __( 'Menu Type' ),
			'rewrite' => array( 'slug' => 'dwk_menu' ),
			'hierarchical' => true,
			'show_admin_column' => true,
	
		)
	);
}

