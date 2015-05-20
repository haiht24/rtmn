<?php $this->Ng->ngController('StoreHowToUseCodeCtrl') ?>
<div class="breadcrumbs">
    <div class="container">
        <div class="row">
            <div class="col-sm-4 links">
                <ul>
                    <li>
                        <a href="<?php echo $this->Html->url('/') ?>">Home</a>
                    </li>
                    <li>
                        <a href="<?php echo $this->Html->url('/Stores/') ?>">Store</a>
                    </li>
                    <li>
                        <a href="<?php echo $this->Html->url('/Stores/HowToUseCode') ?>">How to use code</a>
                    </li>
                </ul>
            </div>
            <form class="col-sm-8 search">
                <div class="input">
                    <input type="text" class="form-control" placeholder="Search by store name, deal, coupon" />
                    <i class="icon mc mc-search"></i>
                </div>
            </form>
        </div>
    </div>
</div>
<div class="container main-content paper how-to-use how-to-use-code-content">
    <h1 class="title font-quark">
        <strong>How To Use Coupon Code</strong>
    </h1>
    <div class="body">
        <div class="step">
            <div class="title">
            <span class="count">step
              <strong>1</strong>
            </span>
                <strong>Click</strong> on the code </div>
            <div class="content">
                <img src="<?php echo $this->Html->url('/assets/img/how-to-1.jpg'); ?>" alt="" width="825" height="769" /> </div>
        </div>
        <div class="step">
            <div class="title">
            <span class="count">step
              <strong>2</strong>
            </span>
                <strong>Copy</strong> the code </div>
            <div class="content">
                <img src="<?php echo $this->Html->url('/assets/img/how-to-2.jpg'); ?>" alt="" width="905" height="294" /> </div>
        </div>
        <div class="step">
            <div class="title">
            <span class="count">step
              <strong>3</strong>
            </span>
                <strong>Shop the store's website</strong>
            </div>
            <div class="content">
                <img src="<?php echo $this->Html->url('/assets/img/how-to-3.jpg'); ?>" alt="" width="839" height="159" /> </div>
        </div>
        <div class="step">
            <div class="title">
            <span class="count">step
              <strong>4</strong>
            </span>
                <strong>Paste</strong> the code </div>
            <div class="content">
                <img src="<?php echo $this->Html->url('/assets/img/how-to-4.jpg'); ?>" alt="" width="778" height="310" /> </div>
        </div>
        <div class="step">
            <div class="content">
                <img src="<?php echo $this->Html->url('/assets/img/how-to-thanks.jpg'); ?>" alt="" width="696" height="53" /> </div>
        </div>
    </div>
</div>