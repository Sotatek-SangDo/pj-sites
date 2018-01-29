<?php

$template_directory = get_template_directory() . '/';

// Override functions
require $template_directory . 'includes/functions.php';

// Includes BF loader if not included before
require $template_directory . 'includes/libs/better-framework/init.php';

// Includes Better Template
include $template_directory . 'includes/libs/better-template/init.php';

// Includes Better Attr if not loaded before
require $template_directory . 'includes/libs/better-attr/init.php';

// Registers and prepare all stuffs about BF that is used in theme
require $template_directory . 'includes/class-better-mag-bf-setup.php';
new Better_Mag_BF_Setup();

// Fire up BetterMag
require $template_directory . 'includes/class-better-mag.php';
new Better_Mag();

// Last Versions Compatibility
require $template_directory . 'includes/class-better-mag-last-versions-compatibility.php';
new Better_Mag_Last_Versions_Compatibility();