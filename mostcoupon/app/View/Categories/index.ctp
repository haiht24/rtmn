<?php $this->Ng->ngController('CategoryIndexCtrl') ?>
<?php $this->start('script') ?>
<script type="text/javascript">
    Config.categories  = <?php echo !empty($categories) ? json_encode($categories) : '[]' ?>;


</script>

<?php echo $this->end(); ?>
<div class="container main-content categories">
    <div class="row">
        <?php foreach ($categories as $cat) : ?>
            <div class="category col-md-6">
                <a href="<?php echo $this->Html->url('/categories/'). $cat['Category']['alias'] ?>"
                   class="logo">
                    <i class="<?php echo $cat['Category']['icon'] ?>"></i>
                </a>
                <div class="text"> <?php echo $cat['Category']['name'] ?> </div>
                <div class="text hover">
                    <a class="link"
                       href="<?php echo $this->Html->url('/categories/'). $cat['Category']['alias'] ?>"><?php echo $cat['Category']['name'] ?></a>
                    (
<!--                    --><?php //foreach ($cat['Store'] as $store) : ?>
<!--                        <a href="--><?php //echo $this->Html->url('/' . $store['most_coupon_url']) ?><!---coupons">--><?php //echo $store['name'] ?><!--</a>-->
<!--                        --><?php //endforeach; ?>
                    ) </div>
            </div>
            <div class="sep col-md-1"></div>
            <?php endforeach; ?>
    </div>
</div>