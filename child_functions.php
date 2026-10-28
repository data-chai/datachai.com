<?php
add_filter( 'wp_sitemaps_add_provider', function( $provider, $name ) {
    if ( 'users' === $name ) {
        return false;
    }
    return $provider;
}, 10, 2 );


/**
 * Allow indexing of Luxeritas Custom Global Navigation pages.
 */
add_action( 'admin_menu', function() {
    remove_meta_box( 'thk_robots', 'page', 'side' );

    add_meta_box(
        'thk_robots',
        __( 'Search Engine Visibility', 'luxeritas' ),
        'child_thk_robots_box',
        'page',
        'side',
        'default'
    );
}, 20 );

function child_thk_robots_box() {
    global $post;

    $template = get_post_meta(
        $post->ID,
        '_wp_page_template',
        true
    );

    $is_custom_menu = (
        stripos( $template, 'pages/wrapper-menu.php' ) !== false
    );

    $robots = explode(
        ',',
        (string) get_post_meta( $post->ID, 'thk_robots', true )
    );

    $labels = array(
        'thk_noindex'      => 'Noindex',
        'thk_nofollow'     => 'Nofollow',
        'thk_noarchive'    => 'Noarchive',
        'thk_noimageindex' => 'Noimageindex',
    );

    $i = 0;

    echo '<div>';

    foreach ( $labels as $name => $label ) {
        $checked = (
            !$is_custom_menu &&
            isset( $robots[$i] ) &&
            $robots[$i] === '1'
        );

        echo '<p class="meta-options"><label>';
        echo '<input type="checkbox" name="' . esc_attr( $name ) . '" value="1"';
        checked( $checked );
        echo ' /> ' . esc_html( $label );
        echo '</label></p>';

        $i++;
    }

    echo '</div>';
}
?>
