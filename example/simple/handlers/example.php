<?php

class Example
{
  const R = '/(\d+)?';
  
  function get($req, $id = null)
  {
    return render_to_response(<<<EOB
<b>{$id}</b>
<form action="" method="post">
<input type="text" name="title">
<input type="submit">
</form>
EOB
     );
  }
  
  function post($req)
  {
    return render_to_response("title is '{$req->param('title')}'");
  }
}
