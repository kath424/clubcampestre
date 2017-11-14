<?php
/** 
 * Configuración básica de WordPress.
 *
 * Este archivo contiene las siguientes configuraciones: ajustes de MySQL, prefijo de tablas,
 * claves secretas, idioma de WordPress y ABSPATH. Para obtener más información,
 * visita la página del Codex{@link http://codex.wordpress.org/Editing_wp-config.php Editing
 * wp-config.php} . Los ajustes de MySQL te los proporcionará tu proveedor de alojamiento web.
 *
 * This file is used by the wp-config.php creation script during the
 * installation. You don't have to use the web site, you can just copy this file
 * to "wp-config.php" and fill in the values.
 *
 * @package WordPress
 */

// ** Ajustes de MySQL. Solicita estos datos a tu proveedor de alojamiento web. ** //
/** El nombre de tu base de datos de WordPress */
define('DB_NAME', 'clubcampestretrinidaddb');


/** Tu nombre de usuario de MySQL */
define('DB_USER', 'clubcampestretrinidad');
//define('DB_USER', 'root');

/** Tu contraseña de MySQL */
define('DB_PASSWORD', 'vision');
//define('DB_PASSWORD', '');


/** Host de MySQL (es muy probable que no necesites cambiarlo) */
define('DB_HOST', 'localhost');


/** Codificación de caracteres para la base de datos. */
define('DB_CHARSET', 'utf8mb4');


/** Cotejamiento de la base de datos. No lo modifiques si tienes dudas. */
define('DB_COLLATE', '');

/**#@+
 * Claves únicas de autentificación.
 *
 * Define cada clave secreta con una frase aleatoria distinta.
 * Puedes generarlas usando el {@link https://api.wordpress.org/secret-key/1.1/salt/ servicio de claves secretas de WordPress}
 * Puedes cambiar las claves en cualquier momento para invalidar todas las cookies existentes. Esto forzará a todos los usuarios a volver a hacer login.
 *
 * @since 2.6.0
 */
define('AUTH_KEY', '`W~P>8.QOVF$f?gy0lET2=~+v].tg/~!![5beWd%6$v:_~3q*7MvCEZdfV-d4*)O');

define('SECURE_AUTH_KEY', 'XJ{_nV6~aw,@{H1/kE]}TpFAa5/Q#!,zRvz<hB:)X%TH~,mI*j/$VeL[r[ldb^nJ');

define('LOGGED_IN_KEY', 'D{ZSX%((?)oL)R!Sf?y[l^(Hm4m|`Cm~(}7XnP5rg7z u0q1%|cwhNw+BN#cz>+)');

define('NONCE_KEY', ':Wa&D_0skVYM7vL^=P|gh$r0&W6GADVq!7fX=QiLh?Vvv1B:Bi!!<787S|{Qf]&R');

define('AUTH_SALT', 'u0x7_G[w(SeLg1IH?t8A,>tU?g+-!==/Es~Q]`Fb,|{_e$ig9;P=&?c<,&jgMxgE');

define('SECURE_AUTH_SALT', 'Q@_&ao_:6KsV5`P,k $=8T6>=ea PNEU;|C=rOuZ@MwIgJ}%}]~b[ XtSZd(F]tN');

define('LOGGED_IN_SALT', 'b,mh^;?559.,%S8 IoG5[l}6Y%UR|?Ro#k+*OD87Q0Qt?Ph^B#LsA]Dn?:cd$klU');

define('NONCE_SALT', 'mI.TQ;{mZCb{U:,d6SME}rM;u[Yjzm!qd)LN|C`w&0DW>mr.W5cM8_Q%OO,A)[I0');


/**#@-*/

/**
 * Prefijo de la base de datos de WordPress.
 *
 * Cambia el prefijo si deseas instalar multiples blogs en una sola base de datos.
 * Emplea solo números, letras y guión bajo.
 */
$table_prefix  = 'w324p_';



/**
 * Para desarrolladores: modo debug de WordPress.
 *
 * Cambia esto a true para activar la muestra de avisos durante el desarrollo.
 * Se recomienda encarecidamente a los desarrolladores de temas y plugins que usen WP_DEBUG
 * en sus entornos de desarrollo.
 */
define('WP_DEBUG', false);


/* ¡Eso es todo, deja de editar! Feliz blogging */

/** WordPress absolute path to the Wordpress directory. */
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');

/** Sets up WordPress vars and included files. */
require_once(ABSPATH . 'wp-settings.php');

