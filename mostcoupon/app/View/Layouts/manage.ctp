<!doctype html>
<html lang="en" itemscope itemtype="http://schema.org/Article">
<head>
    <?php if(isset($store)): ?>
    <title><?php echo(isset($seoConfig['title']) ? $seoConfig['title'] : $store['Store']['name'].' coupon codes') ?></title>
    <meta name="description" content="<?php echo(isset($seoConfig['desc']) ? $seoConfig['desc'] : $store['Store']['description']) ?>"/>

    <meta property="og:type" content="article" />
    <meta property="og:title" content="<?php echo ($store['Store']['name'] ? $store['Store']['name'] . ' coupon codes from MostCoupon.com' : '' )?>" />
    <meta property="og:url" content="<?php echo $this->Html->url(null, true) ?>" />
    <?php if($store['Store']['social_image']): ?>
    <meta property="og:image" content="<?php echo $store['Store']['social_image'] ?>" />
    <?php endif; ?>
    <meta property="og:site_name" content="<?php echo (isset($siteName) ? $siteName : '')?>" />
    <meta property="og:description" content="<?php echo ($store['Store']['description'] ? $store['Store']['description'] : '' )?>" />
    <?php else: ?>
    <title><?php echo(isset($seoConfig['title']) ? $seoConfig['title'] : '') ?></title>
    <meta name="description" content="<?php echo(isset($seoConfig['desc']) ? $seoConfig['desc'] : '') ?>"/>
    <?php endif; ?>

    <?php if (isset($seoConfig['disableNoindex']) && $seoConfig['disableNoindex'] != 1): ?>
        <META NAME="ROBOTS" CONTENT="NOINDEX, NOFOLLOW">
    <?php endif; ?>
    <meta charset="UTF-8"/>

    <meta name="keywords" content="<?php echo(isset($seoConfig['keyword']) ? $seoConfig['keyword'] : '') ?>"/>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet"
          href="http://fonts.googleapis.com/css?family=Open+Sans:300,400,700,800|Open Sans Condensed:300,700"/>
    <?php
    echo $this->element('css_and_js');
    echo $this->fetch('css');
    echo $this->fetch('script');
    echo $this->element('google_analytics');
    echo $this->element('browser_update');
    echo $this->element('alexa');
    //echo $this->Html->meta(array('name' => 'robots', 'content' => 'noindex, nofollow'));
    //echo $this->Html->meta(array('name' => 'googlebot', 'content' => 'noindex, nofollow'));
    echo $this->Html->script([
        'http://ajax.googleapis.com/ajax/libs/angularjs/1.0.3/angular-sanitize.js'
    ]);
    // ShareThis
    echo $this->element('social/share_this');
    ?>
</head>
<body <?php echo $this->Ng->ngAppOut() ?> <?php echo $this->Ng->ngInitOut() ?>>
    <?php echo $this->element('facebook') ?>
    <?php echo $this->element('google_plus') ?>
    <?php echo $this->element('twitter') ?>
<?php echo $this->element('header') ?>
<?php echo $this->element('notification') ?>
    <div id="content" class="body" <?php echo $this->Ng->ngControllerOut() ?>>
    <?php echo $this->fetch('content'); ?>
    <?php if (($this->request->controller != 'categories' && $this->request->action != 'index') && $this->request->controller != 'errors') : ?>
        <?php echo $this->element('store_list') ?>
    <?php endif; ?>

</div>

<?php echo $this->element('footer') ?>
<a href="#" class="cd-top"><i class="fa fa-chevron-up fa-lg"></i></a>
</body>
</html>
