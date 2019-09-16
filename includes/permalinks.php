<?php

class PTAP_Permalinks {

    function __construct()
    {
        
        add_filter( 'register_post_type_args', [ $this, 'set_post_type_archive' ], 10, 2 );
        add_filter( 'register_taxonomy_args', [ $this, 'set_taxonomy_archive' ], 10, 3 );

    }

    public function set_post_type_archive( $args, $name ) {

        $archiveRoute = post_type_archive_pages()->get_route($name);

        if ( !$archiveRoute )
            return $args;

        $args['has_archive'] = true;

        $args['rewrite'] = [
            'slug' =>           $archiveRoute,
            'with_front' =>     false
        ];

        return $args;

    }

    public function set_taxonomy_archive( $args, $taxonomy, $object_type ) {

        if ( isset($args['public']) && $args['public'] === false )
            return $args;

        if ( is_array( $object_type ) )
            $object_type = ( count($object_type) === 1 ) ? $object_type[0] : null;

        if ( !$object_type )
            return $args;

        $archiveRoute = post_type_archive_pages()->get_route( $object_type );

        if ( !$archiveRoute )
            return $args;

        $args['rewrite'] = [
            'slug' => $archiveRoute . '/' . $taxonomy,
            'with_front' => false
        ];

        return $args;

    }

}