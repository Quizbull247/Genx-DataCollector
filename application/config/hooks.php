<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
| -------------------------------------------------------------------------
| Hooks
| -------------------------------------------------------------------------
| This file lets you define "hooks" to extend CI without hacking the core
| files.  Please see the user guide for info:
|
|	https://codeigniter.com/user_guide/general/hooks.html
|
*/
$hook['post_controller_constructor'] = array(
    'class'    => '',
    'function' => 'clear_cache',
    'filename' => 'clear_cache.php',
    'filepath' => 'hooks'
);

$hook['display_override'] = array(
    'class'    => '',
    'function' => 'set_cache_headers',
    'filename' => 'cache_headers.php',
    'filepath' => 'hooks'
);

