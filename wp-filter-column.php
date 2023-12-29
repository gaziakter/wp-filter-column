<?php
/**
 * Plugin Name:       WP Filter Column
 * Plugin URI:        https://classysystem.com/plugin/wp-column/
 * Description:       WordPress Column management
 * Version:           1.0.0
 * Requires at least: 5.2
 * Requires PHP:      7.2
 * Author:            Gazi Akter
 * Author URI:        https://gaziakter.com/
 * License:           GPL v2 or later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Update URI:        https://classysystem.com/
 * Text Domain:       wp-column-filter
 * Domain Path:       /languages
 */

 /**
  * Load textdomain
  */
 function wpcol_textdomain(){
    load_textdomain( "wp-column-filter", false, dirname(__FILE__)."/languages" );
 }
 add_action( "plugins_loaded", "wpcol_textdomain" );


 /**
  * Post filter 
  */
 function wpcol_thumbnail_filter() {
	if ( isset( $_GET['post_type'] ) && $_GET['post_type'] != 'post' ) { //display only on posts page
		return;
	}
	$filter_value = isset( $_GET['THFILTER'] ) ? $_GET['THFILTER'] : '';
	$values       = array(
		'0' => __( 'Thumbnail Status', 'column_demo' ),
		'1' => __( 'Has Thumbnail', 'column_demo' ),
		'2' => __( 'No Thumbnail', 'column_demo' ),
	);
	?>
    <select name="THFILTER">
		<?php
		foreach ( $values as $key => $value ) {
			printf( "<option value='%s' %s>%s</option>", $key,
				$key == $filter_value ? "selected = 'selected'" : '',
				$value
			);
		}
		?>
    </select>
	<?php
}

add_action( 'restrict_manage_posts', 'wpcol_thumbnail_filter' );


function wpcol_thumbnail_filter_data( $wpquery ) {
	if ( ! is_admin() ) {
		return;
	}

	$filter_value = isset( $_GET['THFILTER'] ) ? $_GET['THFILTER'] : '';
	if ( '1' == $filter_value ) {
		$wpquery->set( 'meta_query', array(
			array(
				'key'     => '_thumbnail_id',
				'compare' => 'EXISTS'
			)
		) );
	} else if ( '2' == $filter_value ) {
		$wpquery->set( 'meta_query', array(
			array(
				'key'     => '_thumbnail_id',
				'compare' => 'NOT EXISTS'
			)
		) );
	}

}

add_action( 'pre_get_posts', 'wpcol_thumbnail_filter_data' );