<?php
/**
 * The base configurations of the WordPress.
 *
 * This file has the following configurations: MySQL settings, Table Prefix,
 * Secret Keys, WordPress Language, and ABSPATH. You can find more information
 * by visiting {@link http://codex.wordpress.org/Editing_wp-config.php Editing
 * wp-config.php} Codex page. You can get the MySQL settings from your web host.
 *
 * This file is used by the wp-config.php creation script during the
 * installation. You don't have to use the web site, you can just copy this file
 * to "wp-config.php" and fill in the values.
 *
 * @package WordPress
 */
error_reporting(E_ALL | E_STRICT);
ini_set('short_open_tag', 'Off');
ini_set('max_execution_time', '60');
ini_set('max_input_time', '60');
ini_set('memory_limit', '128M');
ini_set('display_errors', 'On');
ini_set('display_startup_errors', 'On');
ini_set('log_errors_max_len', '0');
ini_set('html_errors', 'On');
ini_set('post_max_size', '128M');
ini_set('upload_max_filesize', '128M');
ini_set('date.timezone', 'America/Los_Angeles');

if ( extension_loaded('xdebug') ) {
	// see http://xdebug.org/docs/all_settings
	ini_set('xdebug.cli_color', '1');
	ini_set('xdebug.var_display_max_data', '100000');

	ini_set('xdebug.profiler_enable_trigger', '1');
	ini_set('xdebug.profiler_output_name', 'cachegrind.out.%s.%p');

	ini_set('xdebug.trace_enable_trigger', '1');
	ini_set('xdebug.trace_output_name', 'trace.%s.%p');

	ini_set('xdebug.remote_enable', '1');
	ini_set('xdebug.remote_handler', 'dbgp');
	ini_set('xdebug.remote_mode', 'req');

	// xdebug.remote_host should be your machine's IP.  If you develop on localhost, then this default is fine.  If you develop on a VM, then you'll need to adjust this to your computer's IP address (which may change due to DHCP).');
	ini_set('xdebug.remote_host', 'localhost');

	// xdebug.remote_port is different from the default port, because the default port of 9000 conflicts with PHP-FPM
	ini_set('xdebug.remote_port', '9009');
} else {
	error_log("You should be using xdebug");
}

define('WP_SITEURL', 'http://' . $_SERVER['SERVER_NAME']);
define('WP_HOME', WP_SITEURL);
define('WP_DEBUG', true);
define('SAVEQUERIES', true);
define('DISABLE_WP_CRON', true);

// ** MySQL settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define('DB_NAME', str_replace('.', '_', $_SERVER['SERVER_NAME']));

/** MySQL database username */
define('DB_USER', 'root');

/** MySQL database password */
define('DB_PASSWORD', 'pmc');

/** MySQL hostname */
define('DB_HOST', 'localhost');

/** Database Charset to use in creating database tables. */
define('DB_CHARSET', 'utf8');

/** The Database Collate type. Don't change this if in doubt. */
define('DB_COLLATE', '');


/**#@+
 * Authentication Unique Keys and Salts.
 *
 * Change these to different unique phrases!
 * You can generate these using the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}
 * You can change these at any point in time to invalidate all existing cookies. This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define('AUTH_KEY',         'Xw=]?0el21B.|hgy#LB].OglMV:|vz2:kl:_#T$bYGt|L&e vr-y2N((p36:u[m{');
define('SECURE_AUTH_KEY',  'K`:<[]6/YAE/ $-6r%H|uo!?[(O51[<{5VH}mRH@i9%6b<QPS;B0tc!i}/ZQ?2^E');
define('LOGGED_IN_KEY',    'UO/X-ikLr-F1lX`20Oix$dYh$+Gg}k@DQ(r^G|9IYU@@>L99yMb>pL,+)_y,*0|I');
define('NONCE_KEY',        '5@gL4.tk4WQ4v_P2&=7u^hf!2)AnMut4&K.uU|_2e1WdxxsW+90h/)qSb32b48G~');
define('AUTH_SALT',        'QpNAnV_z1>`8Jn@|Q0kW-wUN2r4u>$L|hh{0Ol+@sbVPRI&W7A|%Z<gJT,Piy?k*');
define('SECURE_AUTH_SALT', 'N@ 0b#a=xA2JI[S-F7bINSQB <z@Zhps-1?}L_tvWid:]&`d{n*S]4$s9AfxrN~?');
define('LOGGED_IN_SALT',   '1AvhS?,vN3xmU!T(Ct/ftpOTXexIJ_5pHE1$Sr7BKL@7EHfBv9rD9-UPmq8GK|&B');
define('NONCE_SALT',       '0t3p!yhC(RY^<6-h7{E+S-K!c0Z3-_@^/V.!|;3)~L+UKH!-UHopsi JR^b6o7|(');

/**#@-*/

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each a unique
 * prefix. Only numbers, letters, and underscores please!
 */
$table_prefix  = 'wp_';

/**
 * WordPress Localized Language, defaults to English.
 *
 * Change this to localize WordPress. A corresponding MO file for the chosen
 * language must be installed to wp-content/languages. For example, install
 * de_DE.mo to wp-content/languages and set WPLANG to 'de_DE' to enable German
 * language support.
 */
define ('WPLANG', '');

/* That's all, stop editing! Happy blogging. */

/** Absolute path to the WordPress directory. */
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');

/** Sets up WordPress vars and included files. */
require_once(ABSPATH . 'wp-settings.php');
