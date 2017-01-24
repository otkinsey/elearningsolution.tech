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
define('DB_NAME', 'elearningsolution');

/** MySQL database username */
define('DB_USER', 'elsokjl');

/** MySQL database password */
define('DB_PASSWORD', ';&rgq#e!lHw?');

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
define('AUTH_KEY',         'B`1H@Es&LMx6Sr;2ugwNzu|msKi@%qPxOj`_a)fQ8?<o YVe}fS(3]f;Ji@#O;aP');
define('SECURE_AUTH_KEY',  '%)ZC3;#{h-g&;-jf7|BQY49Aw1io7rL{(_Xsp-%n4RDED2!WQen3r<>3A`tG,l9k');
define('LOGGED_IN_KEY',    '$O=5yEFIl_F) |^y+slnvi+W097!!]UGU)W=Qzk?}KaKD@ftgV]>Lq><}H*l[P[D');
define('NONCE_KEY',        ',5bQVi?2{8Vu-d$RT|G[Cg+p[P*C-HxtFnZbh>-k(r^Kx1St^uD4/>d&G+(-/PEo');
define('AUTH_SALT',        'dwVRA;MCQV1d<Wg-Fu]%U8h/?0?7R.$1?V1U(TOym8giB5Vkbg,`7U+HjDzi?:M@');
define('SECURE_AUTH_SALT', 'yv1cJ;@|~4bO>A9?.Ei:j5N7kVnyF+:E)`(ddbl!<Z w2SfKgO&|{lpDp}6l;F|L');
define('LOGGED_IN_SALT',   'sHS#l4;AZh%>Zp+^;3uC4VH%IYI/:P/S5W&v92~lU%n1tDAd.z)0XpJ&t~E|30KG');
define('NONCE_SALT',       '0E5hCkZ_8*-sR.19gU4m,Nh;xiZWaJd3LO<k#@a`)$m4yaj:&r%%^kUnAc!TLwz;');

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
