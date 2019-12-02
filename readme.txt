=== Post Type Archive Pages ===
Contributors: darrengrant
Donate link: http://paypal.me/darrenthedev
Tags: archive-pages, post-types
Requires at least: 5.0
Tested up to: 5.3
Requires PHP: 5.6
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Place the archive pages for your post types in the structure of your content pages. The power of pages and post types combined!

== Description ==

Post Type Archive Pages allows you to select pages from within your content page structure to be the archive pages for your custom post types. This gives you control of the permalink for your archive pages and allows them to be nested under other pages. It also sets the permalink base for single posts of that post type and term archive for the post type's taxonomies.

Once the plugin is installed and activated you'll be able to select the archive page for each of your registered post types under Settings > Reading.

== Templating ==

WordPress will look for archive page templates for the pages that you set as archive pages. So *archive-$posttype.php* and *archive.php* will be used rather than *page.php*.

There are also a few functions available that may be helpful in building themes. They are accessed on the plugin's main instance, which is returned by a call to *post_type_archive_pages()*.

= get_archive_page =

Returns the page object for the archive page of the provided post type slug. If called from a post type archive template, a singular template or a term archive and a slug isn't provided, the relative post type will be used. e.g. 

`
$page = post_type_archive_pages()->get_archive_page('book');
`

= get_archive_page_post_type =

Returns the post type object that the provided page ID is the archive page for. e.g. 

`
$postType = post_type_archive_pages()->get_archive_page_post_type(5);
`

== Menu - Enhancements ==

Archive pages added to the menu will be marked as the current menu item when viewing the post type archive and the current menu item's parent / ancestor when viewing a post of that type. The related CSS classes will also be applied.

== Advanced Custom Fields - Enhancements ==

This plugin is particularly effective when paired with [Advanced Custom Fields](https://www.advancedcustomfields.com/). It registers additional *page type* location rules allowing you to add fields to all archive pages or the archive page for a particular post type. So if your listing page requires content like a description or header image you can place fields for those on the archive page itself.

To access fields like these in your archive template you need only call 
`
$desc = get_field( 'description', post_type_archive_pages()->get_archive_page() );
`

== Developer Hooks ==

The plugin attempts to set defaults that should work best for most scenarios. But there are some places to hook in and alter these defaults where needed.

= Filter - post_type_archive_pages/supported_post_types =

By default you will be able to set an archive page for any public post type other than the built in post types of *page* and *post*. This hook allows you to filter the default array of supported post types to add or remove. The array should contain slugs of valid post types. Please note that the array keys are not important in it's use, but contain the slugs to allow you to easily unset values.

`
add_filter( 'post_type_archive_pages/supported_post_types', function( $post_types ){
    unset( $post_types['book'] );
    return $post_types;
} );
`

= Filter - post_type_archive_pages/taxonomy_post_type =

By default taxonomy term archives will inherit the archive page permalink structure of a post type if that post type is the only one that the taxonomy is registered on. For instance if you had a *publisher* taxonomy the permalink for a term archive might become */books/publisher/penguin*. With */books* being the permlaink of the archive page, *publisher* the taxonomy slug and *penguin* the term slug. This filter allows you to alter the post type who's archive page permalink structure will be inherited for a given taxonomy. 

`
add_filter( 'post_type_archive_pages/taxonomy_post_type', function( $post_type, $taxonomy ){
    if ( $taxonomy === 'publisher' ) return null;
    return $post_type;
}, 10, 2 );
`

== Screenshots ==

1. Archive pages can be selected under the reading settings.
2. Archive pages are denoted on the pages table view.
3. Why paired with ACF you can add field groups to archive pages.
4. Archive page with an ACF field group.