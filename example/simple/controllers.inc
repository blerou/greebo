<?php

$controllers = array('example.php');

foreach ($controllers as $c)
  require EDC_CTRL_DIR."/$c";

// it has to be the last one; it contains 404 handler controller
switch (ENVIRONMENT)
{
  case 'dev':
    require EDC_LIB_DIR.'/h.inc';
    require EDC_LIB_DIR.'/d.inc';
    break;
  case 'prod':
  default:
    require EDC_LIB_DIR.'/extra.inc';
}
