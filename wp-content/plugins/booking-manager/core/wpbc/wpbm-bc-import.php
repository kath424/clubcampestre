<?php 
/**
 * @version 1.0
 * @description Booking Calendar integration - Import bookings from ICS events
 * 
 * @author wpdevelop
 * @link http://oplugins.com/
 * @email info@oplugins.com
 *
 * @modified 2017-06-28
 */

if ( ! defined( 'ABSPATH' ) ) exit;                                             // Exit if accessed directly

										
////////////////////////////////////////////////////////////////////////////////////////////
// I M P O R T
////////////////////////////////////////////////////////////////////////////////////////////

/**	Import ICS feed and create bookings .in Booking Calendar 
 * 
 * @param array $attr = array(
								'url' => ''
							  , 'resource_id' => 1
							  , 'import_conditions' => '' | 'if_dates_free'				// maybe rename ???		if_dates_booked_then_not_import
							)
 * @return int|bool		number of imported bookings or FALSE if error
 */
function wpbm_ics_import_start( $attr ) {

	//                                                                              <editor-fold   defaultstate="collapsed"   desc=" Parse / validate  parameters " >    
	/////////////////////////////////////////////////////////////////////
	// Parse / validate  parameters
	/////////////////////////////////////////////////////////////////////
	$defaults = array(
						'url' => ''
					  , 'resource_id' => 1
					  , 'import_conditions' => ''	
					  , 'from' => 'any'				// 'today'		// '00:00 today'
					  , 'from_offset' => ''
					  , 'until' => 'any'			// 'year-end'
					  , 'until_offset' => ''
					  , 'max' => ''
					  , 'is_all_dates_in' => true	// Conditional of Dates checking.  TRUE - Remove event if al least 1 day not in conditional interval,  FALSE - save event, if at leat one date in conditional interval
	);
	$shortcode = array();

	foreach ( $attr as $param_name => $param_value ) {

		switch ( $param_name) {							// Validate Params

			case 'url':					
				$shortcode[ $param_name ] = esc_url_raw( $param_value );		// $shortcode[ 'url' ]
				break;

			case 'import_conditions':
				$shortcode[ $param_name ] = esc_attr( $param_value );				// $shortcode[ 'resource_id' ] 
				break;
				
			case 'resource_id':					
				$shortcode[ $param_name ] = intval($param_value );				// $shortcode[ 'resource_id' ] 
				break;
			
			case 'from':										// 'now', 'today', 'week', 'month-start', 'month-end', 'year-start', 'any', 'date' = 2017-08-07
				$shortcode[ $param_name ] = $param_value;
				break;
						
			case 'from_offset':									// 5d,  10h, 5m, 30s	--	if  from: { 'now', 'today', 'week', 'month-start', 'month-end', 'year-start' }
				$shortcode[ $param_name ] = $param_value;				
				break;
			
			case 'until':										// 'now', 'today', 'week', 'month-start', 'month-end', 'year-end', 'any', 'date' = 2017-08-07
				$shortcode[ $param_name ] = $param_value;
				break;
						
			case 'until_offset':								// 5d,  10h, 5m, 30s	--	if  until: { 'now', 'today', 'week', 'month-start', 'month-end', 'year-end' }
				$shortcode[ $param_name ] = $param_value;				
				break;
			
			case 'max': 
				$shortcode[ $param_name ] = intval( $param_value );				
				break;
			
			case 'is_all_dates_in':								// Conditional of Dates checking.  TRUE - Remove event if al least 1 day not in conditional interval,  FALSE - save event, if at leat one date in conditional interval
				$shortcode[ $param_name ] = intval( $param_value );				
				break;
			
			
			default:
				$shortcode[ $param_name ] = $param_value;
				break;
		}			
	}		
	$shortcode = wp_parse_args( $shortcode, $defaults );
	//                                                                              </editor-fold>
		

	//                                                                              <editor-fold   defaultstate="collapsed"   desc=" Notice - Import Parameters  |  Error No URL" >    	
	
	do_action( 'wpbc_show_debug', array( 'Import Parameters' , $shortcode ) );											//  S_Y_S_T_E_M    L_O_G

	if ( empty( $shortcode[ 'url' ] ) ) {		
		do_action( 'wpbc_admin_show_top_notice', __( 'No ics url feed', 'booking-manager' ), 'error', 5000 );					// N_O_T_I_C_E  in  H_E_A_D_E_R		
		return  false;
	}	
	//                                                                              </editor-fold>
	

	/////////////////////////////////////////////////////////////////////	-	Get, Parse ICS Feed	
	
	$ics_array = wpbm_ics_file_to_array( $shortcode[ 'url' ] );

	//                                                                              <editor-fold   defaultstate="collapsed"   desc=" Notice - feed contain N events  |  Error Importing " >    
	do_action( 'wpbc_show_debug', array( 'Imported data' , $ics_array) );												//  S_Y_S_T_E_M    L_O_G

	// If Error
	if ( is_wp_error( $ics_array ) ) {
		
		$error_message = $ics_array->get_error_message();		
		do_action( 'wpbc_admin_show_top_notice', $error_message, 'error', 5000 );										// N_O_T_I_C_E  in  H_E_A_D_E_R		
		return  false;
	}

	do_action( 'wpbc_admin_show_top_notice'																				// N_O_T_I_C_E  in  H_E_A_D_E_R
			, sprintf ( __( '.ics feed contain %s events at URL %s', 'booking-manager' ), '<b>' . count( $ics_array[ 'events' ] ) . '</b>', '<b><a href="'. $shortcode[ 'url' ] .'">' . $shortcode[ 'url' ] . '</a></b>' )
			, 'info', 5000 );			
	//                                                                              </editor-fold>
		
	$bk_array = array();
	// Get Only '_BOOKING...' field from  ICS array 
	if ( $ics_array !== false ) {				
		$bk_array = wpbm_get_booking_fields_from_ics_array( $ics_array[ 'events' ] );									
	}	
	
	do_action( 'wpbc_show_debug', array( 'Imported Events', $bk_array ) );												//  S_Y_S_T_E_M    L_O_G
				 
	 
	/////////////////////////////////////////////////////////////////////	-	Check if these EVENTS was imported before or NOT

	$bk_array = wpbm_clear_events_from_exist_bookings( $bk_array );	


	//                                                                              <editor-fold   defaultstate="collapsed"   desc=" Notice - if events was imported previously  |  Already ALL Imported " >
	do_action( 'wpbc_show_debug', array( 'Check, if events was imported previously. New events: ', $bk_array ) );		//  S_Y_S_T_E_M    L_O_G

	if ( empty( $bk_array ) ) {
		
		do_action( 'wpbc_admin_show_top_notice'																			// N_O_T_I_C_E  in  H_E_A_D_E_R	
			, '<strong>' . __( 'Warning', 'booking' ) . '!</strong> ' 
			  . sprintf( __( 'No any new events to import! These events was import previously, already.', 'booking-manager' ), '<strong>' . count( $bk_array ) . '</strong>' )
			, 'warning', 3000 );			
		return 0;	
	}
	//                                                                              </editor-fold>
		
	
	/////////////////////////////////////////////////////////////////////	-	Skip events that  does not fit to filter parameters: FROM - UNTIL 
	
	// $bk_array = wpbm_clear_events_by_dates( $bk_array, $shortcode );


	// Sort Events
	$bk_array = wpbm_sort_events_by( $bk_array );

	// Filter By Dates		-	"From - Until"	
	$bk_array = wpbm_clear_events_by_dates( $bk_array, $shortcode );

	// Filter events by		-	"Max"	
	$bk_array = wpbm_clear_events_by_count( $bk_array, $shortcode );

	
	//                                                                              <editor-fold   defaultstate="collapsed"   desc=" Notice - Creation of N bookings " >    
	do_action( 'wpbc_admin_show_top_notice'																				// N_O_T_I_C_E  in  H_E_A_D_E_R
			, sprintf( __( 'Imported of %s bookings', 'booking-manager' ), '<strong>' . count( $bk_array ) . '</strong>' )
			, 'info', 3000 );			
			
	do_action( 'wpbc_show_debug', array( 'Create bookings after filtering', $bk_array ) );								//  S_Y_S_T_E_M    L_O_G
	//                                                                              </editor-fold>
				
	/////////////////////////////////////////////////////////////////////	-	Loop events  >  C r e a t e    B o o k i n g s

	// Get assigning fields  for  SUMMARY, DESCRIPTION, LOCATION	
	$assigned_fields_arr = WPBM_create_bookings_from_events::get_assigned_form_fields();
	
	$booking_added_num = 0;
	
	foreach ( $bk_array as $ics_event) {

		$booking_data = array();

		$dates_formats = array_fill( 0, count( $ics_event[ '_BOOKING_DATES' ] ), "Y-m-d" );								// Array ( [0] => Y-m-d [1] => Y-m-d	...  )
		$booking_dates_unix = array_map( 'strtotime', $ics_event[ '_BOOKING_DATES' ] );									// Array ( [0] => 1498262400 [1] => 1498348800 )
		$simple_booking_dates = array_map( 'date_i18n', $dates_formats , $booking_dates_unix );							// Array ( '2017-06-23', '2017-06-24', '2017-06-25', '2017-06-26' )

		// Tempoary save our dates
		WPBM_create_bookings_from_events::set_ics_dates( $ics_event[ '_BOOKING_DATES' ] );

		$booking = array(
						  'dates'		=> $simple_booking_dates														// array( '2017-06-24', '2017-06-24', '2017-06-25', '2017-06-26' )
						, 'data'		=> array()
						, 'resource_id' => $shortcode[ 'resource_id' ]				 
		);


		$bk_data = array();
		foreach ( $assigned_fields_arr as $assigned_field ) {

			switch ( $assigned_field[ 'ics_field_name' ] ) {

				case 'title':						
					$bk_data [ $assigned_field[ 'name' ] ]= array( 'type' => $assigned_field[ 'type' ], 'value' => trim( $ics_event[ '_BOOKING_SUMMARY' ] ) );
					break;

				case 'description':						
					$bk_data [ $assigned_field[ 'name' ] ]= array( 'type' => $assigned_field[ 'type' ], 'value' => trim( $ics_event[ '_BOOKING_DESCRIPTION' ] ) );
					break;

				case 'where':						
					$bk_data [ $assigned_field[ 'name' ] ]= array( 'type' => $assigned_field[ 'type' ], 'value' => trim( $ics_event[ '_BOOKING_LOCATION' ] ) );
					break;

				default:
					break;
			}
		}

		// Email
		$home_url = explode( '://', home_url() );			
		if (count($home_url>0))
			$email = 'ics@' . $home_url[1];
		else 
			$email = get_option ( 'admin_email' );
		$bk_data [ 'email' ] = array( 'value' => $email, 'type' => 'email' );

		// Optional Start  and End times
		$start_time = substr( $ics_event[ '_BOOKING_DATES' ][ 0 ], 11, 5 );
		$end_time	= $ics_event[ '_BOOKING_DATES' ][ ( count( $ics_event[ '_BOOKING_DATES' ] ) - 1 ) ];
		$end_time	= substr( $end_time, 11, 5 );

		if ( ( $start_time !== '00:00' ) || ( $end_time !== '00:00' ) ) {
			$bk_data [ 'rangetime' ] = array( 'value' => $start_time . ' - ' . $end_time, 'type' => 'select-one' );
		}

		$booking['data'] = $bk_data;

		$additional_params = array( 'sync_gid' => $ics_event[ '_BOOKING_UID' ] );

		/**
		//		$ics_event[ '_BOOKING_DATES' ];			// array ( [0] => 2014-09-16 05:00:01		[1] => 2014-09-16 12:00:02 )
		//		$ics_event[ '_BOOKING_SUMMARY' ];		// Event (timezone Pacific GMT-07:00)
		//		$ics_event[ '_BOOKING_DESCRIPTION' ];	// 8/7/2017 1:00pm  TO   3:30pm  8/8/2017  (GMT-07:00) Pacific Time
		//		$ics_event[ '_BOOKING_LOCATION' ];		// Pacific Coast Highway, Pacific Coast Hwy, Los Angeles, CA, USA
		//		$ics_event[ '_BOOKING_UID' ];			// 5t3ogfsb3tqj09po7fiou6hh60@google.com
		// $ics_event[ '_BOOKING_MODIFIED' ];			// 2017-06-28 10:12:35			
		*/
		
		add_filter( 'wpbc_get_insert_sql_for_dates',			 'wpbm_get_insert_sql_for_dates', 10, 5 );
		
		// Do  not overupdate "child booking resources" when  saving bookings to  parent booking resource. Just skip  this checking
		add_filter( 'wpbc_is_reupdate_dates_to_child_resources', 'wpbm_is_reupdate_dates_to_child_resources', 10, 7 );	

		// Check dates availability and process 
		// only  if dates available in specific booking resource!
		if ( $shortcode[ 'import_conditions' ] == 'if_dates_free' )
			$is_dates_booked = wpbc_api_is_dates_booked( $booking[ 'dates' ], $booking[ 'resource_id' ] );
		else
			$is_dates_booked = false;

		if ( ! $is_dates_booked ) {
			
			$booking_id = wpbc_api_booking_add_new( $booking[ 'dates' ], $booking[ 'data' ], $booking[ 'resource_id' ] , $additional_params );			
			$booking_added_num++;

			do_action( 'wpbc_show_debug', sprintf ( 'Added new booking ID:<strong>%d</strong> ', $booking_id ) );						//  S_Y_S_T_E_M    L_O_G
		} else {

			do_action( 'wpbc_show_debug',																				//  S_Y_S_T_E_M    L_O_G
				sprintf ( 'Event was not create becausse dates %s already booked in booking resource ID = %d'
						, implode( ', ', $booking[ 'dates' ] ) , $booking[ 'resource_id' ] ) );			
		}

		remove_filter( 'wpbc_get_insert_sql_for_dates',				'wpbm_get_insert_sql_for_dates', 10 );
		remove_filter( 'wpbc_is_reupdate_dates_to_child_resources', 'wpbm_is_reupdate_dates_to_child_resources', 10 );

		// Remove previously saved dates to our 'Static' class.
		WPBM_create_bookings_from_events::erase_ics_dates();			
	}
	
	return $booking_added_num;	
}
add_action( 'wpbm_ics_import_start', 'wpbm_ics_import_start', 10, 1 );


