<?php

$files = array('e', 'r');
$result = 'ecore.inc';

$f = fopen($result, 'w');
fwrite($f, '<?php ');
foreach ($files as $file)
{
  $fn = "{$file}.inc";
  $fnp = "{$fn}.pack";
  copy($fn, $fnp);
  exec("vim $fnp");
  fwrite($f, packing(file_get_contents($fnp)));
  unlink($fnp);
}
fclose($f);

function packing($c)
{
  // general rewrites
  $rep = array(
    '/\<\?php\s*/' => '',
    '/\s*\n\s*/' => '',
    '/;\s+/s' => ';',
    '/ \? /' => '?',
    '/ : /' => ':',
    '/, /' => ',',
    '/ = /' => '=',
    '/if\s+/' => 'if',
    '/foreach\s+/' => 'foreach',
    '/as\s+\$/' => 'as$',
    '/\)\s+as\$/' => ')as$',
    '/\s*{\s*/' => '{',
    '/\s*}\s*/' => '}',
    '/\s*case\s*\'/' => 'case\'',
    '/\s*require\s*\'/' => 'require\'',
    
    '/\/\*\*.+?\*\//s' => '',
  );
  foreach ($rep as $f => $t)
    $c = preg_replace($f, $t, $c);
  
  // special post processing
  $clear = array("require'r.inc';", "require'd.inc';");
  foreach ($clear as $cl)
    $c = str_replace($cl, '', $c);
  
  return $c;
}
