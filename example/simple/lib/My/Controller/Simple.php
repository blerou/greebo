<?php

namespace My\Controller;

class Simple extends \Greebo\Controller2
{
  function indexAction()
  {
  }
  
  function postAction()
  {
    $title = $this->request->param('title');
    $this->response->content("title is '$title'");
  }
}
