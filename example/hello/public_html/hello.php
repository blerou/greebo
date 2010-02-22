<?php

require __DIR__.'/../src/My/hello/Bootstrap.php';

$b = new \My\hello\Bootstrap('prod');
$b->run();