////////////////////////////////////////////////////////////////////////////////////////////
// Booking Calendar support functions
////////////////////////////////////////////////////////////////////////////////////////////


/** Clear array  of Events from events that  already  exist  in Booking table
 *  Check UID and GUID sync paramaters
 * 
 * @param array $bk_array		- array of events
 * @return array				- trimmed array
 */
function wpbm_clear_events_from_exist_bookings( $bk_array ) {
	
	$bk_uid = $bk_guid = array();
	// GET array of UID for imported bookings
	foreach ( $bk_array as $ics_key => $ics_event) {
		
		$bk_uid[ $ics_key ] = $ics_event[ '_BOOKING_UID' ];
		
		if ( strpos( $ics_event[ '_BOOKING_UID' ], '@google.com') !== false ) {
			// 15ig8t0i739kajgjc8ekc386dt@google.com	-  ID of event from  ICS
			// 15ig8t0i739kajgjc8ekc386dt_20170815		-  ID of event during import from Google Calendar  (probabaly  created by ID before @ + first  date)

			$bk_guid[ $ics_key ] = str_replace( '@google.com'
									, date_i18n( '_Ymd', strtotime( $ics_event[ '_BOOKING_DATES' ][0] ) )
									,  $ics_event[ '_BOOKING_UID' ] 
								);
		}		
	}	
	$bookings_check_uid = array_merge( $bk_uid,$bk_guid );
	
	$bookings_exit_uid = wpbm_get_exist_bookings_gid(  $bookings_check_uid );
	
	// Remove events which already  exist
	foreach ( $bk_uid as $ics_key => $ics_uid ) {
		
		// If exist UID remove it from  event
		if ( in_array( $ics_uid, $bookings_exit_uid ) ) {
			unset( $bk_array[ $ics_key ] );
		}
		
		// If we are having such  GUID and it exist, then  remove ir
		if ( isset( $bk_guid[ $ics_key ] ) ) {
			$ics_gid = $bk_guid[ $ics_key ];
			if ( in_array( $ics_uid, $bookings_exit_uid ) ) {
				unset( $bk_array[ $ics_key ] );
			}
		}
	}
	////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	
	return $bk_array;
}


	/** Check  if bookings exist  with  specific sync UID
	 * 
	 * @global type $wpdb
	 * @param array of UID to  check 
	 * @return array of exist UID
	 */
	function wpbm_get_exist_bookings_gid( $uid_arr ) {

		$sql_sync_gid = implode( "','", $uid_arr );

		$exist_bookings_guid = array();

		if ( ! empty( $sql_sync_gid ) ) {
			global $wpdb;

			$exist_bookings = $wpdb->get_results( "SELECT * FROM {$wpdb->prefix}booking WHERE sync_gid IN ('{$sql_sync_gid}')" );

			foreach ( $exist_bookings as $bk ) {
				$exist_bookings_guid[] = $bk->sync_gid;
			}
		}
		return $exist_bookings_guid;
	}


