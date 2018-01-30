<?php
/**
 * Better Attributes   
 *
 * @package  Better Attributes
 * @author   BetterStudio <info@betterstudio.com>
 * @version  1.2.0
 * @access   public
 * @see      http://www.betterstudio.com
 */

// current directory path
$dir = get_template_directory() . '/includes/libs/better-attr/';

// Core functions
include $dir . 'core.php';

// Structural tags functions and filters
include $dir . 'structural.php';

// Header tags functions and filters
include $dir . 'header.php';

// Post tags functions and filters
include $dir . 'post.php';

// Post meta tags functions and filters
include $dir . 'meta-tag.php';

// Comment tags functions and filters
include $dir . 'comment.php';

// Social Meta tags generator
include $dir . 'class-bsba-social-meta-tag-generator.php';
