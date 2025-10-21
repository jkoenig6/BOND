<?php

add_action('after_setup_theme', 'competemap_setup');
function competemap_setup()
{
    load_theme_textdomain('competemap', get_template_directory() . '/languages');
    add_theme_support('title-tag');
    add_theme_support('automatic-feed-links');
    add_theme_support('post-thumbnails');
    add_theme_support('html5', array(
        'search-form'
    ));
    global $content_width;
    if (!isset($content_width)) {
        $content_width = 1920;
    }
    register_nav_menus(array(
        'main-menu' => esc_html__('Main Menu', 'competemap')
    ));
}

add_action('wp_enqueue_scripts', 'competemap_load_scripts');
function competemap_load_scripts()
{
    wp_enqueue_style( 'competemap-fonts', '//fonts.googleapis.com/css?family=Work+Sans:400,500,700&display=swap', false ); 

    wp_enqueue_style('competemap-style', get_template_directory_uri() . '/assets/styles/style.css', array(), filemtime(get_stylesheet_directory() .'/assets/styles/style.css'));

    wp_enqueue_script('jquery');

    wp_enqueue_script( 'competemap-script', get_template_directory_uri() . '/assets/javascript/map.min.js', array( 'jquery' ), filemtime(get_stylesheet_directory() .'/assets/javascript/map.min.js') );

}

add_action('wp_footer', 'competemap_footer_scripts');
function competemap_footer_scripts()
{
?>
<script>
jQuery(document).ready(function ($) {
var deviceAgent = navigator.userAgent.toLowerCase();
if (deviceAgent.match(/(iphone|ipod|ipad)/)) {
$("html").addClass("ios");
$("html").addClass("mobile");
}
if (navigator.userAgent.search("MSIE") >= 0) {
$("html").addClass("ie");
}
else if (navigator.userAgent.search("Chrome") >= 0) {
$("html").addClass("chrome");
}
else if (navigator.userAgent.search("Firefox") >= 0) {
$("html").addClass("firefox");
}
else if (navigator.userAgent.search("Safari") >= 0 && navigator.userAgent.search("Chrome") < 0) {
$("html").addClass("safari");
}
else if (navigator.userAgent.search("Opera") >= 0) {
$("html").addClass("opera");
}
});
</script>
<?php
}
add_filter('document_title_separator', 'competemap_document_title_separator');
function competemap_document_title_separator($sep)
{
    $sep = '|';
    return $sep;
}
add_filter('the_title', 'competemap_title');
function competemap_title($title)
{
    if ($title == '') {
        return '...';
    } else {
        return $title;
    }
}
add_filter('the_content_more_link', 'competemap_read_more_link');
function competemap_read_more_link()
{
    if (!is_admin()) {
        return ' <a href="' . esc_url(get_permalink()) . '" class="more-link">...</a>';
    }
}
add_filter('excerpt_more', 'competemap_excerpt_read_more_link');
function competemap_excerpt_read_more_link($more)
{
    if (!is_admin()) {
        global $post;
        return ' <a href="' . esc_url(get_permalink($post->ID)) . '" class="more-link">...</a>';
    }
}
add_filter('intermediate_image_sizes_advanced', 'competemap_image_insert_override');
function competemap_image_insert_override($sizes)
{
    unset($sizes['medium_large']);
    return $sizes;
}

add_action('widgets_init', 'competemap_widgets_init');
function competemap_widgets_init()
{
    register_sidebar(array(
        'name' => esc_html__('Sidebar Widget Area', 'competemap'),
        'id' => 'primary-widget-area',
        'before_widget' => '<li id="%1$s" class="widget-container %2$s">',
        'after_widget' => '</li>',
        'before_title' => '<h3 class="widget-title">',
        'after_title' => '</h3>'
    ));
}

add_action('wp_head', 'competemap_pingback_header');
function competemap_pingback_header()
{
    if (is_singular() && pings_open()) {
        printf('<link rel="pingback" href="%s" />' . "\n", esc_url(get_bloginfo('pingback_url')));
    }
}


/* Custom thumbnail sizes */
add_image_size( 'school-image', 682, 292, array( 'center', 'center' ) );
add_image_size( 'school-logo', 200, 300);
add_image_size( 'principal-photo', 150, 150, array( 'center', 'center' ) );

 
/*
* Register Schools custom post type
*/

