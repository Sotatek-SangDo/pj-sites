<?php
/**
 * Single Product title
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

Better_Mag::generator()->blocks()->get_page_title( get_the_title(), false, true, 'h1', '' );