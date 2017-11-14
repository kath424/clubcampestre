<?php
/**
 * @version 1.0
 * @package Booking Manager 
 * @subpackage Define Constants
 * @category Items
 * 
 * @author wpdevelop
 * @link http://oplugins.com/
 * @email info@oplugins.com
 *
 * @modified 2014.05.17
 */

if ( ! defined( 'ABSPATH' ) ) exit;                                             // Exit if accessed directly

////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//   USERS  CONFIGURABLE  CONSTANTS           //////////////////////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

//if ( ! defined( 'WPBM_IS_SEND_EMAILS' ) )		define( 'WPBM_IS_SEND_EMAILS', false );									// FixIn: 1.0.1.1   // Is send modification email

////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//   SYSTEM  CONSTANTS                        //////////////////////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
if ( ! defined( 'WPBM_VERSION_NUM' ) )		define( 'WPBM_VERSION_NUM',		'2.0.2' );
if ( ! defined( 'WPBM_MINOR_UPDATE' ) )		define( 'WPBM_MINOR_UPDATE',	true );
if ( ! defined( 'WPBM_MIN' ) )				define( 'WPBM_MIN',				false );									// TODO: Finish it.
if ( ! defined( 'WPBM_RESPONSE' ) )			define( 'WPBM_RESPONSE',		false ); 