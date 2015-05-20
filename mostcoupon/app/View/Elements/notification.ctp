<div class="container">
    <?php if ($this->Session->check('Message.success')) : ?>
        <div class="alert alert-success alert-dismissible" role="alert">
            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span
                    aria-hidden="true">&times;</span></button>
            <?php echo $this->Session->flash('success'); ?>
        </div>
    <?php endif; ?>
    <?php if ($this->Session->check('Message.info')) : ?>
        <div class="alert alert-info alert-dismissible" role="alert">
            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span
                    aria-hidden="true">&times;</span></button>
            <?php echo $this->Session->flash('info'); ?>
        </div>
    <?php endif; ?>
    <?php if ($this->Session->check('Message.warning')) : ?>
        <div class="alert alert-warning alert-dismissible" role="alert">
            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span
                    aria-hidden="true">&times;</span></button>
            <?php echo $this->Session->flash('warning'); ?>
        </div>
    <?php endif; ?>
    <?php if ($this->Session->check('Message.error')) : ?>
        <div class="alert alert-danger alert-dismissible" role="alert">
            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span
                    aria-hidden="true">&times;</span></button>
            <?php echo $this->Session->flash('error'); ?>
        </div>
    <?php endif; ?>
</div>