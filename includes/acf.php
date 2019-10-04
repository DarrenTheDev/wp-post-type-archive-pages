<?php

class PTAP_ACF{


    function __construct() {

        add_filter( 'acf/location/rule_values/page_type', array( $this, 'acf_page_types_options' ) );
        add_filter( 'acf/location/rule_match/page_type', array( $this, 'acf_page_types_match' ), 10, 3);

    }

    function acf_page_types_options( $choices ) {

        $archivePageText = __( 'Archive Page', 'post-type-archive-pages' );

        $choices[ 'archive' ] = $archivePageText;

        foreach ( post_type_archive_pages()->get_supported_post_types() as $post_type ) {

            $choices[ 'archive_' . $post_type->name ] = $archivePageText . ': ' . $post_type->label;

        }

        return $choices;

    }

    function acf_page_types_match( $match, $rule, $options ) {

        if ( !isset( $options['post_id'] ) ) 
            return $match;

        $archiveType = post_type_archive_pages()->get_archive_page_post_type( $options['post_id'] );

        if ( $rule['value'] == 'archive' ) {

            if( $rule['operator'] == "==" ) {

                $match = ( $archiveType );

            } elseif($rule['operator'] == "!=") {

                $match = !( $archiveType );

            }

        } elseif ( $archiveType && strpos( $rule['value'], 'archive_' ) !== false ) {

            $postType = str_replace( 'archive_', '', $rule['value'] );

            if( $rule['operator'] == "==" ) {

                $match = ( $archiveType->name == $postType );

            } elseif($rule['operator'] == "!=") {

                $match = ( $archiveType->name != $postType );

            }

        }

        return $match;

    }

}