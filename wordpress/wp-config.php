<?php
/**
 * The base configuration for WordPress
 *
 * The wp-config.php creation script uses this file during the
 * installation. You don't have to use the web site, you can
 * copy this file to "wp-config.php" and fill in the values.
 *
 * This file contains the following configurations:
 *
 * * MySQL settings
 * * Secret keys
 * * Database table prefix
 * * ABSPATH
 *
 * @link https://codex.wordpress.org/Editing_wp-config.php
 *
 * @package WordPress
 */

// ** MySQL settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define('DB_NAME', 'writing');

/** MySQL database username */
define('DB_USER', 'Rubab');

/** MySQL database password */
define('DB_PASSWORD', 'RubabAli143');

/** MySQL hostname */
define('DB_HOST', 'localhost');

/** Database Charset to use in creating database tables. */
define('DB_CHARSET', 'utf8mb4');

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
define('AUTH_KEY',         'TFH}K9<2%l,41x~/!$b(SKEA9<8ans3B7&LM2_`?o-wQ2##>aOreruyIJ;uB%Msj');
define('SECURE_AUTH_KEY',  'EQ/aqy^*Z|?UcwWZ-b%?hezw~i_;!W<Cdeob6n)wJ!fk{3I%(;|_l^6r,e[~C#3^');
define('LOGGED_IN_KEY',    '.LI[~5Fzt^,Fc>x,O9sB.*?SeTe+!#cUzujH:vsw/+ sIA^U_d>6EzI8s;SWt<TG');
define('NONCE_KEY',        'HHg9z?:^Ty!&bCQZ&eoH$SsSsm1W^}ix*wIB ~!7+!6*@`Dd!A[ZdcE&or}l(cDU');
define('AUTH_SALT',        '1)-Kc3%{+09`#>4)M==rRO^ jrZM4%vfRFng&gb*@ghs@9r~W2Q/ dQ0Q<XV &jX');
define('SECURE_AUTH_SALT', '4*Zf6O/Hm{KC-SRs[t*>1DX:<YYH3ygdilZlQ3!8<%q[b<w8OE!v5Vvxu?Gx@3+z');
define('LOGGED_IN_SALT',   'xb&fzMV|7Our]AJdqq:<gK0jl2*Dh~0x`y5@auq|K&8p^`UqLBH#,9T%LT:<Mm_l');
define('NONCE_SALT',       'YL&%)#{Vly_zG=9L:QGiG;uI{}rW7l;r=tdiJQ6 l:*4k+Z7a7`-a9fJyQ$2f4,w');

/**#@-*/

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix  = 'wp_';

/**
 * For developers: WordPress debugging mode.
 *
 * Change this to true to enable the display of notices during development.
 * It is strongly recommended that plugin and theme developers use WP_DEBUG
 * in their development environments.
 *
 * For information on other constants that can be used for debugging,
 * visit the Codex.
 *
 * @link https://codex.wordpress.org/Debugging_in_WordPress
 */
define('WP_DEBUG', false);

/* That's all, stop editing! Happy blogging. */

/** Absolute path to the WordPress directory. */
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');

/** Sets up WordPress vars and included files. */
require_once(ABSPATH . 'wp-settings.php');
