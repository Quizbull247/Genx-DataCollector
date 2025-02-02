<?php

function set_cache_headers() {
    $CI =& get_instance();
    
    // Set Cache-Control header
    $CI->output->set_header("Cache-Control: max-age=3600, must-revalidate");

    // Set Expires header
    $CI->output->set_header("Expires: ".gmdate("D, d M Y H:i:s", time() + 3600)." GMT");

    $CI->output->_display();
}