/** Trim  number of events in array
 * 
 * @param array $bk_array
 * @param array $shortcode
 * @return array
 */	
function wpbm_clear_events_by_count( $bk_array, $shortcode ) {
				
	if ( empty( $shortcode['max'] ) ) {
		return $bk_array;
	} else {
		$max = intval( $shortcode['max'] );
	}
	
	$bk_count= count( $bk_array );
	if ( $max > $bk_count )
		return $bk_array;
	
	$cnt = 0;
	$events_arr = array();
	
	foreach ( $bk_array as $ev_key => $ev_arr ) {
		
		$events_arr[] = $ev_arr;
		
		$cnt++;
		if ( $cnt >= $max ) 
			break;
	}
				
	return $events_arr;
}	
	



/** Remove ONLY dates from event(s) that  does not fit to filter parameters: FROM - UNTIL -- All events will exist here
 * 
 * @param array $bk_array		- array of events
 * @param array $shortcode
 * @return array				- trimmed array
 */	
function wpbm_remove_dates_from_event_not_in_condition( $bk_array, $shortcode ) {
	
	if ( ( ! isset( $shortcode['from'] ) ) && ( ! isset( $shortcode['until'] ) ) ) {
		return $bk_array;
	}
	
	//                                                                              <editor-fold   defaultstate="collapsed"   desc=" F R O M    C o n d " >    

	// F R O M
	$offset = wpbm_get_offset_unix_from_param( $shortcode[ 'from_offset' ] );
	
	if ( $shortcode['from'] == 'any' )		$shortcode['from'] = 'any-start';	
	if ( $shortcode['from'] == 'week' )		$shortcode['from'] = 'week-start';	
	
	$condition_from = wpbm_get_time_unix_from_param_offset( $shortcode['from'], $offset );
	
	//                                                                              </editor-fold>
	

	//                                                                              <editor-fold   defaultstate="collapsed"   desc=" U N T I L   C o n d " >    
	
	//  U N T I L	
	$offset = wpbm_get_offset_unix_from_param( $shortcode[ 'until_offset' ] );
		
	if ( $shortcode['until'] == 'any' )		$shortcode['until'] = 'any-end';	
	if ( $shortcode['until'] == 'week' )	$shortcode['until'] = 'week-end';	
	
	$condition_until = wpbm_get_time_unix_from_param_offset( $shortcode['until'], $offset );
	
	//                                                                              </editor-fold>
	
	
	foreach ( $bk_array as $e_key => $ics_event ) {
		
		$new_dates = array();
//debuge($ics_event[ '_BOOKING_DATES' ] );		
		foreach ( $ics_event[ '_BOOKING_DATES' ] as $d_key => $ics_date ) {
			
			$ics_date_unix = strtotime( $ics_date );
//debuge( $d_key, $ics_date , array( $condition_from, $condition_until ) );			
			if ( ( $condition_from <= $ics_date_unix ) && ( $condition_until >= $ics_date_unix ) ) {			
				
				$new_dates[] = $ics_date;
			}
		}
//debuge( $new_dates ); die;		
		$bk_array[ $e_key ][ '_BOOKING_DATES' ] = $new_dates;
	}
	
	return $bk_array;
}


