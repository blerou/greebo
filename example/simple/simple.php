<?php

foreach (new RecursiveIteratorIterator(new RecursiveDirectoryIterator(dirname(__FILE__).'/handlers')) as $item)
{
  if ($item->isDir()) continue;
  require $item;
}

