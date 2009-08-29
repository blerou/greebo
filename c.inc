<?php

require 'b.inc';

class Lister
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
    return render_to_response("title is '{$_POST['title']}'");
  }
}

class Poster
{
  const URI = '/post';
  
  function get()
  {
    return render_to_response('this is poster');
  }
}

require 'd.inc';
