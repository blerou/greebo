<?php

set_include_path(get_include_path().PATH_SEPARATOR.dirname(__FILE__));

switch (ENVIRONMENT)
{
  case 'dev':
    error_reporting(E_ALL | E_STRICT);
    define('EDC_LIB_DIR', __DIR__.'/../..');
    break;
  
  case 'prod':
  default:
    error_reporting(E_NONE);
    define('EDC_LIB_DIR', __DIR__.'/../../lib');
}

require EDC_LIB_DIR.'/greebo.php';
