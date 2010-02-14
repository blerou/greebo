<?php

namespace greebo\test;

class ActionSet extends \greebo\conveniences\ActionSet
{
    public $t;
    function  __construct($t)
    {
        $this->t = $t;
    }

    function indexAction()
    {
        $this->t->pass('index action executed properly');
        $this->t->is($this->foo, 'bar', 'container getter works properly');

        $this->foo = new \stdClass();
    }

    function assignmentsAction()
    {
        $this->assign('foo', 'bar');
        $this->assign('baz', true);
    }
}