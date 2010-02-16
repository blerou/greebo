<?php

require dirname(__DIR__).'/lib/My/messageboard/Bootstrap.php';

$b = new \My\messageboard\Bootstrap('prod');
$b->run();
