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
define('DB_NAME', 'elearningsolutions');

/** MySQL database username */
define('DB_USER', 'otkinsey');

/** MySQL database password */
define('DB_PASSWORD', 'komet1');

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
define('AUTH_KEY',         'K <}6Le1ocaEPCn.<6FFIf<_otA5b :PYS5!h{+,71nquwuz(__IbA1#w0&VeLlI');
define('SECURE_AUTH_KEY',  '^|)6v_>Zc %G3>J)Guf )<}*^2~^]*X!dL[rYV6B_hA^>j!udP_}?t3/C(&5/&`x');
define('LOGGED_IN_KEY',    'SyzovL}U{Uvs8=}Q+=B<LG{aP37JYr:zHW3H{_[ {.W39 xz@t@G[uNLIrLq5y1_');
define('NONCE_KEY',        'ONZi Fw3.6h(+~<<Kke<@w}6lINU*59SkxJ`U8<HZFs#hpI0ft;9=s4cAJ~TN!QX');
define('AUTH_SALT',        '1m$2}OFs<pDdpaCV/|t+g7]=.vybyjy1.:uP_aCZr<Gga[`.75/,N<naWt{S<Bl/');
define('SECURE_AUTH_SALT', '`Q:CTj&Pb-}gt;-kF?m=W]ZQK<,5l#F86l^|1T?N*!.PLFxeVF)$!c073@9yZO<S');
define('LOGGED_IN_SALT',   'f!Awk1ij}5oRUqHn|2]sp8Q2~V_5`%2MybN=~P``{Lp(Q/5hNmD*n({BhuXYs[B@');
define('NONCE_SALT',       '5kBsr<@uPj,5mG-*|t&P0 ewgIq7&<)`.(P L+f#9},ITukLrThZ`0*T|@e}?vKb');

/**#@-*/

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix  = 'e_';

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
