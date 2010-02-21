<?php namespace My\simple\Template; class layout extends \greebo\conveniences\Template { function content() { ?>
<!DOCTYPE html>
<html>
<head>
  <title><?php echo $this->title; ?></title>
</head>
<body>
  <?php echo $this->_content; ?>
</body>
</html>
<?php }}
