<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$hook['post_controller_constructor'][] = array(
    'class'    => 'Cors',
    'function' => 'enable_cors',
    'filename' => 'Cors.php',
    'filepath' => 'hooks'
);

