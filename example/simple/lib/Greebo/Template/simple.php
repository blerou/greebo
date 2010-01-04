<?php

namespace Greebo\Template;

class simple extends \Greebo\Template
{
  function content()
  {
?>

<form action="simple.php?action=post" method="post">
<input type="text" name="title">
<input type="submit">
</form>

<?php
  }
}