function compete_schools_custom_post_type() {
 
    // Set UI labels for Custom Post Type
        $labels = array(
            'name'                => _x( 'Schools', 'Post Type General Name', 'competemap' ),
            'singular_name'       => _x( 'School', 'Post Type Singular Name', 'competemap' ),
            'menu_name'           => __( 'Schools', 'competemap' ),
            'parent_item_colon'   => __( 'Parent School', 'competemap' ),
            'all_items'           => __( 'All Schools', 'competemap' ),
            'view_item'           => __( 'View School', 'competemap' ),
            'add_new_item'        => __( 'Add New School', 'competemap' ),
            'add_new'             => __( 'Add New', 'competemap' ),
            'edit_item'           => __( 'Edit School', 'competemap' ),
            'update_item'         => __( 'Update School', 'competemap' ),
            'search_items'        => __( 'Search School', 'competemap' ),
            'not_found'           => __( 'Not Found', 'competemap' ),
            'not_found_in_trash'  => __( 'Not found in Trash', 'competemap' ),
        );
         
    // Set other options for Custom Post Type
         
        $args = array(
            'label'               => __( 'Schools', 'competemap' ),
            'description'         => __( 'School news and reviews', 'competemap' ),
            'labels'              => $labels,
            'supports'            => array( 'title', 'editor', 'excerpt', 'author', 'thumbnail', 'comments', 'revisions', 'custom-fields', ),
            'taxonomies'          => array('regions'),
            'hierarchical'        => false,
            'public'              => true,
            'show_ui'             => true,
            'show_in_menu'        => true,
            'show_in_nav_menus'   => true,
            'show_in_admin_bar'   => true,
            'menu_position'       => 5,
            'menu_icon'           => 'dashicons-location',
            'can_export'          => true,
            'has_archive'         => true,
            'exclude_from_search' => false,
            'publicly_queryable'  => true,
            'capability_type'     => 'page',
        );
         
        register_post_type( 'schools', $args );
     
    }

    add_action( 'init', 'compete_schools_custom_post_type', 0 );



/*
* Create Regions taxonomy for Schools custom post type
*/
add_action( 'init', 'compete_schools_custom_taxonomy', 0 );
    
    function compete_schools_custom_taxonomy() {
    
    // Set UI labels for Regions taxonomy
    $labels = array(
        'name' => _x( 'Regions', 'taxonomy general name' ),
        'singular_name' => _x( 'Region', 'taxonomy singular name' ),
        'search_items' =>  __( 'Search Regions' ),
        'all_items' => __( 'All Regions' ),
        'parent_item' => __( 'Parent Region' ),
        'parent_item_colon' => __( 'Parent Region:' ),
        'edit_item' => __( 'Edit Region' ), 
        'update_item' => __( 'Update Region' ),
        'add_new_item' => __( 'Add New Region' ),
        'new_item_name' => __( 'New Region Name' ),
        'menu_name' => __( 'Regions' ),
    ); 	
    
    // Set other options for Regions taxonomy
    register_taxonomy('regions',array('schools'), array(
        'hierarchical' => true,
        'labels' => $labels,
        'show_ui' => true,
        'show_admin_column' => true,
        'query_var' => true,
        'rewrite' => array( 'slug' => 'region' ),
    ));
}




/*
* Add options page for map settings for School post type
*/
   if( function_exists('acf_add_options_page') ) {
	
        acf_add_options_page(array(
            'page_title' 	=> 'Map Settings',
            'menu_title'	=> 'Map Settings',
            'menu_slug' 	=> 'map-settings',
            'capability'	=> 'edit_posts',
            'redirect'		=> false
        ));
        
    }

/*
*Hide menu items from WP admin sidebar
*/
    function post_remove () { 

        remove_menu_page('edit.php'); // Posts
        remove_menu_page( 'edit.php?post_type=page' );    //Pages
        remove_menu_page( 'edit-comments.php' );          //Comments

    }

    add_action('admin_menu', 'post_remove');   //adding action for triggering function call
    
    
    // Scheduled Action Hook

function w3_flush_cache( ) {
    $w3_plugin_totalcache->flush_all();
}


// Added by Jason Koenig (CCSD-CIO) to clear cache daily
// Schedule Cron Job Event

function w3tc_cache_flush() {
    if ( ! wp_next_scheduled( 'w3_flush_cache' ) ) {
        wp_schedule_event( current_time( 'timestamp' ), 'weekly', 'w3_flush_cache' );    
    }
}

add_action( 'wp', 'w3tc_cache_flush' );