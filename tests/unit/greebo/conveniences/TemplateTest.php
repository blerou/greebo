<?php

require_once __DIR__.'/../../bootstrap.php';

use greebo\essence\Event;
use greebo\conveniences\Template;

$t = new lime_test(17);

$event = new Event;
$template = new Template($event);


$t->diag('slot assignment');


$template->foo = 'bar';
$t->is_deeply($template->foo, 'bar', '->__get(), ->__set() works properly');

$template->baz = 'foo';
$t->ok(isset($template->baz) && !isset($template->bar), '->__isset() works properly');

unset($template->baz);
$t->is_deeply(isset($template->baz), false, '->__unset() works properly');

$t->is_deeply($template->raw('foo'), 'bar', '->raw() returns assigned slot properly');
$t->is_deeply($template->raw('bar'), null, '->raw() returns unassigned slot properly');

$template->rec('bar'); ?>
Testing bar slot
<?php
$template->stop();
$t->is_deeply($template->bar, "Testing bar slot\n", '->rec(), ->stop() works properly');

$template->assign(array('t1' => 'foo', 't2' => 'bar'));
$t->ok($template->t1 === 'foo' && $template->t2 === 'bar', '->assign() mass assignment works properly');


$t->diag('escaper');


$escaper = function($v) { return htmlspecialchars($v); };
$template->escaper($escaper);
$t->is_deeply($template->escaper(), $escaper, '->escaper() works properly');

$value = 12;
$t->is_deeply($template->escape($value), $value, '->escape() handles integers correctly');

$value = 3.1415;
$t->is_deeply($template->escape($value), $value, '->escape() handles floats correctly');

$value = '<b>foo</b>';
$t->is_deeply($template->escape($value), htmlspecialchars($value), '->escape() handles strings correctly');

$value = true;
$t->is_deeply($template->escape($value), $value, '->escape() handles booleans correctly');

$value = array();
$t->is_deeply($template->escape($value), $value, '->escape() handles arrays correctly');

$value = new stdClass();
$t->is_deeply($template->escape($value), $value, '->escape() handles objects correctly');


$t->diag('inheritance');


class layout extends greebo\conveniences\Template
{
  function content()
  {
    echo 'LAYOUT START';
    echo $this->_content;
    echo 'LAYOUT END';
  }
}

class extends_layout extends layout
{
  function content()
  {
    echo $this->foo;
  }
}

class extends_method extends Template
{
    function content()
    {
        $this->extend('layout');
        echo $this->foo;
    }
}

$event = new Event;
$template = new extends_layout($event);
$template->foo = 'bar';

$result = $template->fetch();
$excepted = 'LAYOUT STARTbarLAYOUT END';
$t->is_deeply($result, $excepted, '->fetch() handles class based inheritance properly');

$event = new Event;
$template = new extends_layout($event);
$template->extend(false);
$template->foo = 'bar';

$result = $template->fetch();
$excepted = 'bar';
$t->is_deeply($result, $excepted, '->extend() disables inheritance properly');


$event = new Event;
$template = new extends_method($event);
$template->foo = 'baz';

$result = $template->fetch();
$excepted = 'LAYOUT STARTbazLAYOUT END';
$t->is_deeply($result, $excepted, '->fetch() handles ->extend() based inheritance properly');