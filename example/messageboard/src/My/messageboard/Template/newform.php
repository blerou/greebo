<?php namespace My\messageboard\Template; class newform extends \greebo\conveniences\Template { function content() { ?>
<form action="?action=create" method="post">
  <div>
  <label for="title">Title</label>
  <input type="text" name="title" id="title" />
  </div>
  <div>
  <label for="body">Body</label>
  <textarea id="body" name="body"></textarea>
  </div>
  <div>
  <input type="submit" value="Add" />
  </div>
</form>
<?php }}
