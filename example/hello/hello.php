<?php

class Hello
{
  const R = '.*';
  
  function get($req)
  {
    return render_to_response('hello world');
  }
}
