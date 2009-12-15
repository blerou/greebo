<?php

error_reporting(E_ALL | E_STRICT);

$return = array(); reset($_SERVER['argv']);
while (false !== $param = next($_SERVER['argv'])) {
  if (strlen($param) >= 2 && $param[0] == '-') {
    list($key, $value) = preg_split('/=/', ltrim($param, '-'));
    $return[$key] = $value;
  } else {
    $return[] = $param;
  }
}

var_dump($return);
