<?php

error_reporting(E_ALL | E_STRICT);
ini_set('display_errors', 'on');

require dirname(__DIR__).'/lib/My/escaper/Bootstrap.php';

$b = new \My\escaper\Bootstrap('prod');
$b->run();
