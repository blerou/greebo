<?php namespace My\simple\Template; class post extends \greebo\conveniences\Template { function content() { ?>

<?php //$this->slot('content') ?>
title is '<?php echo $this->title ?>'
<?php //$this->stop() ?>

<?php }}
