1;3R
<?php

require_once __DIR__.'/../../bootstrap.php';

use greebo\essence\Greebo;
use greebo\essence\Container as Container;
use greebo\essence\Hooks;
use greebo\essence\Request;
use greebo\essence\Response;


$t = new LimeTest(2);

$container = new Container;
$container->action = function($c) use($t) { $t->pass('->unleash() calls action callback'); };

$container->hooks->reg('startup', function($c) use($container, $t) {
  $t->same($c, $container, '->__construct() takes Container instance');
});

ob_start();
$g = new Greebo($container);
$g->unleash();
ob_end_flush();
