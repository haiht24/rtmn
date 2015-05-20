<div style="height: 1080px">
    <div class="fof">
        <form class="search">
            <div class="input-group">
                <input type="text" class="form-control" placeholder="Search store, coupon, deal ..."/>

                <div class="input-group-btn">
                    <button class="btn btn-default">
                        <i class="icon mc mc-search"></i>
                    </button>
                </div>
            </div>
        </form>
        <div class="stores">
            <div class="store-list icon-line">
                <?php if (isset($stores)) : ?>
                    <?php foreach ($stores as $store) : ?>
                        <a href="<?php echo $this->Html->url('/' . $store['Store']['alias']) ?>-coupons"
                           class="item"
                           style="background-image: url('<?php echo (!empty($store['Store']['logo'])) ? $store['Store']['logo'] : 'http://lorempixel.com/50/50' ?>')"></a>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>
        <a href="<?php echo $this->Html->url('/') ?>" class="back-to-home"></a>
    </div>
</div>