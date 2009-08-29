<?php

$files = array('e', 'r');
$result = 'ecore.inc';

$f = fopen($result, 'w');
fwrite($f, '<?php ');
foreach ($files as $file)
{
  fwrite($f, packing(file_get_contents("{$file}.inc")));
}
fclose($f);

function packing($c)
{
  // general rewrites
  $rep = array(
    // comments
    '/\/\*\*.+?\*\//s' => '',
    '/\/\/.*$/m' => '',
  
    '/\<\?php\s*/' => '',
    '/\s+/' => ' ',
    
    '/\s*([;()?:,={}$@\'])\s*/' => '\1',
  );
  foreach ($rep as $f => $t)
    $c = preg_replace($f, $t, $c);
    
  // variable rewrites
  $rep = array(
    'no_classes' => 'n',
    'server' => 's',
    'class' => 'c',
    'method' => 'm',
    'uri' => 'u',
    'args' => 'a',
    'param' => 'p',
    'header' => 'h',
    'cookie' => 'c',
  );
  foreach ($rep as $f => $t)
    $c = str_replace("\$$f", "\$$t", $c);
  
  // special post processing
  $clear = array("require'r.inc';", "require'd.inc';");
  foreach ($clear as $cl)
    $c = str_replace($cl, '', $c);
  
  return $c;
}