/** Skip events that  does not fit to filter parameters: FROM - UNTIL
 * 
 * @param array $bk_array		- array of events
 * @param array $shortcode
 * @return array				- trimmed array
 */	
function wpbm_clear_events_by_dates( $bk_array, $shortcode ) {

	if ( ( ! isset( $shortcode['from'] ) ) && ( ! isset( $shortcode['until'] ) ) ) {
		return $bk_array;
	}
	
	//                                                                              <editor-fold   defaultstate="collapsed"   desc=" F R O M    C o n d " >    

	// F R O M
	$offset = wpbm_get_offset_unix_from_param( $shortcode[ 'from_offset' ] );
	
	if ( $shortcode['from'] == 'any' )		$shortcode['from'] = 'any-start';	
	if ( $shortcode['from'] == 'week' )		$shortcode['from'] = 'week-start';	
	
	$condition_from = wpbm_get_time_unix_from_param_offset( $shortcode['from'], $offset );
	
	//                                                                              </editor-fold>
	

	//                                                                              <editor-fold   defaultstate="collapsed"   desc=" U N T I L   C o n d " >    
	
	//  U N T I L	
	$offset = wpbm_get_offset_unix_from_param( $shortcode[ 'until_offset' ] );
		
	if ( $shortcode['until'] == 'any' )		$shortcode['until'] = 'any-end';	
	if ( $shortcode['until'] == 'week' )	$shortcode['until'] = 'week-end';	
	
	$condition_until = wpbm_get_time_unix_from_param_offset( $shortcode['until'], $offset );
	
	//                                                                              </editor-fold>

	// Conditional of Dates checking.  TRUE - Remove event if al least 1 day not in conditional interval,  FALSE - save event, if at leat one date in conditional interval
	if ( ! isset( $shortcode['is_all_dates_in'] ) )
		$is_all_dates_in_condition = true;	
	else 
		$is_all_dates_in_condition = (bool) $shortcode['is_all_dates_in'];	
	
//debuge($condition_from, date_i18n('Y-m-d H:is',$condition_from));
//debuge($condition_until, date_i18n('Y-m-d H:is',$condition_until));
//debuge( (int) $is_all_dates_in_condition );
//die;
	// Remove some events,  that does not inside From | End time
	$remove_events_keys = array();	
	foreach ( $bk_array as $e_key => $ics_event ) {
		foreach ( $ics_event[ '_BOOKING_DATES' ] as $d_key => $ics_date ) {
			$ics_date = strtotime( $ics_date );
			
			if ( $is_all_dates_in_condition ) {
				// STRICT - ALL DATES
				if ( ( $condition_from > $ics_date ) || ($condition_until < $ics_date ) ) {
					
					$remove_events_keys[] = $e_key;
					continue;
				}
			} else {
				// AT LEAST 1 DATE
				if ( ( $condition_from <= $ics_date ) && ( $condition_until >= $ics_date ) ) {
					
					$remove_events_keys = array_diff( $remove_events_keys, array( $e_key ) );	// Remove values "$e_key" from array
					break;
				} else {
					if ( ! in_array( $e_key, $remove_events_keys ) ) {
						$remove_events_keys[] = $e_key;
					}
				}
				
			}
			
		}
	}
	
	// Remove them
	$remove_events_keys = array_unique( $remove_events_keys );
	
//debuge( $bk_array, $remove_events_keys);	
	foreach ( $remove_events_keys as $evnt_key ) {
		unset( $bk_array[ $evnt_key ] );
	}

	return $bk_array;
}


