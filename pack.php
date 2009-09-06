<?php

if (basename(__FILE__) == $_SERVER['SCRIPT_FILENAME'])
  packer($_SERVER['argv'][1]);

function packer($input)
{
  $f = fopen("lib/$input.php", 'w');
  fwrite($f, '<?php ');
  fwrite($f, packing(file_get_contents("$input.php")));
  fclose($f);
}

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
    'args' => 'a',
    'app' => 'a',
    'class' => 'c',
    'cookie' => 'c',
    'header' => 'h',
    'method' => 'm',
    'match' => 'm',
    'name' => 'n',
    'no_classes' => 'n',
    'param' => 'p',
    'server' => 's',
    'text' => 't',
    'uri' => 'u',
    'var' => 'v',
  );
  foreach ($rep as $f => $t)
    $c = str_replace("\$$f", "\$$t", $c);
  
  return $c;
}
