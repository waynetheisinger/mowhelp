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

define( 'AS3CF_SETTINGS', serialize( array(
    'provider' => 'do',
    'access-key-id' => 'EXSJSNUXUQC4MOCHJLZZ',
    'secret-access-key' => 'pofI3uubhPEV6bJBXU+hUJKwn37PeJ6OyyyHw4J5EV8',
) ) );

define('MULTISITE', true);
define('SUBDOMAIN_INSTALL', false);
define('DOMAIN_CURRENT_SITE', 'tmpdomain');
define('PATH_CURRENT_SITE', '/blog/');
define('SITE_ID_CURRENT_SITE', 1);
define('BLOG_ID_CURRENT_SITE', 1);


// ** MySQL settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define('DB_NAME', 'tmpdbname');

/** MySQL database username */
define('DB_USER', 'tmpdbuser');

/** MySQL database password */
define('DB_PASSWORD', 'tmpdbpass');

/** MySQL hostname */
define('DB_HOST', 'tmpdbhost:tmpdbport');

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
define('AUTH_KEY',         '!G!@gzmV9pj3=t5 K`zR|Nxm/mB?Q.DCO&BxWDcvDw+q]qm)*O7[DbTWr6Zed#&.');
define('SECURE_AUTH_KEY',  '}BwuU>(HC?H{YH[Zh1.O#*gmShT_0f1dK) ITC]@!5iv._I>$B@e~2|~W$AFhP0!');
define('LOGGED_IN_KEY',    'UeTZ/_P!#WM=(6:ppZq!_2hwfc&FyPn6u8)eG;.(_i[8N5%ILVj:8Sx(?2u=Hp?/');
define('NONCE_KEY',        'qO)t.~7iYFGic;Z+QMEQPRux`z;?0>n)I5ZN_Zm[HG%r QEa Z9fy.|.Ae+%WWM-');
define('AUTH_SALT',        '3AwBT]c6;Owx]J`f`x7^q`E2-[5kCI~GMWJp%H3lt;QQINOx_X;hyDVvanKA)f]]');
define('SECURE_AUTH_SALT', 'PZnSmBdC~n/&1s&%?B9?%{/(P+s9Z>Ab{E9_K>#!R_U^bPKo8dJtlGw^;qFI$nSn');
define('LOGGED_IN_SALT',   'Sv}.5V:N8Aq5MciOA!uoz3y{P^6O,A~kn#Qb4}Q5^O4wK3ekn6HSgj|Q=!*@`B<l');
define('NONCE_SALT',       'Og*5pV_v(hItG]K%Vd$m[ vqAfp<&/_r8xQ,-Lq]:5nx[kWY#UOh1W!M.E7%ZB{s');

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

/* SSL Settings */
define('FORCE_SSL_ADMIN', true);

/* Turn HTTPS 'on' if HTTP_X_FORWARDED_PROTO matches 'https' */
if (strpos($_SERVER['HTTP_X_FORWARDED_PROTO'], 'https') !== false) {
    $_SERVER['HTTPS'] = 'on';
}

define( 'WP_HOME', 'https://tmpdomain/blog/' );
define( 'WP_SITEURL', 'https://tmpdomain/blog/' );

/* That's all, stop editing! Happy blogging. */

/** Absolute path to the WordPress directory. */
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');

/** Sets up WordPress vars and included files. */
require_once(ABSPATH . 'wp-settings.php');
