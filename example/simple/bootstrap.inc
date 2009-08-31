<?php

error_reporting(E_ALL | E_STRICT);

switch (ENVIRONMENT)
{
  case 'dev':
    define('EDC_LIB_DIR', dirname(__FILE__).'/../..');
    define('EDC', 'e.inc');
    break;
  case 'prod':
  default:
    define('EDC_LIB_DIR', dirname(__FILE__).'/../../lib');
    define('EDC', 'ecore.inc');
}


set_include_path(get_include_path().PATH_SEPARATOR.EDC_LIB_DIR);


define('CTRLS', dirname(__FILE__).'/controllers.inc');

define('EDC_CTRL_DIR', dirname(__FILE_).'/controllers');
