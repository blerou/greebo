<?php namespace My\Template\Simple; class index extends \My\Template\layout { function content() { ?>
<?php $this->rec('content') ?>
<form action="simple.php?action=post" method="post">
<input type="text" name="title">
<input type="submit">
</form>
<?php $this->stop() ?>
<?php }}
