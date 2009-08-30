<?php

require EDC_CTRL_DIR.'/example.php';

// it has to be the last one; it contains 404 handler controller
switch (ENVIRONMENT)
{
  case 'dev':
    require EDC_LIB_DIR.'/h.inc';
    break;
  case 'prod':
  default:
    require EDC_LIB_DIR.'/extra.inc';
}


