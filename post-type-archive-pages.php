<?php
/**
 * Plugin Name:       Post Type Archive Pages
 * Description:       Set the archive for your custom post types to a page within your pages structure.
 * Version:           1.0.0
 * Author:            Darren Grant
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 */

if( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Post_Type_Archive_Pages {

    protected static $_instance = null;

    private $supported_post_types = null;

    const CONFIG_KEY = 'ptap_archive_pages';

    public function includes() {

        require plugin_dir_path( __FILE__ ) . 'includes/settings.php';
        require plugin_dir_path( __FILE__ ) . 'includes/admin-mods.php';
        require plugin_dir_path( __FILE__ ) . 'includes/permalinks.php';
        require plugin_dir_path( __FILE__ ) . 'includes/acf.php';

    }

    public function initialise() {

        new PTAP_Settings();
        new PTAP_Permalinks();
        new PTAP_Admin_Mods();
        new PTAP_ACF();

    }

    function get_supported_post_types() {

        $post_types = get_post_types( 
            array(
                'public' => true,
                '_builtin' => false
            ) 
        );

        $post_types = apply_filters( 'post_type_archive_pages/supported_post_types', $post_types );

        $post_types = array_filter( $post_types, 'post_type_exists' );

        $post_types = array_map( 'get_post_type_object', $post_types );

        return $post_types;

    }

    function get_config() {

        return get_option( self::CONFIG_KEY );

    }

    public function get_route( $slug ) {

        $id = $this->get_archive_page_id( $slug );

        if ( !$id )
            return null;

        $link = get_permalink( $id );

        if ( !$link )
            return null;

        $slug = str_replace( home_url(), '', $link );

        return trim( $slug, '/' );

    }

    public function get_archive_page_id( $slug ) {

        $config = $this->get_config();

        return isset( $config[$slug] ) ? $config[$slug] : null;

    }

    public function is_archive_page( $page_id ) {

        $post_type = array_search( $page_id, $this->get_config() );

        return ( $post_type ) ? true : false;

    }

    public function get_archive_page_post_type( $page_id ) {

        $post_type = array_search( $page_id, $this->get_config() );

        if ( !$post_type )
            return null;

        return get_post_type_object( $post_type );

    }

    public static function instance()
	{

		if ( is_null( self::$_instance ) ) {
            self::$_instance = new self();
            self::$_instance->includes();
            self::$_instance->initialise();
		}

		return self::$_instance;
	}

}


function post_type_archive_pages() {
	return Post_Type_Archive_Pages::instance();
}

post_type_archive_pages();