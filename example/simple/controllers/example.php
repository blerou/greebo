<?php

class Example
{
  const URI = '/?';
  
  function get()
  {
    return render_to_response(<<<EOB
<form action="" method="post">
<input type="text" name="title">
<input type="submit">
</form>
EOB
     );
  }
  
  function post()
  {
    return render_to_response("title is '{$this->req->param('title')}'");
  }
}
