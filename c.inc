<?php

class Lister extends C
{
  const URI = '/?';
  
  function get()
  {
    return $this->render();
  }
  
  function post()
  {
    echo '<pre>';
    var_dump($_POST);
  }
}

function lister_get($params)
{
  return <<<EOB
<form action="" method="post">
<input type="text" name="title">
<input type="submit">
</form>
EOB;
}


class Poster extends C
{
  const URI = '/post';
  
  function get()
  {
    return 'this is poster';
  }
}
