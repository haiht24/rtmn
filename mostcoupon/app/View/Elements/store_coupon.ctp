<ul>
    <?php foreach($storesCoupon as $store) :?>
    <li>
        <a href="<?php echo $this->Html->url('/' . $store['Store']['alias']) ?>-coupons"><?php echo $store['Store']['name'] . ' ' . $store['Store']['custom_keywords'] ?></a>
    </li>
    <?php endforeach; ?>
</ul>