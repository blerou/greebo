<?php

/**
 * Bootstrap file
 */

function render_to_response($text)
{
  $r = new Res;
  $r->content = $text;
  return $r;
}
