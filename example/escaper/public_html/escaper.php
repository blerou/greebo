<?php

require dirname(__DIR__).'/lib/My/escaper/Bootstrap.php';

$b = new \My\escaper\Bootstrap('prod');
$b->run();