<?php
/**
 * The base configuration for WordPress by TasteWP.com
 */
 
define('DB_NAME',     "help-and-advice");
define('DB_USER',     "magento-two");
define('DB_PASSWORD', "xR8fE2iJ9eVRmr3KCkRByRUh");
define('DB_HOST',     "164.92.183.25:3306");
define('DB_CHARSET',  'utf8mb4');
define('DB_COLLATE',  '');

define('AUTH_KEY',         'FOweQTcuZIpNSdMqMPSaCzSID1jAgzcnJqdwzoZL6nPBkpeOve5epbNnmVkxXCGj');
define('SECURE_AUTH_KEY',  'klcHkR1nvcdUSGDEnCxvB0iiC5IP7PqCL2orNaaYRHpcoLffSbVOEc6HNvW1XUcR');
define('LOGGED_IN_KEY',    'eSo74JLJqPaOEPrPf9sWWYIdEfKTvhw5vG7yrMdkC7asDXJjwvQyHez5iyxJLZnI');
define('NONCE_KEY',        'yfVuWmGCkab6JvJFnT0FaHmdDyl4MzTqW4FGR8sDDmx3aiHXOdGI5tOLaLf7hxTK');
define('AUTH_SALT',        'xKZyLxJlTPfqU6XunldcGBPUKZCpw6MCAHMPIrsMkjhaZD4bap8xGY7XBdgp8Bx0');
define('SECURE_AUTH_SALT', 'Xl5Mrx4G7iga9D7pwzLTN3vY5xPYDgLC2pfSDFkGYjYIO3PXK4gj2sVgkKJ3JbXq');
define('LOGGED_IN_SALT',   'NSR3vs4nQ2GXN82m9TMbhHeS8VaG1B4DFvsBQZi0muUPCRBDnD3sNj2V58mpOgfB');
define('NONCE_SALT',       'QoUK3x6dKaWGabuI0GyVmZaHOHBgOBHR1h0kTeJuLTxG5mIaSWW2FB5wvhFjotk1');

$table_prefix = 'wp_';

//define( 'WP_TEMP_DIR', '/var/www/html/mowzendesk/tmp' );
define( 'WP_MEMORY_LIMIT', '96M' );
define( 'WP_MAX_MEMORY_LIMIT', '96M' );
define('FS_METHOD', 'direct');

define( 'WP_DEBUG', true );
//define('WP_DEBUG_LOG', '/s1-whistlepet/wordpress/debug.log');

define( 'WP_DEBUG_DISPLAY', true );
define( 'CONCATENATE_SCRIPTS', true );







if (!defined('ABSPATH')) define('ABSPATH', __DIR__ . '/');
define( 'WP_SITEURL', 'https://www.mowdirect.co.uk/help-and-advice/' );
require_once ABSPATH . 'wp-settings.php';
