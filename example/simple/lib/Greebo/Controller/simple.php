<?php

namespace Greebo\Controller;

class simple extends \Greebo\Controller
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
