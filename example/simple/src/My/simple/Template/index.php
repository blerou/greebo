<?php namespace My\simple\Template; class index extends layout { function content() { ?>

<?php $this->title = 'from index template'; ?>

<form action="simple.php?action=post" method="post">
<input type="text" name="title">
<input type="submit">
</form>

<?php }}
