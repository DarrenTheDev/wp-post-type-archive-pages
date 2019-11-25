<?php

class PTAP_Menus {


    function __construct() {

        add_filter( 'wp_nav_menu_objects', [ $this, 'current_archive_pages' ] );

    }

    function current_archive_pages( $menu_items ) {

        if ( is_page() )
			return $menu_items;

    	if ( !is_post_type_archive() && !is_singular() )
    		return $menu_items;

    	$post_type = get_post_type();
		$archive_page = post_type_archive_pages()->get_archive_page( $post_type );
		$ancestors = [];

		if ( !$archive_page )
			return $menu_items;

		foreach ( $menu_items as $index => $item ) {

			if ( $item->object !== 'page' || (int)$item->object_id !== $archive_page->ID )
				continue;

			if ( is_post_type_archive() ) {

				$item->current = true;

				$item->classes = array_merge( $item->classes, [
					'current-menu-item'
				] );

			} elseif ( is_singular() ) {

                $item->current_item_parent = true;
                $item->current_item_ancestor = true;

				$item->classes = array_merge( $item->classes, [
                    'current-menu-parent',
                    'current-menu-ancestor'
				] );

			}

			if ( $item->menu_item_parent != 0 ) {
				$ancestors[] = $item->menu_item_parent;
			}

			$menu_items[$index] = $item;

			$menu_items = $this->mark_menu_ancestor( $menu_items, (int)$item->menu_item_parent );

		}

        return $menu_items;

    }

    function mark_menu_ancestor( $menu_items, $ancestor_id ) {

        if ( $ancestor_id == 0 )
    		return $menu_items;

    	foreach ( $menu_items as $index => $item ) {

    		if ( $item->ID !== $ancestor_id )
    			continue;

			$item->current_item_ancestor = true;

			$item->classes = array_merge( $item->classes, [
				'current-menu-ancestor'
			] );

			$menu_items[$index] = $item;
			$menu_items = $this->mark_menu_ancestor( $menu_items, (int)$item->menu_item_parent );

		}

		return $menu_items;

    }


}