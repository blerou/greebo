<?php namespace My\simple\Template; class index extends \greebo\conveniences\Template { function content() { ?>

<?php $this->extend('layout'); ?>

<?php $this->slot('content') ?>
<form action="simple.php?action=post" method="post">
<input type="text" name="title">
<input type="submit">
</form>
<?php $this->stop() ?>

<?php }}
