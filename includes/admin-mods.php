<?php

class PTAP_Admin_Mods {

    function __construct() {

        add_action( 'admin_init', array( $this, 'hide_editor' ), 5 );
        add_action( 'theme_page_templates', array( $this, 'hide_page_templates' ), 50, 3 );
        add_action( 'edit_form_after_title', array( $this, 'editing_message' ) );
        add_filter( 'display_post_states', array( $this, 'add_display_post_states' ), 10, 2 );

    }

    function hide_editor() {

        $action = isset($_GET['action']) ? sanitize_text_field($_GET['action']) : null;
        $post_id = isset($_GET['post']) ? intval($_GET['post']) : null;

        if ( $action !== 'edit' || !$post_id )
            return;

        $post_type = get_post_type($post_id);

        if ( $post_type !== 'page' || !post_type_archive_pages()->get_archive_page_post_type($post_id) )
            return;

        remove_post_type_support('page', 'editor');  

    }

    function hide_page_templates( $templates, $theme, $post ) {

        if ( !$post )
            return $templates;

        if ( post_type_archive_pages()->get_archive_page_post_type($post->ID) )
            return null;

        return $templates;

    }

    function editing_message( $post ) {

        if ( $post->post_type !== 'page' )
            return;

        $post_type = post_type_archive_pages()->get_archive_page_post_type( $post->ID );

        if ( !$post_type )
            return;

        $noticeText = __( 'You are currently editing the page that shows your %s archive.', 'post-type-archive-pages' );
        $noticeText = sprintf( $noticeText, strtolower($post_type->label) );

        printf( '<div class="notice notice-warning inline"><p>%s</p></div>', $noticeText );

    }

    function add_display_post_states( $post_states, $post ) {

        $post_type = post_type_archive_pages()->get_archive_page_post_type( $post->ID );

        if ( !$post_type )
            return $post_states;

        $stateText = __( '%s Archive Page', 'post-type-archive-pages' );
        $stateText = sprintf( $stateText, $post_type->label );

        $post_states['ptap_archive_page'] = $stateText;
        
        return $post_states;

    }

}