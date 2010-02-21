<?php

require dirname(__DIR__).'/src/My/escaper/Bootstrap.php';

$b = new \My\escaper\Bootstrap('prod');
$b->run();
