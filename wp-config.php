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

// ** MySQL settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define('DB_NAME', 'wordpress-conext');

/** MySQL database username */
define('DB_USER', 'wordpress_user');

/** MySQL database password */
define('DB_PASSWORD', 'wordpress_pwd');

/** MySQL hostname */
define('DB_HOST', '127.0.0.1');

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
define('AUTH_KEY',         'Z$qh~z(miKbxsN}F>&nob9Y1n-&.#`.C(yuwLKS!_Frak-d(/qFe.uzvc8p3=]w@');
define('SECURE_AUTH_KEY',  'OqtO:(|a/[Abh+GjD]^[)-enPg$|Ll?gG/D$]X.ro#RU2qBtAqA[7SE3H{D)$))b');
define('LOGGED_IN_KEY',    'iK<u@VrJi$n?kwQS9+B_r-jnvb*~9<].)G_Y+,rn<3[^!q^PSX!MZM<rmyYr!%87');
define('NONCE_KEY',        ' w!fZiT% C|4i8`H98O+(aeFbVM*{&P5qFH]{+[v[u~=YAFBV&GIIHK_.60C+:*A');
define('AUTH_SALT',        ' ~dK1QI@S7>+P>fYQn&q+8vqjA)Lt00IWfJ$o5#aJTzNsd!9Nv|a26yfrs6m%}{h');
define('SECURE_AUTH_SALT', '2Q^(>b9xUz/]#n|((/j;-4?vmoT}Q,Z={WNooQI2UL5]-;[#Mp(OCCV oQZMy8X4');
define('LOGGED_IN_SALT',   '.j5%/|D3Mjp&,(T>,HcMDZtrQ#D.E?3k(MEk2cW/ Q.kXSf!(t6Zr[||f-X+qi|N');
define('NONCE_SALT',       '(E%MKec-!JFVW ~O03#A1%C[XGw|Bz4+1$=]>&ZmJ  GfJ`U-zB-hEhJbP`*k*bm');

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
define('WPLANG', '');

/**
 * For developers: WordPress debugging mode.
 *
 * Change this to true to enable the display of notices during development.
 * It is strongly recommended that plugin and theme developers use WP_DEBUG
 * in their development environments.
 */
define('WP_DEBUG', false);

/* That's all, stop editing! Happy blogging. */

/** Absolute path to the WordPress directory. */
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');

/** Sets up WordPress vars and included files. */
require_once(ABSPATH . 'wp-settings.php');
