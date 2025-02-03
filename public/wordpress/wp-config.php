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
 * @link https://wordpress.org/support/article/editing-wp-config-php/
 *
 * @package WordPress
 */

define( 'AS3CF_SETTINGS', serialize( array(
    'provider' => 'do',
    'access-key-id' => getenv('SPACES_ACCESS_KEY_ID'),
    'secret-access-key' => getenv('SPACES_SECRET_ACCESS_KEY'),
) ) );

define('MULTISITE', true);
define('SUBDOMAIN_INSTALL', false);
define('DOMAIN_CURRENT_SITE', getenv('DOMAIN_CURRENT_SITE'));
define('PATH_CURRENT_SITE', '/blog/');
define('SITE_ID_CURRENT_SITE', 1);
define('BLOG_ID_CURRENT_SITE', 1);

// ** MySQL settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define( 'DB_NAME', getenv('WP_DB_NAME'));

/** MySQL database username */
define( 'DB_USER', getenv('WP_DB_USER'));

/** MySQL database password */
define( 'DB_PASSWORD', getenv('WP_DB_PASSWORD'));

/** MySQL hostname */
define( 'DB_HOST', getenv('WP_DB_HOST'));

/** Database Charset to use in creating database tables. */
define( 'DB_CHARSET', 'utf8');

/** The Database Collate type. Don't change this if in doubt. */
define( 'DB_COLLATE', '');

/**#@+
 * Authentication Unique Keys and Salts.
 *
 * Change these to different unique phrases!
 * You can generate these using the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}
 * You can change these at any point in time to invalidate all existing cookies. This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define('AUTH_KEY',         ';b`%ipRsV+o)di3S$}(&H:_Nk;$8,71q&I@j| kCLu{i3:kwphgLm+XIF2*?`kB|');
define('SECURE_AUTH_KEY',  'VG%L+=pkg|6OJ|P>7#UC(O;V(ck+y_KsEA?@pp,+Xv#l%q!U]br#dK?52)ZPE|1-');
define('LOGGED_IN_KEY',    's9f}}lyu@`%X^C{jKMhZj%n6##%l`g?%PM&%[6Z1Z2CBxl~;5E+mQb`TL1HBx|{9');
define('NONCE_KEY',        '2M-Ubvz,fB#Tr*>vD0GEU)xz#=]>utYvaLXWu+X-kyRUN >|2RY20{nB9_|X|JOf');
define('AUTH_SALT',        'xI41sx%KT4S8RuzZU=7Plv]|r_n+~9-f=4^;3M|1~gF9f,X-M>.4zK$x;Wds~-TI');
define('SECURE_AUTH_SALT', 'Iq7kk*6K^I|rcXVrJuc^OqcmR3&DxYo>+NJuyo(@hyc4(9j5i#m_#]34)P!-1+O}');
define('LOGGED_IN_SALT',   '?Oh,rW#=I]2E`{Q7f*3k=?wogWazlr`@ix$cB#JEDcrnql8_-J*sYwW3|{`VB2YV');
define('NONCE_SALT',       '<@_3;<{/`5?]n^-Oh=~?k=0J|ZvaX^x7{^ta/ptR|z$Mj!Xp>sOvMObkYW~N`dQa');

/**#@-*/

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix = 'wp_';

/**
 * For developers: WordPress debugging mode.
 *
 * Change this to true to enable the display of notices during development.
 * It is strongly recommended that plugin and theme developers use WP_DEBUG
 * in their development environments.
 *
 * For information on other constants that can be used for debugging,
 * visit the documentation.
 *
 * @link https://wordpress.org/support/article/debugging-in-wordpress/
 */
define( 'WP_DEBUG', false );

// If we're behind a proxy server and using HTTPS, we need to alert Wordpress of that fact
// see also http://codex.wordpress.org/Administration_Over_SSL#Using_a_Reverse_Proxy
if (isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] === 'https') {
	$_SERVER['HTTPS'] = 'on';
}

/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', __DIR__ . '/' );
}

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';
