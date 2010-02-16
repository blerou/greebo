<?php namespace My\messageboard\Template; class index extends \greebo\conveniences\Template { function content() { ?>
<!doctype html>
<html>
  <head>
    <link href="/css/custom.css" type="text/css" rel="stylesheet" />
  </head>
  <body>
    <h1>Messages: <a id="new" href="?action=newform">new message</a></h1>
    <div id="form"></div>
    <div id="messages"></div>
    <div id="more"></div>
    <script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.4/jquery.min.js"></script>
    <script type="text/javascript" src="/js/custom.js"></script>
  </body>
</html>
<?php }}
