<?php

class HandlerFor404
{
  const URI = '.*';
  
  function __construct() { header(sprintf('%s 404', $_SERVER['SERVER_PROTOCOL'])); }
  
  function get() { return 'ooopss'; }
  
  function post() { return null; }
  
  function put() { return null; }
  
  function head() { return null; }
}
