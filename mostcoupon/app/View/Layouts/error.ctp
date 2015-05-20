<!doctype html>
<html lang="en">
<head>
    <title>Most coupon</title>
    <meta charset="UTF-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?php
    echo $this->element('css_and_js');
    echo $this->fetch('css');
    echo $this->fetch('script');
    echo $this->element('google_analytics');
    echo $this->element('browser_update');
    echo $this->element('alexa');
    echo $this->Html->meta(array('name' => 'robots', 'content' => 'noindex, nofollow'));
    echo $this->Html->meta(array('name' => 'googlebot', 'content' => 'noindex, nofollow'));
    ?>
</head>
<body>
<?php echo $this->fetch('content') ?>
</body>
</html>