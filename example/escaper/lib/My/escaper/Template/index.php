<?php
namespace My\escaper\Template;
class index extends \greebo\conveniences\Template
{
    function setup()
    {
        $this->string = '<script>alert("xss");</script>';
        $this->array = array(
            $this->string => $this->string,
        );
        $this->int = 123;
    }

    function content() { ?>

<?php foreach (range(1, 20) as $i) : ?>
<h1>
    <?php echo $this->string; ?>
</h1>
<?php foreach ($this->array as $key => $value) : ?>
<p>
    <strong><?php echo $key; ?></strong>:<?php echo $value; ?>
</p>
<?php endforeach; ?>
<p><sup><?php echo $this->int; ?></sup></p>

<?php endforeach; ?>

<?php }}