/** Get time offset in seconds based on parameter
 * 
 * @param string $offset_param		30s | 5m | 2h | 7d | just seconds
 * @return int
 */
function wpbm_get_offset_unix_from_param( $offset_param ) {
	
	$offset = 0;	
	if ( ! empty( $offset_param ) ) {
		
		$offset_type  = substr( $offset_param, -1 );
		$offset_value = substr( $offset_param, 0, -1 );
				
		switch ( $offset_type ) {
			case "s":  // Seconds	
				$offset = intval( $offset_value );
				break;
			case "m":  // Minutes
				$offset = intval( $offset_value ) * 60 ;
				break;
			case "h":  // Hours
				$offset = intval( $offset_value ) * 3600 ;
				break;
			case "d":  // Days
				$offset = intval( $offset_value ) * 86400 ;
				break;
			default:
				$offset = intval( $offset_value );
		}   
	}        
	return $offset;
}


/** Get Unix Time based on date parameter / condition and offset in seconds
 * 
 * @param string $check_day		- date type:			// 'now' | 'today' | 'week' == 'week-start' | 'week-end' | 'month-start' | 'month-end' | 'year-start' | 'year-end' | 'any' == 'any-end' | 'any-start' | '2017-08-07'
 * @param int $offset_unix		- offset in seconds
 * @return int					- Unix time in seconds
 */
