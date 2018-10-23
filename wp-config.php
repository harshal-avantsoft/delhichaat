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

// ** MySQL settings ** //
/** The name of the database for WordPress */
define( 'DB_NAME', 'delhicha_wp' );

/** MySQL database username */
define( 'DB_USER', 'root' );

/** MySQL database password */
define( 'DB_PASSWORD', 'root' );

/** MySQL hostname */
define( 'DB_HOST', 'localhost' );

/** Database Charset to use in creating database tables. */
define( 'DB_CHARSET', 'utf8' );

/** The Database Collate type. Don't change this if in doubt. */
define( 'DB_COLLATE', '' );

/**
 * Authentication Unique Keys and Salts.
 *
 * Change these to different unique phrases!
 * You can generate these using the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}
 * You can change these at any point in time to invalidate all existing cookies. This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define('AUTH_KEY',         ':2VsTKI#84<aTB@t69z6N!Z`QX{yX58qHVZ:p!0SM6-S~`fc ?d]K/P:aR&C>=Vn');
define('SECURE_AUTH_KEY',  'aazWw&Q1<*g,NYmN,9MO%-H)K$=R(O|O3^`u0:))Nwwhyeh_-J%/i8::FoWPAo[A');
define('LOGGED_IN_KEY',    'ca*x%d]`{-shL-Gvkl1bl;,1d{EFnxhhfmDxHNi]ST_iTKAUgLc_#%R-{A._Y!q4');
define('NONCE_KEY',        '#uiO6b<}a!Un5-||f;BV.je58u>xv+E/]elM^%5Wl!!+mjYnT|QDL+jh-GHfj++-');
define('AUTH_SALT',        'D^@>^B,iEwqUOcayHlE!S8a1X(mF,]-T,`Mr?yj+?$K@&2PwpjkMCcIiiGMo?[MS');
define('SECURE_AUTH_SALT', '*w+OVWpcSRw~}KX`DZDp?j^:,w%Lvlvr=>jr)bI6^$kK])f1MCkM./Ia&>KL%O_+');
define('LOGGED_IN_SALT',   '1A4tp5^FgGJ~GMJ<I}>T]<fPWvA~?80G7pA3e9t/#@=&LVf0arLm{!(/f*m_{{@H');
define('NONCE_SALT',       'x]2}tsDlT_EHbo+=e]f-}^ 6U?6z+M;C1Y5H@J-h3N(|^}1-zh<u9.|bHx8N2*ZK');

define( 'WP_AUTO_UPDATE_CORE', true );


define( 'WP_DEBUG', true );
define( 'WP_DEBUG_DISPLAY', false );
define( 'SCRIPT_DEBUG', true );
define( 'WP_AUTO_UPDATE_CORE', false );
/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix = 'dc_';




/* That's all, stop editing! Happy blogging. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) )
	define( 'ABSPATH', dirname( __FILE__ ) . '/' );

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';
