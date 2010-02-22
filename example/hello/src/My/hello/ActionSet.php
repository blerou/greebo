<?php

namespace My\hello;

class ActionSet extends \greebo\conveniences\ActionSet
{
  function indexAction() {
    $this->assign('name', $this->request->get('name', ''));
  }
}
