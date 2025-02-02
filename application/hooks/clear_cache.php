<?php
function clear_cache() {
    $CI =& get_instance();
    $CI->output->delete_cache();
}