function wpbm_get_time_unix_from_param_offset( $check_day, $offset_unix ) {
	
//		$condition_end = strtotime ( $shortcode['until'] . ' +1 day - 1 second' );
		
	$check_sql_day = explode( '-', $check_day );
	if ( count( $check_sql_day ) == 3 ) {
		$check_day = 'date';
	} 

	switch ( $check_day ) {
		// Don't just use time() for 'now', as this will effectively make cache duration 1 second. 
		// Instead set to previous minute. Events in Google Calendar cannot be set to precision of seconds anyway
		case 'now':
						
			//$time_unix = strtotime( date_i18n( 'Y-m-d H:i:s' ) ) + $offset_unix;		// "Now" in "Timezone" from WordPress > Settings 
			$time_unix = mktime( date_i18n( 'H' ), date_i18n( 'i' ), 0, date_i18n( 'm' ), date_i18n( 'j' ), date_i18n( 'Y' ) ) + $offset_unix ;			
			break;
		case 'today':
			//$time_unix = strtotime( date_i18n( 'Y-m-d 00:00:00' ) ) + $offset_unix;		// "Today 00:00" in "Timezone" from WordPress > Settings 
			$time_unix = mktime( 0, 0, 0, date_i18n( 'm' ), date_i18n( 'j' ), date_i18n( 'Y' ) ) + $offset_unix ;
			break;
		case 'week':
		case 'week-start':
			
			$start_of_week = get_wpbm_option( 'wpbm_start_day_weeek' ); //get_option( 'start_of_week' );		
			if ( empty( $start_of_week ) ) 
				$start_of_week = 0;
			
			$start_day = date_i18n( 'w' ) - $start_of_week ;
			if ( $start_day < 0 ) 
				$start_day = 7 + $start_day;

			$time_unix = mktime( 0, 0, 0, date_i18n( 'm' ), (   date_i18n( 'j' ) - $start_day ), date_i18n( 'Y' ) ) + $offset_unix ;
			break;
		case 'week-end':
			
			$start_of_week = get_wpbm_option( 'wpbm_start_day_weeek' ); //get_option( 'start_of_week' );
			if ( empty( $start_of_week ) ) 
				$start_of_week = 0;
			
			$start_day = date_i18n( 'w' ) - $start_of_week ;
			if ( $start_day < 0 ) 
				$start_day = 7 + $start_day;			
																														// minus 1 second -- prevent of events exactly ==  start Next period
			$time_unix = mktime( 0, 0, 0, date_i18n( 'm' ), (   date_i18n( 'j' ) - $start_day + 7 ), date_i18n( 'Y' ) ) + $offset_unix - 1; 
			break;
		case 'month-start':
			$time_unix =  mktime( 0, 0, 0, date_i18n( 'm' ), 1, date_i18n( 'Y' ) ) + $offset_unix ;
			break;
		case 'month-end':
			$time_unix =  mktime( 0, 0, 0, date_i18n( 'm' ) + 1, 1, date_i18n( 'Y' ) ) + $offset_unix - 1;				// minus 1 second -- prevent of events exactly ==  start Next period
			break;
		case 'year-start':
			$time_unix =  mktime( 0, 0, 0, 1, 1, date_i18n( 'Y' ) ) + $offset_unix ;
			break;			
		case 'year-end':
			$time_unix =  mktime( 0, 0, 0, 1, 1, date_i18n( 'Y' ) + 1 ) + $offset_unix - 1;								// minus 1 second -- prevent of events exactly ==  start Next period
			break;			
		case 'date':

			if ( intval( $check_sql_day[0] ) - intval( date('Y') ) > 15 ) {                                             //FixIn m.2.0.1
				$time_unix =2145916800;
			} else {
				$time_unix = mktime( 0, 0, 0, intval( $check_sql_day[1] ), intval( $check_sql_day[2] ), intval( $check_sql_day[0] ) );
			}
			break;
		case 'any-start':			
			$time_unix =  0;													// any - 1970-01-01 00:00
			break;
		case 'any-end':			
			$time_unix =  2145916800;											//any - 2038-01-01 00:00
			break;
		default:
			$time_unix =  2145916800;											//any  END - 2038-01-01 00:00
	}

//debuge($time_unix, date_i18n('Y-m-d H:i:s (D)',$time_unix));	


	return $time_unix;
}