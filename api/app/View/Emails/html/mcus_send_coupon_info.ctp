<h3><b><?php echo $coupon['Coupon']['title_store'] ?></b></h3>
<p><?php echo $coupon['Coupon']['description_store'] ?></p>
<br>
<a href="<?php echo str_replace('api', 'mostcoupon', Configure::read('Url')). $coupon['Store']['alias']?>">Show detail</a><br>
<a href="<?php echo $coupon['Coupon']['product_link'] ?>" target="_blank"><?php echo $coupon['Coupon']['product_link'] ?></a>