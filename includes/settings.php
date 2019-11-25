<?php

class PTAP_Settings {

    private $supported_post_types;

    function __construct() {

        add_action( 'admin_init', array( $this, 'add_settings_fields' ) );

    }

    function add_settings_fields() {

        $this->supported_post_types = post_type_archive_pages()->get_supported_post_types();

        register_setting(
            'reading',
            post_type_archive_pages()::CONFIG_KEY
        );

        if ( count($this->supported_post_types) ) {

            add_settings_field(
                'archive-pages',
                __( 'Archive Pages', 'post-type-archive-pages' ),
                array( $this, 'draw_fields' ),
                'reading'
            );

        }

    }

    function draw_fields() {
        ?>
        <p>Select the page to display the archive for each of your post types.</p>
        <br>
        <fieldset>

            <?php foreach( $this->supported_post_types as $post_type ) : ?>

                <?php
                $field_name = post_type_archive_pages()::CONFIG_KEY . '[' . $post_type->name . ']';
                $field_label = $post_type->label;
                $field_value = post_type_archive_pages()->get_archive_page_id( $post_type->name );
                ?>

                <label for="<?php echo $field_name ?>">
                    <?php
                    printf(
                        $field_label . ': %s',
                        wp_dropdown_pages(
                            array(
                                'name'              => $field_name,
                                'echo'              => 0,
                                'show_option_none'  => __( '&mdash; Select &mdash;' ),
                                'option_none_value' => '0',
                                'selected'          => $field_value,
                            )
                        )
                    );
                    ?>
                </label><br>

            <?php endforeach ?>

        </fieldset>
        <?php
    }

    

}