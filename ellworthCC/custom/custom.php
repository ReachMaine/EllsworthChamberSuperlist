<?php /*  custom functions for ellsworth chamber superlist theme
*/
// disable street view
add_filter( 'inventor_metabox_field_enabled', 'disable_gmap_views', 10, 4 );
function disable_gmap_views( $enabled, $metabox_id, $field_id, $post_type ) {
    if ( ( 'listing_street_view' == $field_id ) || ('listing_inside_view' == $field_id) ) {
        return false;
    }

    return $enabled;
}
