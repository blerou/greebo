<?php namespace My\simple\Template; class layout extends \greebo\conveniences\Template { function content() { ?>
<html>
<head>
  <title>test</title>
</head>
<body>
  <?php echo $this->content ?>
</body>
</html>
<?php }}
