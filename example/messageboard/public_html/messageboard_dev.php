<?php
    
require dirname(__DIR__).'/src/My/messageboard/Bootstrap.php';

$b = new \My\messageboard\Bootstrap('dev');
$b->run();
