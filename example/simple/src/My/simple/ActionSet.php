<?php

namespace My\simple;

class ActionSet extends \greebo\conveniences\ActionSet
{
  function indexAction() {
  }
  
  function postAction() {
    $this->assign('title', $this->request->param('title'));
  }
}
