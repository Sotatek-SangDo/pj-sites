<?php
/**
 * Fire ups BetterTemplate functionality that enables child themes to change theme templates simply
 * with flexible options and us to develop themes quickly.
 *
 * @package    BetterTemplate
 * @version    1.0.0
 * @author     BetterStudio <info@betterstudio.com>
 * @copyright  Copyright (c) 2015, BetterStudio
 */


if( ! defined( 'BS_THEME_ADMIN_ASSETS_URI' ) )
	define( 'BS_THEME_ADMIN_ASSETS_URI', get_template_directory_uri() . '/includes/admin-assets/' );

if( ! defined( 'BS_THEME_PATH' ) )
	define( 'BS_THEME_PATH', get_template_directory() . '/' );

if( ! defined( 'BS_THEME_URI' ) )
	define( 'BS_THEME_URI', get_template_directory_uri() . '/' );

// current directory path
$dir = BS_THEME_PATH . '/includes/libs/better-template/';

// Loads core template functionality
require $dir . 'templates/core.php';

// Loads helper functions
require $dir . 'templates/template-helpers.php';

// Loads content template functionality
require $dir . 'templates/template-content.php';

// Loads comment template functionality
require $dir . 'templates/template-comment.php';

// Loads helpers for VC
require $dir . 'vc-helpers/vc-helpers.php';

// Loads chat format
require $dir . 'chat-format/chat-format.php';

// Loads shortcodes placeholder
require $dir . 'shortcodes-placeholder/class-bsbt-shortcodes-placeholder.php';

// Loads duplicate posts limiter
require $dir . 'duplicate-posts/class-bsbt-duplicate-posts.php';

// Loads gallery slider
require $dir . 'gallery-slider/class-bsbt-gallery-slider.php';

// Loads version compatibility
require $dir . 'version-compatibility/class-bsbt-version-compatibility.php';
