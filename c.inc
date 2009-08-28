<?php

class Lister
{
  const URI = '/?';
  
  function get()
  {
    return <<<EOB
<form action="" method="post">
<input type="text" name="title">
<input type="submit">
</form>
EOB;
  }
  
  function post()
  {
    return "title is '{$_POST['title']}'";
  }
}

class Poster
{
  const URI = '/post';
  
  function get()
  {
    return 'this is poster';
  }
}
