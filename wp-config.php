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
define('DB_NAME', 'site_demo');

/** MySQL database username */
define('DB_USER', 'root');

/** MySQL database password */
define('DB_PASSWORD', '1@123');

/** MySQL hostname */
define('DB_HOST', 'localhost');

/** Database Charset to use in creating database tables. */
define('DB_CHARSET', 'utf8mb4');

/** The Database Collate type. Don't change this if in doubt. */
define('DB_COLLATE', '');
define('WP_MEMORY_LIMIT', '256M');

/**#@+
 * Authentication Unique Keys and Salts.
 *
 * Change these to different unique phrases!
 * You can generate these using the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}
 * You can change these at any point in time to invalidate all existing cookies. This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define('AUTH_KEY',         'B^;KB]Q(ZXY]V) edFMMe3KiDj-6<FR,Ip9_[9nK:!|@6W idYJvg.(Jf;2nWoJf');
define('SECURE_AUTH_KEY',  'j|vgS+4$NQG,aQHa}$ +7Ih_O#4@}WRE%%%<JL&Luz#VV:p9uy=}5*u+X&z;;0[s');
define('LOGGED_IN_KEY',    'WaSh%U}aMriOJRt06Dg+:=^.g,xX(L3lFRlK=Nj)gV&gGQUI;K.h573!>N8g:4x5');
define('NONCE_KEY',        'bV4@S39MavoRF$:#nb%Hlx0Rmd7m[{?DA8L%I(,WT<89I7=cPHv([bE[{$I~}{}w');
define('AUTH_SALT',        'y$ `cP*j2,G/maM/l*+) eSm8`[ZX@Elq^3dcEG:P&}IHTys}Oz,sHYHzj40UU@j');
define('SECURE_AUTH_SALT', 'p3jv/=-GYb(`2BE(yj30y[~5<OO$<W~j}2`D?p{1p3@A4j=g^vyff^Oj3WS.jRev');
define('LOGGED_IN_SALT',   'I=NC]0u<hP<XhPx6+^A, G0(~W#;@WE|MaUBx4l&y7C5Ak3OE%Y;%YxqFDw0a>Mh');
define('NONCE_SALT',       '2q@_Clc%[uU>zF{`{dLh%LuYUaO,wH,+5^eGM}e/B8mwspZXDxFL{.zkpxv|e7[W');

/**#@-*/

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix  = 'wp_';
define('FS_METHOD', 'direct');

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
