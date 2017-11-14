<?php 
/**
 * @version 1.0
 * @description Booking Manager - Shortcodes
 * 
 * @author wpdevelop
 * @link http://oplugins.com/
 * @email info@oplugins.com
 *
 * @modified 2017-07-16
 */

if ( ! defined( 'ABSPATH' ) ) exit;                                             // Exit if accessed directly

										
////////////////////////////////////////////////////////////////////////////////////////////
// I M P O R T
////////////////////////////////////////////////////////////////////////////////////////////

/**	Shortcode [] - Import ICS feed and create bookings from this feed.
 * 
 * @param array $attr
 * @return string
 */
function wpbm_ics_import_shortcode( $attr ) {

	ob_start();
	
    $import_status = wpbm_ics_import_start( $attr );

	$echo_results = ob_get_contents();
	
    ob_end_clean();

	if ( ! empty( $attr['silence'] ) ) {                                                                                //FixIn: m.2.0.1.2  - do  not show 'Import XX bookings' message,  if paremeter silence=1 in shortcode
		$import_status_echo = '';
	} else {
		$import_status_echo = sprintf( __( 'Imported %s bookings', 'booking-manager' ), '<strong>' . ( (int) $import_status ) . '</strong>' );
	}

	return $import_status_echo . $echo_results;
}
add_shortcode('booking-manager-import',		'wpbm_ics_import_shortcode' );	




////////////////////////////////////////////////////////////////////////////////////////////
// L I S T I N G
////////////////////////////////////////////////////////////////////////////////////////////
// [booking-manager-listing url='https://server.com/feed.ics' from='2017-08-06' until='week' until_offset='4h' max=500]
// [booking-manager-listing url='https://server.com/feed.ics' from='today' from_offset='5d' until='year-end' until_offset='4h' max=500]
// 								'now'			=> __( 'Now', 'booking-manager' )
//							  , 'today'			=> __( '00:00 Today', 'booking-manager' )
//							  , 'week'			=> __( 'Start of current week', 'booking-manager' )
//							  , 'month-start'	=> __( 'Start of current month', 'booking-manager' )
//							  , 'month-end'		=> __( 'End of current month', 'booking-manager' )
//							  , 'year-start'	=> __( 'Start of current year', 'booking-manager' )
//							  , 'any'			=> __( 'The start of time', 'booking-manager' )
//							  , 'date'			=> __( 'Specific date / time', 'booking-manager' )

/** Shortcode [booking-manager-listing ...] for showing listing from .ics feed
 * 
 * @param array $attr
 */
function wpbm_ics_listing_shortcode( $attr ) {
	
	$listing = wpbm_ics_get_listing( $attr );

	return $listing;
	
}
add_shortcode('booking-manager-listing',	'wpbm_ics_listing_shortcode' );		//[booking-manager-listing url='https://calendar.google.com/calendar/ical/pkqi3dnhq1l4j2qu0id3as984k%40group.calendar.google.com/public/basic.ics' from='2016-08-01' until='2018-08-30']
