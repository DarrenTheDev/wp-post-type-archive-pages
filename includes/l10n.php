<?php

class PTAP_L10n{

    function __construct() {

        add_action( 'plugins_loaded', array( $this, 'load_text_domain' ) );

    }

    function load_text_domain() {

        $langDirectory = dirname( post_type_archive_pages()->basename ) . '/lang/';

        load_plugin_textdomain( 'post-type-archive-pages', false, $langDirectory );

    }

}