<?php

if (basename(__FILE__) == $_SERVER['SCRIPT_FILENAME'])
  packer($_SERVER['argv'][1]);

function packer($input)
{
  $info = pathinfo($input);
  $f = fopen(sprintf("%s.packed.php", $info['filename']), 'w');
  fwrite($f, '<?php ');
  fwrite($f, packing(file_get_contents($input)));
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
    
    '/\s*([;()?:,={}$@\'!])\s*/' => '\1',
  );
  foreach ($rep as $f => $t)
    $c = preg_replace($f, $t, $c);
    
  // variable rewrites
  $rep = array(
    '_container' => 'c',
    '_header' => 'h',
    '_cookie' => 'ci',
    '_status' => 's',
    '_content' => 'ct',
    '_slots' => 's',
    '_services' => 's',
    '_escaper' => 'e',
    '_hooks' => 'h',
    '$controller' => '$ct',
    '$callable' => '$ca',
    '$header' => '$h',
    '$cookie' => '$c',
    '$val' => '$v',
    '$name' => '$n',
    '$status' => '$s',
    '$content' => '$c',
    '$uri' => '$u',
    '$slot' => '$s',
    '$escaper' => '$e',
    '$object' => '$o',
    '$param' => '$p',
    '$method' => '$m',
    '$args' => '$a',
    '$def' => '$d',
    '$hook' => '$h',
  );
  foreach ($rep as $f => $t)
    $c = str_replace($f, $t, $c);
  
  return $c;
}
