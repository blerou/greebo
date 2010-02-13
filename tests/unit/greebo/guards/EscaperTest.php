<?php

require_once __DIR__.'/../../bootstrap.php';

use greebo\guards\Escaper;
use greebo\guards\ArrayEscaper;
use greebo\guards\ObjectEscaper;
use greebo\essence\Event;
use greebo\conveniences\Template;

$t = new lime_test(23);


$t->diag('::escape() return values');


$escaper = function($value) { return $value; };

$value = 12;
$t->is_deeply(Escaper::escape($value, $escaper), $value, '::escape() handles integers correctly');

$value = 3.1415;
$t->is_deeply(Escaper::escape($value, $escaper), $value, '::escape() handles floats correctly');

$value = '<b>foo</b>';
$t->is_deeply(Escaper::escape($value, $escaper), $value, '::escape() handles strings correctly');

$value = true;
$t->is_deeply(Escaper::escape($value, $escaper), $value, '::escape() handles booleans correctly');

$value = array();
$t->ok(Escaper::escape($value, $escaper) instanceof ArrayEscaper, '::escape() handles arrays correctly');

$value = new stdClass();
$t->ok(Escaper::escape($value, $escaper) instanceof ObjectEscaper, '::escape() handles objects correctly');

try {
    Escaper::escape(fopen('/dev/null', 'r'), $escaper);
    $t->fail('::escape() throws exception on resources');
} catch (greebo\guards\EscaperException $e) {
    $t->pass('::escape() throws exception on resources');
}


$t->diag('::register()');


class TestTemplate extends Template
{
    function content()
    {
    }
}

$event = new Event();
$template = new TestTemplate($event);
$template->escaper($escaper);
Escaper::register($event);
$event->connect('template.slots', function($template, $slots) use($t) {
    $escaped = $slots['foo'] instanceof ArrayEscaper && $slots['bar'] instanceof ArrayEscaper;
    $t->ok($escaped, '::register() works properly');
});
$slots = array('foo' => array(), 'bar' => new ArrayEscaper(array(), $escaper));
$event->filter('template.slots', $template, $slots);


$t->diag('HTML escaper');


$escaper = function($value) { return htmlspecialchars($value, ENT_QUOTES); };

$value = 12;
$t->is_deeply(Escaper::escape($value, $escaper), $value, '::escape() returns correctly escaped integers');

$value = 3.1415;
$t->is_deeply(Escaper::escape($value, $escaper), $value, '::escape() returns correctly escaped floats');

$value = '<b>foo</b>';
$t->is_deeply(Escaper::escape($value, $escaper), htmlspecialchars($value, ENT_QUOTES), '::escape() returns correctly escaped strings');

$value = true;
$t->is_deeply(Escaper::escape($value, $escaper), $value, '::escape() returns correctly escaped booleans');

$value = array('<i>bar</i>' => '<b>foo</b>');
$escaped = Escaper::escape($value, $escaper);
$t->is_deeply($escaped['<i>bar</i>'], htmlspecialchars('<b>foo</b>'), '::escape() returns correctly escaped array value (direct)');
foreach ($escaped as $key => $value) {
    $t->is_deeply($key, htmlspecialchars('<i>bar</i>'), '::escape() returns correctly escaped array key (iter)');
    $t->is_deeply($value, htmlspecialchars('<b>foo</b>'), '::escape() returns correctly escaped array value (iter)');
}


class EscaperTest
{
    public $foo = '<b>foo</b>';

    public function bar()
    {
        return '<i>bar</i>';
    }
}

$escaped = Escaper::escape(new EscaperTest, $escaper);
$t->is_deeply($escaped->foo, htmlspecialchars('<b>foo</b>'), '::escape() returns correctly escaped object property');
$t->is_deeply($escaped->bar(), htmlspecialchars('<i>bar</i>'), '::escape() returns correctly escaped object method value');


$t->diag('Array escaper');


$value = array('<i>bar</i>' => '<b>foo</b>');
$escaped = Escaper::escape($value, $escaper);
$t->is(count($escaped), count($value), '->count() works properly');
$t->is_deeply(isset($escaped['<i>bar</i>']), true, '->offsetExists() works properly');

try {
    $escaped['foo'] = 'fail';
    $t->fail('->offsetSet() throws EscaperException');
} catch (\greebo\guards\EscaperException $e) {
    $t->pass('->offsetSet() throws EscaperException');
}

try {
    unset($escaped['foo']);
    $t->fail('->offsetUnset() throws EscaperException');
} catch (\greebo\guards\EscaperException $e) {
    $t->pass('->offsetUnset() throws EscaperException');
}


$t->diag('Object escaper');


$escaped = Escaper::escape(new EscaperTest, $escaper);
$t->is_deeply(count($escaped), 1, '->count() works properly');
try {
    $escaped->foo = 'fail';
    $t->fail('->__set() throws EscaperException');
} catch (\greebo\guards\EscaperException $e) {
    $t->pass('->__set() throws EscaperException');
}