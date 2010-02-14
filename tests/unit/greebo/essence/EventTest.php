<?php

require_once __DIR__.'/../../bootstrap.php';

use greebo\essence\Event;


$t = new lime_test(9);


$event = new Event();
$subject = new stdClass();
$content = array();


$t->diag('->fire()');


$event->fire('invalid', $subject);

$event->connect('fire', function($subj) use($t, $subject) {
    $t->pass('->connect() works properly');
    $t->pass('->fire() works properly');
    $t->ok($subj === $subject, '->fire() send correct subject');
});
$event->fire('fire', $subject);

$event->connect('until', function($subj) {
    return 'stopped';
});
$event->connect('until', function($subj) use($t) {
    $t->fail('->fire() must be stopped by previous listener');
});

$return = $event->fire('until', $subject);
$t->is($return, 'stopped', '->fire() stop notification and returns correct value');


$t->diag('->filter()');


$return = $event->filter('invalid', $subject, $content);
$t->is($return, $content, '->filter() returns original content on invalid event');

$event->connect('filter', function($subj, $cont) use($t, $subject, $content) {
    $t->pass('->filter() works properly');
    $t->ok($subj === $subject, '->filter() send correct subject');
    $t->ok($cont === $content, '->filter() send correct content');

    return array('filtered');
});

$return = $event->filter('filter', $subject, $content);

$t->is($return, array('filtered'), '->filter() returns filtered content properly');