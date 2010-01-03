<?php

namespace Greebo\Controller;

class simple extends \Greebo\Controller
{
  function indexAction()
  {
    $this->response->content('
<form action="simple.php?action=post" method="post">
<input type="text" name="title">
<input type="submit">
</form>
');
  }
  
  function postAction()
  {
    $title = $this->request->param('title');
    $this->response->content("title is '$title'");
  }
}