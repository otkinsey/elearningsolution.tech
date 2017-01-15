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
define('DB_NAME', 'eliteTrainingVideos');

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
define('AUTH_KEY',         ']?i{Hzg[4$ 4ZKQX.qEty8o(**gds=x+9F`Bd9&i$}iFZLU3%s.CRveN/{ ;Lln?');
define('SECURE_AUTH_KEY',  'n!xt!gTt{5q6chlWTw-fdQtK$$R#]ct&<3]{8BnfGEeH]g(Va~A$Z.b0_zBmndc6');
define('LOGGED_IN_KEY',    '^$Zl`LlvU;EF6%pY.sqLw?DRK%OXH~3p#M:d@ksxb!/v^NrIUYA2Ee3R9bsNe0er');
define('NONCE_KEY',        '+?Fiv510K9L&1W?Hy-#dt i6DR>=#cxsoQRK&Y[R5Keew(}G:$F/r%uS(hnw2w,H');
define('AUTH_SALT',        '$.XwSj7TrQWV&r%S=s@oCtKW_g=..k(NlnKMQjFua+kq*v;E-h@I?$R-SJn/`@L}');
define('SECURE_AUTH_SALT', 'MV%pR=Fs@@]?9(V$Vny&RI(c.<%|K~uh.MA[4&1LfN[PhL}QxJ2cKw+zSw,|r&rM');
define('LOGGED_IN_SALT',   'cj;iq?md,Qz8G^4oK/x1|wc[(Do*$+@]UU?b3Ba`<^l^gf+<at1^~jSW@{`N,U.#');
define('NONCE_SALT',       'S_W,=;Yav^iC<OO^FJdlc/}zRj8d17fb|=@ ;<z&piBoZ5%V=[}S,qvSE6S,:e5K');

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
