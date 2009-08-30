<?php

/**
 * Helper functions
 */

function render_to_response($text)
{
  $r = new Res;
  $r->content = $text;
  return $r;
}

function redirect($uri)
{
  $r = new Res;
  $uri = $_SERVER['SCRIPT_NAME'].$uri;
  $r->header[] = "Location: $uri";
  return $r;
}
